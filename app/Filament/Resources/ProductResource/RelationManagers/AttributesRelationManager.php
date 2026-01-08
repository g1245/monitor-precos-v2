<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AttributesRelationManager extends RelationManager
{
    protected static string $relationship = 'attributes';

    protected static ?string $title = 'Especificações Técnicas';

    protected static ?string $recordTitleAttribute = 'key';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->label('Atributo')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ex: Cor, Tamanho, Voltagem, Material')
                    ->helperText('Nome do atributo técnico'),

                Forms\Components\Textarea::make('description')
                    ->label('Valor')
                    ->required()
                    ->rows(2)
                    ->placeholder('Ex: Azul Marinho, 42, 220V, Alumínio')
                    ->helperText('Valor ou descrição do atributo'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('key')
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('Atributo')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Valor')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn ($record): string => $record->description),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Adicionar Atributo'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('key', 'asc')
            ->emptyStateHeading('Nenhuma especificação técnica')
            ->emptyStateDescription('Adicione atributos e especificações técnicas do produto.')
            ->emptyStateIcon('heroicon-o-list-bullet');
    }
}
