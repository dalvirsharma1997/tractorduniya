<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationGroup = 'Site Settings';

    protected static ?string $navigationLabel = 'Header & Footer Menus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('location')
                    ->options(['header' => 'Header', 'footer' => 'Footer'])
                    ->required()
                    ->live()
                    ->helperText('Header: top items are the main nav links, child items become a dropdown. Footer: top items are column headings, child items are the links under that column.'),
                Forms\Components\Select::make('parent_id')
                    ->label('Parent (leave empty for a top-level item)')
                    ->options(fn (callable $get) => MenuItem::query()
                        ->where('location', $get('location'))
                        ->whereNull('parent_id')
                        ->pluck('label', 'id'))
                    ->searchable(),
                Forms\Components\TextInput::make('label')->required()->maxLength(60),
                Forms\Components\TextInput::make('url')
                    ->label('Link (relative path like /tractors, or a full URL)')
                    ->helperText('Leave blank for a footer column heading with no link of its own.'),
                Forms\Components\Toggle::make('opens_new_tab')->label('Open in new tab'),
                Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                Forms\Components\Toggle::make('is_active')->default(true),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('location')->badge(),
                Tables\Columns\TextColumn::make('parent.label')->label('Parent')->placeholder('— top level —'),
                Tables\Columns\TextColumn::make('label')->searchable(),
                Tables\Columns\TextColumn::make('url')->placeholder('—'),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('location')->options(['header' => 'Header', 'footer' => 'Footer']),
            ])
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
