<?php

namespace App\Filament\Resources\Highlights\Pages;

use App\Filament\Resources\Highlights\HighlightResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHighlight extends EditRecord
{
    protected static string $resource = HighlightResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
