<?php

namespace App\Filament\Resources\Stores\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class StoresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->disk('public')
                    ->circular()
                    ->size(40)
                    ->defaultImageUrl(url('/images/default-store.png'))
                    ->toggleable(),
                
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('region')
                    ->label('Região')
                    ->searchable()
                    ->sortable()
                    ->default('—')
                    ->toggleable(),
                
                IconColumn::make('has_public_catalog')
                    ->label('Catálogo Público')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('full_url')
                    ->label('URL')
                    ->searchable()
                    ->limit(40)
                    ->url(fn ($record) => $record->full_url, shouldOpenInNewTab: true)
                    ->toggleable(),
                
                TextColumn::make('products_count')
                    ->label('Produtos')
                    ->counts('products')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('public_url')
                    ->label('Ver no Site')
                    ->url(fn ($record) => $record->public_url, shouldOpenInNewTab: true)
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->limit(20)
                    ->toggleable(),
                
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('has_public_catalog')
                    ->label('Catálogo Público')
                    ->placeholder('Todos')
                    ->trueLabel('Com Catálogo')
                    ->falseLabel('Sem Catálogo'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }
}
