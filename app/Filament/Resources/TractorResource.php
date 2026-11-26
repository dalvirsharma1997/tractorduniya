<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TractorResource\Pages;
use App\Models\Brand;
use App\Models\Category;
use App\Models\State;
use App\Models\Tractor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class TractorResource extends Resource
{
    protected static ?string $model = Tractor::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Catalog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tractor')
                    ->columnSpanFull()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Overview')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                Forms\Components\Select::make('brand_id')
                                    ->label('Brand')
                                    ->relationship('brand', 'name')
                                    ->searchable()
                                    ->required(),
                                Forms\Components\Select::make('category_id')
                                    ->label('Category')
                                    ->relationship('category', 'name', fn ($query) => $query->where('type', 'tractor_category'))
                                    ->searchable(),
                                Forms\Components\TextInput::make('series'),
                                Forms\Components\Select::make('status')
                                    ->options(['active' => 'Active', 'discontinued' => 'Discontinued', 'upcoming' => 'Upcoming'])
                                    ->required()
                                    ->default('active'),
                                Forms\Components\Toggle::make('is_featured')->label('Featured on homepage'),
                                Forms\Components\TextInput::make('launch_year')->numeric(),
                                Forms\Components\TextInput::make('warranty_text')->columnSpanFull(),
                                Forms\Components\Textarea::make('description')->rows(4)->columnSpanFull(),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Specs')
                            ->schema([
                                Forms\Components\TextInput::make('hp')->label('HP')->numeric()->required(),
                                Forms\Components\Select::make('drive_type')
                                    ->options(['2WD' => '2WD', '4WD' => '4WD'])
                                    ->required()
                                    ->default('2WD'),
                                Forms\Components\TextInput::make('engine_cc')->label('Engine CC')->numeric(),
                                Forms\Components\TextInput::make('cylinders')->numeric(),
                                Forms\Components\TextInput::make('fuel_tank_litres')->label('Fuel tank (litres)')->numeric(),
                                Forms\Components\TextInput::make('transmission_type'),
                                Forms\Components\TextInput::make('gearbox'),
                                Forms\Components\TextInput::make('pto_hp')->label('PTO HP')->numeric(),
                                Forms\Components\TextInput::make('lifting_capacity_kg')->label('Lifting capacity (kg)')->numeric(),
                                Forms\Components\Repeater::make('specs')
                                    ->relationship('specs')
                                    ->label('Additional specs / features')
                                    ->schema([
                                        Forms\Components\TextInput::make('spec_group')->default('features')->required(),
                                        Forms\Components\TextInput::make('spec_key')->required(),
                                        Forms\Components\TextInput::make('spec_value')->required(),
                                        Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                                    ])
                                    ->columns(4)
                                    ->columnSpanFull()
                                    ->defaultItems(0),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Pricing')
                            ->schema([
                                Forms\Components\Repeater::make('prices')
                                    ->relationship('prices')
                                    ->label('State-wise price rows (leave state empty for national default)')
                                    ->schema([
                                        Forms\Components\Select::make('state_id')
                                            ->label('State')
                                            ->options(State::pluck('name', 'id'))
                                            ->searchable()
                                            ->placeholder('National (all states)'),
                                        Forms\Components\TextInput::make('price_min')->numeric()->required()->prefix('₹'),
                                        Forms\Components\TextInput::make('price_max')->numeric()->required()->prefix('₹'),
                                        Forms\Components\TextInput::make('on_road_price')->numeric()->prefix('₹'),
                                        Forms\Components\DatePicker::make('effective_date')->required()->default(now()),
                                    ])
                                    ->columns(5)
                                    ->columnSpanFull()
                                    ->defaultItems(1),
                            ]),

                        Forms\Components\Tabs\Tab::make('Images')
                            ->schema([
                                Forms\Components\Repeater::make('images')
                                    ->relationship('images')
                                    ->label('Gallery')
                                    ->schema([
                                        Forms\Components\FileUpload::make('path')
                                            ->label('Image')
                                            ->image()
                                            ->directory('tractors')
                                            ->required(),
                                        Forms\Components\Toggle::make('is_primary')->label('Primary image'),
                                        Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                                    ])
                                    ->columns(3)
                                    ->columnSpanFull()
                                    ->defaultItems(0)
                                    ->reorderable('sort_order'),
                            ]),

                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Forms\Components\Fieldset::make('Meta tags')
                                    ->relationship('seoMeta')
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')->label('Meta title')->maxLength(70)->columnSpanFull(),
                                        Forms\Components\Textarea::make('meta_description')->label('Meta description')->rows(2)->maxLength(160)->columnSpanFull(),
                                        Forms\Components\FileUpload::make('og_image')->label('Social share image')->image()->directory('seo')->columnSpanFull(),
                                    ])
                                    ->columns(1),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('brand.name')->sortable(),
                Tables\Columns\TextColumn::make('hp')->label('HP')->sortable(),
                Tables\Columns\TextColumn::make('drive_type')->badge(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn (string $state) => match ($state) {
                        'active' => 'success',
                        'upcoming' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_featured')->boolean(),
                Tables\Columns\TextColumn::make('launch_year'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('brand_id')->label('Brand')->relationship('brand', 'name'),
                Tables\Filters\SelectFilter::make('status')->options([
                    'active' => 'Active', 'discontinued' => 'Discontinued', 'upcoming' => 'Upcoming',
                ]),
                Tables\Filters\SelectFilter::make('drive_type')->options(['2WD' => '2WD', '4WD' => '4WD']),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('replicate')
                        ->label('Replicate')
                        ->icon('heroicon-o-square-2-stack')
                        ->requiresConfirmation()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            foreach ($records as $record) {
                                $replica = $record->replicate(['slug']);
                                $replica->name = $replica->name.' (Copy)';
                                $replica->slug = Str::slug($replica->name).'-'.Str::random(6);
                                $replica->save();
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('import_csv')
                    ->label('Bulk import (CSV)')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        Forms\Components\FileUpload::make('file')
                            ->label('CSV file')
                            ->acceptedFileTypes(['text/csv', 'text/plain'])
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        app(\App\Services\TractorCsvImporter::class)->importFromPath(
                            storage_path('app/public/'.$data['file'])
                        );
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTractors::route('/'),
            'create' => Pages\CreateTractor::route('/create'),
            'edit' => Pages\EditTractor::route('/{record}/edit'),
        ];
    }
}
