<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Content';

    public static function getNavigationBadge(): ?string
    {
        $pending = static::getModel()::where('status', 'pending')->count();

        return $pending > 0 ? (string) $pending : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('reviewable_type')
                    ->options(['tractor' => 'Tractor', 'implement' => 'Implement', 'brand' => 'Brand'])
                    ->live(),
                Forms\Components\Select::make('reviewable_id')
                    ->label('Related item')
                    ->options(function (callable $get) {
                        return match ($get('reviewable_type')) {
                            'tractor' => \App\Models\Tractor::pluck('name', 'id'),
                            'implement' => \App\Models\Implement::pluck('name', 'id'),
                            'brand' => \App\Models\Brand::pluck('name', 'id'),
                            default => [],
                        };
                    })
                    ->searchable(),
                Forms\Components\TextInput::make('reviewer_name')->required(),
                Forms\Components\Select::make('rating')->options(array_combine(range(1, 5), range(1, 5)))->required(),
                Forms\Components\TextInput::make('title'),
                Forms\Components\Textarea::make('body')->required()->columnSpanFull(),
                Forms\Components\Select::make('status')->options(['pending' => 'Pending', 'approved' => 'Approved'])->default('pending')->required(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reviewer_name'),
                Tables\Columns\TextColumn::make('reviewable_type')->badge(),
                Tables\Columns\TextColumn::make('rating'),
                Tables\Columns\TextColumn::make('title')->limit(40),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn (string $state) => $state === 'approved' ? 'success' : 'warning'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(['pending' => 'Pending', 'approved' => 'Approved']),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (Review $record) => $record->status === 'pending')
                    ->action(function (Review $record) {
                        $record->update(['status' => 'approved']);
                        Notification::make()->title('Review approved')->success()->send();
                    }),
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
