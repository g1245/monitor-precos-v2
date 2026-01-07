<?php

namespace App\Filament\Resources\Stores\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Schemas\Schema;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome da Loja')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ex: Magazine Luiza')
                    ->columnSpan(2),
                
                TextInput::make('full_url')
                    ->label('URL da Loja')
                    ->url()
                    ->required()
                    ->maxLength(255)
                    ->placeholder('https://www.magazineluiza.com.br')
                    ->columnSpan(2),
                
                TextInput::make('region')
                    ->label('Região')
                    ->maxLength(100)
                    ->placeholder('Ex: Nacional, Sul, Sudeste')
                    ->columnSpan(1),
                
                Toggle::make('has_public_catalog')
                    ->label('Possui Catálogo Público')
                    ->helperText('Indica se a loja disponibiliza um catálogo público de produtos')
                    ->default(false)
                    ->inline(false)
                    ->columnSpan(1),
                
                FileUpload::make('logo')
                    ->label('Logo da Loja')
                    ->image()
                    ->disk('public')
                    ->directory('stores/logos')
                    ->visibility('public')
                    ->imageEditor()
                    ->imageCropAspectRatio('1:1')
                    ->imageResizeTargetWidth(400)
                    ->imageResizeTargetHeight(400)
                    ->maxSize(2048)
                    ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg', 'image/webp'])
                    ->columnSpanFull(),
                
                Textarea::make('metadata')
                    ->label('Metadados (JSON)')
                    ->helperText('Dados adicionais em formato JSON')
                    ->rows(5)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
