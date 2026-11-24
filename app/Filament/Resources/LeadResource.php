<?php

namespace App\Filament\Resources;

use App\Models\Lead;
use App\Filament\Resources\LeadResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationGroup = 'Dealers & Leads';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'new')->count();
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        $user = Auth::user();
        if ($user?->hasRole('dealer') && $user->dealer_id) {
            $query->where('assigned_dealer_id', $user->dealer_id);
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('phone')->tel()->required(),
                Forms\Components\TextInput::make('email')->email(),
                Forms\Components\Select::make('state_id')->relationship('state', 'name')->searchable(),
                Forms\Components\Select::make('district_id')->relationship('district', 'name')->searchable(),
                Forms\Components\TextInput::make('tehsil'),
                Forms\Components\TextInput::make('city'),
                Forms\Components\Select::make('interested_tractor_id')->label('Tractor')->relationship('tractor', 'name')->searchable(),
                Forms\Components\Select::make('interested_implement_id')->label('Implement')->relationship('implement', 'name')->searchable(),
                Forms\Components\TextInput::make('source'),
                Forms\Components\TextInput::make('utm_campaign'),
                Forms\Components\Select::make('status')
                    ->options([
                        'new' => 'New', 'contacted' => 'Contacted', 'quoted' => 'Quoted',
                        'won' => 'Won', 'lost' => 'Lost',
                    ])
                    ->required(),
                Forms\Components\Select::make('assigned_dealer_id')->label('Assigned dealer')->relationship('assignedDealer', 'name')->searchable(),
                Forms\Components\Textarea::make('notes')->columnSpanFull(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('phone')->searchable(),
                Tables\Columns\TextColumn::make('tractor.name')->label('Interested in')
                    ->formatStateUsing(fn ($record) => $record->tractor?->name ?? $record->implement?->name ?? '—'),
                Tables\Columns\TextColumn::make('state.name')->label('State'),
                Tables\Columns\TextColumn::make('district.name')->label('District')->placeholder('—'),
                Tables\Columns\TextColumn::make('tehsil')->placeholder('—'),
                Tables\Columns\TextColumn::make('assignedDealer.name')->label('Dealer'),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn (string $state) => match ($state) {
                        'new' => 'info',
                        'contacted' => 'warning',
                        'quoted' => 'primary',
                        'won' => 'success',
                        'lost' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('source'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'new' => 'New', 'contacted' => 'Contacted', 'quoted' => 'Quoted',
                    'won' => 'Won', 'lost' => 'Lost',
                ]),
                Tables\Filters\SelectFilter::make('state_id')->label('State')->relationship('state', 'name'),
                Tables\Filters\SelectFilter::make('assigned_dealer_id')->label('Dealer')->relationship('assignedDealer', 'name'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export_csv')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        return static::exportCsv();
                    }),
            ]);
    }

    protected static function exportCsv(): StreamedResponse
    {
        $leads = static::getEloquentQuery()->with(['state', 'district', 'tractor', 'implement', 'assignedDealer'])->get();

        return response()->streamDownload(function () use ($leads) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Date', 'Name', 'Phone', 'Email', 'State', 'District', 'Tehsil', 'City', 'Interested In', 'Source', 'Status', 'Dealer']);
            foreach ($leads as $lead) {
                fputcsv($out, [
                    $lead->created_at,
                    $lead->name,
                    $lead->phone,
                    $lead->email,
                    $lead->state?->name,
                    $lead->district?->name,
                    $lead->tehsil,
                    $lead->city,
                    $lead->tractor?->name ?? $lead->implement?->name,
                    $lead->source,
                    $lead->status,
                    $lead->assignedDealer?->name,
                ]);
            }
            fclose($out);
        }, 'leads-'.now()->format('Y-m-d').'.csv');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}
