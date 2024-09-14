<?php

namespace App\Filament\Resources\Blog\PostResource\Tables;

use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;

class PostTableColumns
{
    public static function make(): array
    {
        return [
            Split::make([
                TextColumn::make('translation.title')
                    ->label(__('general.title'))
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable(),

                Stack::make([
                    ImageColumn::make('author.avatar')
                        ->circular()
                        ->grow(false),
                    TextColumn::make('author.name')
                        ->label(__('Blog::post.author'))
                        ->searchable()
                        ->sortable()
                        ->visibleFrom('lg'),
                ])->visibleFrom('lg'),

                IconColumn::make('is_published')
                    ->label(__('Blog::post.is_published'))
                    ->boolean(),

                Stack::make([
                    ImageColumn::make('author.avatar')
                        ->circular()
                        ->grow(false),
                    TextColumn::make('author.name')
                        ->label(__('Blog::post.author'))
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('published_at')
                        ->label(__('Blog::post.published_at'))
                        ->date()
                        ->searchable()
                        ->sortable(),
                ])->hiddenFrom('lg'),

                TextColumn::make('published_at')
                    ->label(__('Blog::post.published_at'))
                    ->date()
                    ->searchable()
                    ->sortable()
                    ->visibleFrom('lg'),
                TextColumn::make('updatedBy.name')
                    ->label(__('audit.updated_by'))
                    ->visibleFrom('lg'),
                TextColumn::make('created_at')
                    ->label(__('audit.created_at'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visibleFrom('lg'),
                TextColumn::make('updated_at')
                    ->label(__('audit.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visibleFrom('lg'),
            ]),
        ];
    }
}
