<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StateResource\Pages;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class StateResource extends Resource
{
    protected static ?string $model = State::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationGroup = 'Geography';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
            Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Forms\Components\Textarea::make('rto_tax_notes')->label('RTO/tax notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('districts_count')->counts('districts')->label('Districts'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('import_geography_csv')
                    ->label('Bulk import States/Districts/Tehsils (CSV)')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        Forms\Components\Placeholder::make('format_help')
                            ->label('CSV format')
                            ->content('Columns: state, district, tehsil — one row per tehsil. The same state/district can repeat across rows; existing entries are matched and skipped, new ones are created.'),
                        Forms\Components\FileUpload::make('file')
                            ->label('CSV file')
                            ->acceptedFileTypes(['text/csv', 'text/plain'])
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $counts = app(\App\Services\GeographyCsvImporter::class)->importFromPath(
                            storage_path('app/public/'.$data['file'])
                        );

                        Notification::make()
                            ->title('Geography import complete')
                            ->body("Added {$counts['states']} states, {$counts['districts']} districts, {$counts['tehsils']} tehsils.")
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            StateResource\RelationManagers\DistrictsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStates::route('/'),
            'create' => Pages\CreateState::route('/create'),
            'edit' => Pages\EditState::route('/{record}/edit'),
        ];
    }
}
