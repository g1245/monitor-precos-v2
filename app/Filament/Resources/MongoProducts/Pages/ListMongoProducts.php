<?php

namespace App\Filament\Resources\MongoProducts\Pages;

use App\Filament\Resources\MongoProducts\MongoProductResource;
use Filament\Resources\Pages\ListRecords;

class ListMongoProducts extends ListRecords
{
    protected static string $resource = MongoProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
