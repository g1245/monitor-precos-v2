<?php

namespace App\Filament\Resources\Highlights\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class HighlightForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),

                FileUpload::make('image')
                    ->label('Imagem')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                    ->maxSize(2048)
                    ->directory('highlights')
                    ->disk('public')
                    ->columnSpanFull(),

                TextInput::make('discount_text')
                    ->label('Texto de Desconto')
                    ->helperText('Ex: 20%, 50% OFF, etc.')
                    ->maxLength(255),

                TextInput::make('link')
                    ->label('Link')
                    ->helperText('URL para a página de destino do destaque')
                    ->maxLength(255)
                    ->url(),
            ]);
    }
}
