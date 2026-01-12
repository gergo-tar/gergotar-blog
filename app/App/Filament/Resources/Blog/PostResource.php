<?php

namespace App\Filament\Resources\Blog;

use Filament\Schemas\Schema;
use App\Filament\Resources\Blog\PostResource\Pages\ListPostsPage;
use App\Filament\Resources\Blog\PostResource\Pages\CreatePostPage;
use App\Filament\Resources\Blog\PostResource\Pages\EditPostPage;
use Filament\Tables\Table;
use Domain\Blog\Models\Post;
use Filament\Resources\Resource;
use App\Filament\Resources\Blog\PostResource\Forms\PostForm;
use App\Filament\Resources\Blog\PostResource\Tables\PostTable;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-book-open';

    public static function getModelLabel(): string
    {
        return __('Blog::post.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Blog::post.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components(PostForm::make());
    }

    public static function table(Table $table): Table
    {
        return PostTable::make($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPostsPage::route('/'),
            'create' => CreatePostPage::route('/create'),
            'edit' => EditPostPage::route('/{record}/edit'),
        ];
    }
}
