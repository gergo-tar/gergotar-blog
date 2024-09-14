<?php

namespace App\Filament\Resources\Project;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Domain\Project\Models\Project;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\Project\ProjectResource\Pages;
use App\Filament\Resources\Project\ProjectResource\Forms\ProjectForm;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-code-bracket';

    public static function getModelLabel(): string
    {
        return __('Project::project.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Project::project.plural');
    }

    public static function form(Form $form): Form
    {
        return $form->schema(ProjectForm::make());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('translation.title')
                    ->label(__('general.title'))
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectsPage::route('/'),
            'create' => Pages\CreateProjectPage::route('/create'),
            'edit' => Pages\EditProjectPage::route('/{record}/edit'),
        ];
    }
}
