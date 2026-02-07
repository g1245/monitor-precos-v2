<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                FileUpload::make('desktop_image')
                    ->label('Imagem Desktop')
                    ->helperText('Imagem para visualização em computadores (recomendado: 1200x250px)')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(2048)
                    ->directory('banners')
                    ->disk('public')
                    ->columnSpan(1),

                FileUpload::make('mobile_image')
                    ->label('Imagem Mobile')
                    ->helperText('Imagem para visualização em dispositivos móveis (recomendado: 600x200px)')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(2048)
                    ->directory('banners')
                    ->disk('public')
                    ->columnSpan(1),

                TextInput::make('link')
                    ->label('Link')
                    ->helperText('URL para a página de destino quando o banner for clicado')
                    ->maxLength(255)
                    ->url(),

                TextInput::make('order')
                    ->label('Ordem')
                    ->helperText('Define a ordem de exibição dos banners (menor número aparece primeiro)')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->minValue(0),

                Toggle::make('is_active')
                    ->label('Ativo')
                    ->helperText('Desative para ocultar este banner do carrossel')
                    ->default(true)
                    ->required(),
            ]);
    }
}
