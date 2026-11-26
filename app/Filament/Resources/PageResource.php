<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Site Settings';

    protected static ?string $navigationLabel = 'Pages (About, Privacy, …)';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->required()->maxLength(150),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('Matches the page it controls, e.g. "about" or "privacy-policy". Changing this for an existing system page (About Us, Privacy Policy) will disconnect it from that page.'),
                Forms\Components\RichEditor::make('body')->columnSpanFull(),

                Forms\Components\Fieldset::make('SEO')
                    ->relationship('seoMeta')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')->label('Meta title')->maxLength(70)->columnSpanFull(),
                        Forms\Components\Textarea::make('meta_description')->label('Meta description')->rows(2)->maxLength(160)->columnSpanFull(),
                        Forms\Components\FileUpload::make('og_image')->label('Social share image')->image()->directory('seo')->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\TextColumn::make('updated_at')->dateTime(),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
