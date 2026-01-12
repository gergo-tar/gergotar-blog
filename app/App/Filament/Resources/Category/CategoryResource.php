<?php

namespace App\Filament\Resources\Category;

use Filament\Schemas\Schema;
use App\Filament\Resources\Category\CategoryResource\Pages\ListCategoriesPage;
use App\Filament\Resources\Category\CategoryResource\Pages\CreateCategoryPage;
use App\Filament\Resources\Category\CategoryResource\Pages\EditCategoryPage;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Domain\Category\Models\Category;
use App\Filament\Resources\Category\CategoryResource\Forms\CategoryForm;
use App\Filament\Resources\Category\CategoryResource\Tables\CategoryTable;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('Category::category.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Category::category.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components(CategoryForm::make());
    }

    public static function table(Table $table): Table
    {
        return CategoryTable::make($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategoriesPage::route('/'),
            'create' => CreateCategoryPage::route('/create'),
            'edit' => EditCategoryPage::route('/{record}/edit'),
        ];
    }
}
