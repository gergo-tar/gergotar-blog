<?php

namespace App\Filament\Resources\Blog\PostResource\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Domain\User\Actions\GetEditorUsers;
use Filament\Tables\Filters\SelectFilter;

class PostTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns(PostTableColumns::make())
            ->filters([
                Filter::make('is_published')
                    ->label(__('Blog::post.is_published'))
                    ->query(fn ($query) => $query->where('is_published', true)),
                SelectFilter::make('author_id')
                    ->label(__('Blog::post.author'))
                    ->options(GetEditorUsers::run(['id', 'name'])->pluck('name', 'id')),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])->defaultSort('created_at', 'desc');
    }
}
