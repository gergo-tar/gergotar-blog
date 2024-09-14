<?php

namespace App\Filament\Resources\Blog\PostResource\Tables;

use Filament\Tables;
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
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
