<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadAssignmentRuleResource\Pages;
use App\Models\LeadAssignmentRule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LeadAssignmentRuleResource extends Resource
{
    protected static ?string $model = LeadAssignmentRule::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationGroup = 'Dealers & Leads';

    protected static ?string $navigationLabel = 'Lead Assignment Rules';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('district_id')
                    ->relationship('district', 'name')
                    ->searchable()
                    ->helperText('Most specific match. Leave blank for a state- or brand-level fallback rule.'),
                Forms\Components\Select::make('state_id')
                    ->relationship('state', 'name')
                    ->searchable()
                    ->helperText('Used when no district rule matches.'),
                Forms\Components\Select::make('brand_id')
                    ->relationship('brand', 'name')
                    ->searchable(),
                Forms\Components\Select::make('dealer_id')
                    ->relationship('dealer', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('priority')
                    ->numeric()
                    ->default(0)
                    ->helperText('Higher priority wins when multiple rules match at the same tier.'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('district.name')->label('District')->placeholder('—'),
                Tables\Columns\TextColumn::make('state.name')->label('State')->placeholder('—'),
                Tables\Columns\TextColumn::make('brand.name')->label('Brand')->placeholder('—'),
                Tables\Columns\TextColumn::make('dealer.name')->label('Assigned dealer'),
                Tables\Columns\TextColumn::make('priority')->sortable(),
            ])
            ->actions([
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
            'index' => Pages\ListLeadAssignmentRules::route('/'),
            'create' => Pages\CreateLeadAssignmentRule::route('/create'),
            'edit' => Pages\EditLeadAssignmentRule::route('/{record}/edit'),
        ];
    }
}
