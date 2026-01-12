<?php

namespace App\Filament\Resources\Stores\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                FileUpload::make('logo')
                    ->label('Logo')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml'])
                    ->maxSize(2048)
                    ->directory('stores/logos')
                    ->disk('public')
                    ->columnSpanFull(),
                KeyValue::make('metadata')
                    ->columnSpanFull(),
            ]);
    }
}
