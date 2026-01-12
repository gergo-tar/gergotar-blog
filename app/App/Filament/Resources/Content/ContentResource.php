<?php

namespace App\Filament\Resources\Content;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Content\ContentResource\Pages\ListContentsPage;
use App\Filament\Resources\Content\ContentResource\Pages\CreateContentPage;
use App\Filament\Resources\Content\ContentResource\Pages\EditContentPage;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Domain\Content\Models\Content;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\Content\ContentResource\Forms\ContentForm;

class ContentResource extends Resource
{
    protected static ?string $model = Content::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    public static function form(Schema $schema): Schema
    {
        return $schema->components(ContentForm::make());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('general.title'))
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListContentsPage::route('/'),
            'create' => CreateContentPage::route('/create'),
            'edit' => EditContentPage::route('/{record}/edit'),
        ];
    }
}
