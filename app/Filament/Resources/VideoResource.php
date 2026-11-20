<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoResource\Pages;
use App\Models\Video;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static ?string $navigationIcon = 'heroicon-o-play-circle';

    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('embed_url')->label('YouTube/embed URL')->required()->url(),
                Forms\Components\FileUpload::make('thumbnail')->image()->directory('videos'),
                Forms\Components\Select::make('related_type')
                    ->options(['tractor' => 'Tractor', 'implement' => 'Implement', 'brand' => 'Brand'])
                    ->live(),
                Forms\Components\Select::make('related_id')
                    ->label('Related item')
                    ->options(function (callable $get) {
                        return match ($get('related_type')) {
                            'tractor' => \App\Models\Tractor::pluck('name', 'id'),
                            'implement' => \App\Models\Implement::pluck('name', 'id'),
                            'brand' => \App\Models\Brand::pluck('name', 'id'),
                            default => [],
                        };
                    })
                    ->searchable(),
                Forms\Components\DateTimePicker::make('published_at')->default(now()),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail'),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('related_type')->badge(),
                Tables\Columns\TextColumn::make('published_at')->dateTime()->sortable(),
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
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}
