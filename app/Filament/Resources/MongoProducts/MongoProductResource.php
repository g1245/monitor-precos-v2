<?php

namespace App\Filament\Resources\MongoProducts;

use App\Filament\Resources\MongoProducts\Pages\ListMongoProducts;
use App\Filament\Resources\MongoProducts\Tables\MongoProductsTable;
use App\Models\MongoProduct;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MongoProductResource extends Resource
{
    protected static ?string $model = MongoProduct::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCubeTransparent;

    protected static ?string $navigationLabel = 'Produtos MongoDB';

    protected static ?string $modelLabel = 'Produto';

    protected static ?string $pluralModelLabel = 'Produtos';

    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
    {
        return MongoProductsTable::configure($table);
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
            'index' => ListMongoProducts::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
