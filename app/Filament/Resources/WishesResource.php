<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Wishes;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Split;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\WishesResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use App\Filament\Resources\WishesResource\RelationManagers;
use App\Models\Rsvp;
use Filament\Forms\Components\Split as ComponentsSplit;
use Filament\Support\Enums\IconPosition;

class WishesResource extends Resource
{
    protected static ?string $model = Rsvp::class;

    protected static ?string $navigationIcon = 'heroicon-c-heart';

    protected static ?string $label = 'Wishes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('notes')
                    ->label('Wish')
                    ->maxLength(255)
                    ->columnSpanFull(),
                \Filament\Forms\Components\Grid::make(1)->schema([
                    ComponentsSplit::make([
                        Forms\Components\TextInput::make('name')
                            ->label('From')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('created_at')
                            ->label('Wish Date')
                            ->required()
                            ->default(now())
                            ->columnSpanFull(),
                    ])
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\Layout\Grid::make()
                    ->columns(1)
                    ->schema([
                        TextColumn::make('notes')
                            ->label('Notes')
                            ->fontFamily('italic')
                            ->size(TextColumn\TextColumnSize::Medium)
                            ->default('Not specified')
                            ->suffix('"')
                            ->prefix('"')
                            ->searchable()
                            ->columnSpanFull(),
                        \Filament\Tables\Columns\Layout\Split::make([
                            TextColumn::make('name')
                                ->label('Name')
                                ->searchable()
                                ->weight(FontWeight::SemiBold)
                                ->icon(fn(TextColumn $column): string => $column->getRecord()->attendence ? 'heroicon-c-heart' : 'heroicon-c-moon')
                                ->iconColor(fn(TextColumn $column): string => $column->getRecord()->attendence ? 'success' : 'warning')
                                ->iconPosition(IconPosition::After)
                                ->columnSpanFull(),
                            TextColumn::make('created_at')
                                ->label('Created At')
                                ->since()
                                ->columnSpanFull(),
                        ])
                            ->columnSpanFull(),
                    ]),
            ])
            ->contentGrid(['md' => 2, 'xl' => 3])
            ->groups([
                Tables\Grouping\Group::make('attendence')
                    ->label('Attendance')
                    ->getTitleFromRecordUsing(fn(Rsvp $record): string => $record->attendence ? 'Attending' : 'Not Attending')
                    ->collapsible(),
            ])
            ->defaultPaginationPageOption(25)
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hiddenLabel()
                    ->icon(''),
            ])
            ->recordClasses(fn(Rsvp $record): string => match ($record->attendence) {
                true => 'border-s-2 border-indigo-600 dark:border-indigo-300',
                false => 'border-s-2 border-amber-600 dark:border-amber-300',
            });
    }
    public static function getNavigationGroup(): ?string
    {
        return __('menu.nav_group.rsvp_setup');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageWishes::route('/'),
        ];
    }
}
