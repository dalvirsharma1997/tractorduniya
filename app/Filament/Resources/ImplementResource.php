<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImplementResource\Pages;
use App\Models\Implement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ImplementResource extends Resource
{
    protected static ?string $model = Implement::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Catalog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Implement')
                    ->columnSpanFull()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Overview')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                                Forms\Components\Select::make('brand_id')->label('Brand')->relationship('brand', 'name')->searchable(),
                                Forms\Components\Select::make('category_id')
                                    ->label('Type (category)')
                                    ->relationship('category', 'name', fn ($query) => $query->where('type', 'implement_type'))
                                    ->searchable(),
                                Forms\Components\TextInput::make('type')->required()->helperText('e.g. Rotavator, Plough, Cultivator'),
                                Forms\Components\Select::make('status')
                                    ->options(['active' => 'Active', 'discontinued' => 'Discontinued'])
                                    ->default('active')
                                    ->required(),
                                Forms\Components\TextInput::make('compatible_hp_min')->label('Compatible HP (min)')->numeric(),
                                Forms\Components\TextInput::make('compatible_hp_max')->label('Compatible HP (max)')->numeric(),
                                Forms\Components\TextInput::make('price_min')->numeric()->prefix('₹'),
                                Forms\Components\TextInput::make('price_max')->numeric()->prefix('₹'),
                                Forms\Components\Textarea::make('description')->columnSpanFull(),
                                Forms\Components\RichEditor::make('footer_content')
                                    ->label('Text section (shown before the footer)')
                                    ->helperText('A longer write-up shown as its own section near the bottom of this implement\'s page, above the footer.')
                                    ->columnSpanFull(),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Specs')
                            ->schema([
                                Forms\Components\Repeater::make('specs')
                                    ->relationship('specs')
                                    ->schema([
                                        Forms\Components\TextInput::make('spec_group')->default('general')->required(),
                                        Forms\Components\TextInput::make('spec_key')->required(),
                                        Forms\Components\TextInput::make('spec_value')->required(),
                                        Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                                    ])
                                    ->columns(4)
                                    ->columnSpanFull()
                                    ->defaultItems(0),
                            ]),

                        Forms\Components\Tabs\Tab::make('Images')
                            ->schema([
                                Forms\Components\Repeater::make('images')
                                    ->relationship('images')
                                    ->schema([
                                        Forms\Components\FileUpload::make('path')->image()->directory('implements')->required(),
                                        Forms\Components\Toggle::make('is_primary'),
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
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('brand.name'),
                Tables\Columns\TextColumn::make('compatible_hp_min')->label('HP range')
                    ->formatStateUsing(fn ($record) => "{$record->compatible_hp_min}–{$record->compatible_hp_max}"),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn (string $state) => $state === 'active' ? 'success' : 'gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('brand_id')->label('Brand')->relationship('brand', 'name'),
                Tables\Filters\SelectFilter::make('type')->options(fn () => Implement::query()->distinct()->pluck('type', 'type')->toArray()),
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListImplements::route('/'),
            'create' => Pages\CreateImplement::route('/create'),
            'edit' => Pages\EditImplement::route('/{record}/edit'),
        ];
    }
}
