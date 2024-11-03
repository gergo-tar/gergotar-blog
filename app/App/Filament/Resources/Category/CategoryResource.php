<?php

namespace App\Filament\Resources\Category;

use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Domain\Category\Models\Category;
use App\Filament\Resources\Category\CategoryResource\Pages;
use App\Filament\Resources\Category\CategoryResource\Forms\CategoryForm;
use App\Filament\Resources\Category\CategoryResource\Tables\CategoryTable;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('Category::category.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Category::category.plural');
    }

    public static function form(Form $form): Form
    {
        return $form->schema(CategoryForm::make());
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
            'index' => Pages\ListCategoriesPage::route('/'),
            'create' => Pages\CreateCategoryPage::route('/create'),
            'edit' => Pages\EditCategoryPage::route('/{record}/edit'),
        ];
    }
}
