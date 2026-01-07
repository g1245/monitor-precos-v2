<?php

namespace App\Filament\Resources\MongoProducts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;

class MongoProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('merchant_thumb_url')
                    ->label('Imagem')
                    ->size(50)
                    ->defaultImageUrl(url('/images/no-image.png'))
                    ->toggleable(),

                TextColumn::make('product_name')
                    ->label('Nome do Produto')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(50)
                    ->wrap(),

                TextColumn::make('brand_name')
                    ->label('Marca')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->default('—'),

                TextColumn::make('merchant_name')
                    ->label('Loja')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-building-storefront')
                    ->toggleable(),

                TextColumn::make('merchant_category')
                    ->label('Categoria')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->toggleable(),

                TextColumn::make('search_price')
                    ->label('Preço')
                    ->money('BRL')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                TextColumn::make('rrp_price')
                    ->label('Preço Sugerido')
                    ->money('BRL')
                    ->sortable()
                    ->toggleable()
                    ->default('—'),

                TextColumn::make('discount')
                    ->label('Desconto')
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(function ($record) {
                        $discount = $record->getDiscountPercentage();
                        return $discount ? $discount . '%' : '—';
                    })
                    ->toggleable(),

                IconColumn::make('in_stock')
                    ->label('Em Estoque')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->getStateUsing(fn ($record) => in_array(strtolower($record->in_stock ?? ''), ['yes', '1', 'true', 'in stock']))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('stock_quantity')
                    ->label('Quantidade')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->default('—'),

                TextColumn::make('aw_product_id')
                    ->label('ID Awin')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('merchant_product_id')
                    ->label('ID Produto')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('last_updated')
                    ->label('Última Atualização')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('search')
                    ->form([
                        TextInput::make('query')
                            ->label('Pesquisar')
                            ->placeholder('Buscar por nome, descrição, marca ou palavras-chave'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['query'] ?? null,
                            fn (Builder $query, $search): Builder => $query->search($search),
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['query']) {
                            return null;
                        }

                        return 'Pesquisa: ' . $data['query'];
                    }),

                TernaryFilter::make('in_stock')
                    ->label('Em Estoque')
                    ->placeholder('Todos')
                    ->trueLabel('Sim')
                    ->falseLabel('Não')
                    ->queries(
                        true: fn (Builder $query) => $query->where('in_stock', 'yes'),
                        false: fn (Builder $query) => $query->where('in_stock', '!=', 'yes'),
                        blank: fn (Builder $query) => $query,
                    ),

                Filter::make('has_discount')
                    ->label('Com Desconto')
                    ->toggle()
                    ->query(fn (Builder $query): Builder => $query->where('rrp_price', '>', 0)
                        ->whereRaw('rrp_price > search_price')),

                Filter::make('price_range')
                    ->form([
                        TextInput::make('price_from')
                            ->label('Preço Mínimo')
                            ->numeric()
                            ->prefix('R$'),
                        TextInput::make('price_to')
                            ->label('Preço Máximo')
                            ->numeric()
                            ->prefix('R$'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['price_from'] ?? null,
                                fn (Builder $query, $price): Builder => $query->where('search_price', '>=', $price),
                            )
                            ->when(
                                $data['price_to'] ?? null,
                                fn (Builder $query, $price): Builder => $query->where('search_price', '<=', $price),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['price_from'] ?? null) {
                            $indicators[] = 'Preço mínimo: R$ ' . number_format($data['price_from'], 2, ',', '.');
                        }

                        if ($data['price_to'] ?? null) {
                            $indicators[] = 'Preço máximo: R$ ' . number_format($data['price_to'], 2, ',', '.');
                        }

                        return $indicators;
                    }),

                SelectFilter::make('merchant_name')
                    ->label('Loja')
                    ->options(function () {
                        return \App\Models\MongoProduct::query()
                            ->distinct('merchant_name')
                            ->pluck('merchant_name', 'merchant_name')
                            ->filter()
                            ->sort()
                            ->toArray();
                    })
                    ->searchable()
                    ->multiple(),

                SelectFilter::make('merchant_category')
                    ->label('Categoria')
                    ->options(function () {
                        return \App\Models\MongoProduct::query()
                            ->distinct('merchant_category')
                            ->pluck('merchant_category', 'merchant_category')
                            ->filter()
                            ->sort()
                            ->toArray();
                    })
                    ->searchable()
                    ->multiple(),
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->persistSortInSession()
            ->persistSearchInSession()
            ->persistFiltersInSession()
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
