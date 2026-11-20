<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TodaysLeadsWidget extends BaseWidget
{
    protected static ?int $sort = -1;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = "Today's Leads";

    public function table(Table $table): Table
    {
        return $table
            ->query(Lead::query()->whereDate('created_at', today())->latest())
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->time(),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('tractor.name')->label('Interested in')
                    ->formatStateUsing(fn ($record) => $record->tractor?->name ?? $record->implement?->name ?? '—'),
                Tables\Columns\TextColumn::make('assignedDealer.name')->label('Dealer')->placeholder('Unassigned'),
                Tables\Columns\TextColumn::make('status')->badge(),
            ])
            ->paginated(false);
    }
}
