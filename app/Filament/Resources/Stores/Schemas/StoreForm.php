<?php

namespace App\Filament\Resources\Stores\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('internal_name', Str::slug($state))),

                TextInput::make('internal_name')
                    ->label('Nome Interno')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Usado internamente para identificação da loja'),

                FileUpload::make('logo')
                    ->label('Logo')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml'])
                    ->maxSize(2048)
                    ->directory('stores/logos')
                    ->disk('public')
                    ->columnSpanFull(),

                Toggle::make('has_public')
                    ->label('Tem página pública')
                    ->helperText('Se ativado, a loja terá uma página pública acessível aos usuários')
                    ->default(false),

                KeyValue::make('metadata')
                    ->label('Metadados')
                    ->helperText('Informações adicionais sobre a loja em formato chave-valor')
                    ->columnSpanFull(),
            ]);
    }
}
