<?php

namespace App\Filament\Resources\Project;

use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Domain\Project\Models\Project;
use App\Filament\Resources\Project\ProjectResource\Pages;
use App\Filament\Resources\Project\ProjectResource\Forms\ProjectForm;
use App\Filament\Resources\Project\ProjectResource\Tables\ProjectTable;

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
        return ProjectTable::make($table);
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
