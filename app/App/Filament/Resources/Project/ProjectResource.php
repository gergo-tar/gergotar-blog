<?php

namespace App\Filament\Resources\Project;

use Filament\Schemas\Schema;
use App\Filament\Resources\Project\ProjectResource\Pages\ListProjectsPage;
use App\Filament\Resources\Project\ProjectResource\Pages\CreateProjectPage;
use App\Filament\Resources\Project\ProjectResource\Pages\EditProjectPage;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Domain\Project\Models\Project;
use App\Filament\Resources\Project\ProjectResource\Forms\ProjectForm;
use App\Filament\Resources\Project\ProjectResource\Tables\ProjectTable;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-code-bracket';

    public static function getModelLabel(): string
    {
        return __('Project::project.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Project::project.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components(ProjectForm::make());
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
            'index' => ListProjectsPage::route('/'),
            'create' => CreateProjectPage::route('/create'),
            'edit' => EditProjectPage::route('/{record}/edit'),
        ];
    }
}
