<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsedListingResource\Pages;
use App\Models\UsedListing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UsedListingResource extends Resource
{
    protected static ?string $model = UsedListing::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Dealers & Leads';

    protected static ?string $navigationLabel = 'Used Listings';

    public static function getNavigationBadge(): ?string
    {
        $pending = static::getModel()::where('status', 'pending')->count();

        return $pending > 0 ? (string) $pending : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('listing_type')
                    ->options(['tractor' => 'Tractor', 'implement' => 'Implement'])
                    ->required()
                    ->live(),
                Forms\Components\Select::make('tractor_id')
                    ->relationship('tractor', 'name')
                    ->searchable()
                    ->visible(fn (callable $get) => $get('listing_type') === 'tractor'),
                Forms\Components\Select::make('implement_id')
                    ->relationship('implement', 'name')
                    ->searchable()
                    ->visible(fn (callable $get) => $get('listing_type') === 'implement'),
                Forms\Components\TextInput::make('seller_name')->required(),
                Forms\Components\TextInput::make('seller_phone')->tel()->required(),
                Forms\Components\TextInput::make('city')->required(),
                Forms\Components\Select::make('state_id')->relationship('state', 'name')->searchable(),
                Forms\Components\Select::make('district_id')->relationship('district', 'name')->searchable(),
                Forms\Components\TextInput::make('year_of_purchase')->numeric(),
                Forms\Components\TextInput::make('hours_used')->numeric(),
                Forms\Components\Select::make('condition')
                    ->options(['excellent' => 'Excellent', 'good' => 'Good', 'fair' => 'Fair'])
                    ->default('good')
                    ->required(),
                Forms\Components\TextInput::make('asking_price')->numeric()->prefix('₹')->required(),
                Forms\Components\Textarea::make('description')->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending', 'approved' => 'Approved',
                        'rejected' => 'Rejected', 'sold' => 'Sold',
                    ])
                    ->default('pending')
                    ->required(),
                Forms\Components\TextInput::make('rejected_reason'),
                Forms\Components\Repeater::make('images')
                    ->relationship('images')
                    ->schema([
                        Forms\Components\FileUpload::make('path')->image()->directory('used-listings')->required(),
                    ])
                    ->columnSpanFull()
                    ->defaultItems(0),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('listing_type')->badge(),
                Tables\Columns\TextColumn::make('tractor.name')->label('Item')
                    ->formatStateUsing(fn ($record) => $record->tractor?->name ?? $record->implement?->name ?? '—'),
                Tables\Columns\TextColumn::make('seller_name'),
                Tables\Columns\TextColumn::make('seller_phone'),
                Tables\Columns\TextColumn::make('city'),
                Tables\Columns\TextColumn::make('asking_price')->money('INR'),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn (string $state) => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'sold' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending', 'approved' => 'Approved',
                    'rejected' => 'Rejected', 'sold' => 'Sold',
                ]),
                Tables\Filters\SelectFilter::make('listing_type')->options(['tractor' => 'Tractor', 'implement' => 'Implement']),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (UsedListing $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (UsedListing $record) {
                        $record->update(['status' => 'approved']);
                        Notification::make()->title('Listing approved')->success()->send();
                    }),
                Tables\Actions\Action::make('reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (UsedListing $record) => $record->status === 'pending')
                    ->form([
                        Forms\Components\TextInput::make('rejected_reason')->required(),
                    ])
                    ->action(function (UsedListing $record, array $data) {
                        $record->update(['status' => 'rejected', 'rejected_reason' => $data['rejected_reason']]);
                        Notification::make()->title('Listing rejected')->warning()->send();
                    }),
                Tables\Actions\Action::make('mark_sold')
                    ->icon('heroicon-o-tag')
                    ->color('gray')
                    ->visible(fn (UsedListing $record) => $record->status === 'approved')
                    ->requiresConfirmation()
                    ->action(fn (UsedListing $record) => $record->update(['status' => 'sold'])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsedListings::route('/'),
            'create' => Pages\CreateUsedListing::route('/create'),
            'edit' => Pages\EditUsedListing::route('/{record}/edit'),
        ];
    }
}
