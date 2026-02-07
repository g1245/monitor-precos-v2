<?php

namespace App\Filament\Resources\NewsletterLeads\Pages;

use App\Filament\Resources\NewsletterLeads\NewsletterLeadResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNewsletterLead extends EditRecord
{
    protected static string $resource = NewsletterLeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
