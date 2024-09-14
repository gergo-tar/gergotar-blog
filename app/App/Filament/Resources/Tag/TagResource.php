<?php

namespace App\Filament\Resources\Tag;

use Filament\Tables;
use Filament\Forms\Form;
use Domain\Tag\Models\Tag;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Filament\Resources\Tag\TagResource\Pages;
use App\Filament\Resources\Tag\TagResource\Forms\TagForm;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function getModelLabel(): string
    {
        return __('Tag::tag.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Tag::tag.plural');
    }

    public static function form(Form $form): Form
    {
        return $form->schema(TagForm::make());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('translation.name')
                    ->label(__('general.name'))
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
            'index' => Pages\ListTagsPage::route('/'),
            'create' => Pages\CreateTagPage::route('/create'),
            'edit' => Pages\EditTagPage::route('/{record}/edit'),
        ];
    }
}
