<?php

namespace App\Filament\Resources\NewsletterLeads\Pages;

use App\Filament\Resources\NewsletterLeads\NewsletterLeadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNewsletterLeads extends ListRecords
{
    protected static string $resource = NewsletterLeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
