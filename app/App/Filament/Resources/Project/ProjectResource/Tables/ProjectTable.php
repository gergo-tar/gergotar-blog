<?php

namespace App\Filament\Resources\Project\ProjectResource\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class ProjectTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('translation.title')
                    ->label(__('general.title'))
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->withAggregate('translation', 'title')
                            ->orderBy('translation_title', $direction);
                    }),
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
}
