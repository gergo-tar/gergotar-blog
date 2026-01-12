<?php

namespace App\Filament\Resources\Tag;

use Filament\Schemas\Schema;
use App\Filament\Resources\Tag\TagResource\Pages\ListTagsPage;
use App\Filament\Resources\Tag\TagResource\Pages\CreateTagPage;
use App\Filament\Resources\Tag\TagResource\Pages\EditTagPage;
use Domain\Tag\Models\Tag;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\Tag\TagResource\Forms\TagForm;
use App\Filament\Resources\Tag\TagResource\Tables\TagTable;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-tag';

    public static function getModelLabel(): string
    {
        return __('Tag::tag.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Tag::tag.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components(TagForm::make());
    }

    public static function table(Table $table): Table
    {
        return TagTable::make($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTagsPage::route('/'),
            'create' => CreateTagPage::route('/create'),
            'edit' => EditTagPage::route('/{record}/edit'),
        ];
    }
}
