<?php

namespace App\Filament\Resources\Category\CategoryResource\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class CategoryTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('translation.name')
                    ->label(__('general.name'))
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->withAggregate('translation', 'name')
                            ->orderBy('translation_name', $direction);
                    }),
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
}
