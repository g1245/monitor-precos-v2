<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DepartmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'departments';

    protected static ?string $title = 'Departamentos';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id')
                    ->label('Departamento')
                    ->options(function () {
                        return Department::query()
                            ->orderBy('name')
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required()
                    ->preload(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('permalink')
                    ->label('Permalink')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Departamento Pai')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->default('—'),

                Tables\Columns\IconColumn::make('isRoot')
                    ->label('Raiz')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->isRoot())
                    ->toggleable(),

                Tables\Columns\TextColumn::make('children_count')
                    ->label('Subdepartamentos')
                    ->counts('children')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('root_only')
                    ->label('Apenas raiz')
                    ->query(fn (Builder $query): Builder => $query->whereNull('parent_id')),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Vincular Departamento')
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn (Builder $query) => $query->orderBy('name'))
                    ->recordTitle(fn (Department $record): string => $record->name),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Nenhum departamento vinculado')
            ->emptyStateDescription('Vincule este produto a um ou mais departamentos para facilitar a navegação no catálogo.')
            ->emptyStateIcon('heroicon-o-folder');
    }
}
