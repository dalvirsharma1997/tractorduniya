<?php

namespace App\Filament\Widgets;

use App\Models\UsedListing;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingApprovalsWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Pending Used-Listing Approvals';

    public function table(Table $table): Table
    {
        return $table
            ->query(UsedListing::query()->where('status', 'pending')->latest())
            ->columns([
                Tables\Columns\TextColumn::make('listing_type')->badge(),
                Tables\Columns\TextColumn::make('tractor.name')->label('Item')
                    ->formatStateUsing(fn ($record) => $record->tractor?->name ?? $record->implement?->name ?? '—'),
                Tables\Columns\TextColumn::make('seller_name'),
                Tables\Columns\TextColumn::make('asking_price')->money('INR'),
                Tables\Columns\TextColumn::make('created_at')->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (UsedListing $record) {
                        $record->update(['status' => 'approved']);
                        Notification::make()->title('Listing approved')->success()->send();
                    }),
                Tables\Actions\Action::make('reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function (UsedListing $record) {
                        $record->update(['status' => 'rejected']);
                        Notification::make()->title('Listing rejected')->warning()->send();
                    }),
            ])
            ->paginated(false);
    }
}
