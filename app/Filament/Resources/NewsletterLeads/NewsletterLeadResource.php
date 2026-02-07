<?php

namespace App\Filament\Resources\NewsletterLeads;

use App\Filament\Resources\NewsletterLeads\Pages\CreateNewsletterLead;
use App\Filament\Resources\NewsletterLeads\Pages\EditNewsletterLead;
use App\Filament\Resources\NewsletterLeads\Pages\ListNewsletterLeads;
use App\Filament\Resources\NewsletterLeads\Schemas\NewsletterLeadForm;
use App\Filament\Resources\NewsletterLeads\Tables\NewsletterLeadsTable;
use App\Models\NewsletterLead;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NewsletterLeadResource extends Resource
{
    protected static ?string $model = NewsletterLead::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $navigationLabel = 'Newsletter Leads';

    protected static ?string $modelLabel = 'Newsletter Lead';

    protected static ?string $pluralModelLabel = 'Newsletter Leads';

    public static function form(Schema $schema): Schema
    {
        return NewsletterLeadForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NewsletterLeadsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNewsletterLeads::route('/'),
            'create' => CreateNewsletterLead::route('/create'),
            'edit' => EditNewsletterLead::route('/{record}/edit'),
        ];
    }
}
