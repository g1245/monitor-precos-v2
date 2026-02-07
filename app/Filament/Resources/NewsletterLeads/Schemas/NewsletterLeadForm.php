<?php

namespace App\Filament\Resources\NewsletterLeads\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class NewsletterLeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
            ]);
    }
}
