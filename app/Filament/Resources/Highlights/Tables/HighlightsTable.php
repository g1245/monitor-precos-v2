<?php

namespace App\Filament\Resources\Highlights\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HighlightsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Imagem')
                    ->disk('public')
                    ->height(50),
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('discount_text')
                    ->label('Desconto')
                    ->searchable(),
                TextColumn::make('link')
                    ->label('Link')
                    ->limit(30),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
