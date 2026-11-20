<?php

namespace App\Filament\Widgets;

use App\Models\ActivityLog;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentActivityWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Recent Activity';

    public function table(Table $table): Table
    {
        return $table
            ->query(ActivityLog::query()->latest('created_at'))
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->since(),
                Tables\Columns\TextColumn::make('user.name')->placeholder('System'),
                Tables\Columns\TextColumn::make('action'),
                Tables\Columns\TextColumn::make('subject_type'),
            ])
            ->paginated([10]);
    }
}
