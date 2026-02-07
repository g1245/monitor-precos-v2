<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('store.name')
                    ->label('Store')
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('price')
                    ->label('Price')
                    ->formatStateUsing(function ($state, $record) {
                        $price = 'R$ ' . number_format($state, 2, ',', '.');
                        $regular = $record->price_regular;
                        if ($regular) {
                            $regularFormatted = 'R$ ' . number_format($regular, 2, ',', '.');
                            return $price . '<br><small>' . $regularFormatted . '</small>';
                        }
                        return $price;
                    })
                    ->html()
                    ->sortable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('departments.name')
                    ->label('Departamentos')
                    ->listWithLineBreaks()
                    ->limitList(3)
                    ->expandableLimitedList(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('store')
                    ->relationship('store', 'name')
                    ->label('Store'),
            ])
            ->recordActions([
                Action::make('view_product')
                    ->label('View Product')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => route('product.show', ['id' => $record->id, 'slug' => Str::of($record->name)->slug()]))
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
