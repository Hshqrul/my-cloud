<?php

namespace App\Filament\Resources;

use App\Enum\FinanceEnum;
use Filament\Forms;
use Filament\Tables;
use App\Models\Finance;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Count;
use App\Filament\Resources\FinanceResource\Pages;
use Filament\Tables\Actions\HeaderActionsPosition;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FinanceResource\RelationManagers;
use App\Filament\Resources\FinanceResource\Widgets\FinanceStatsOverview;

class FinanceResource extends Resource
{
    protected static ?string $model = Finance::class;

    protected static ?string $navigationLabel = 'List';

    protected static ?string $navigationIcon = 'heroicon-s-wallet';

    protected static ?string $navigationGroup = 'Finance';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Details')
                    ->description('Please fill in the form below.')
                    ->compact()
                    ->schema([
                        Forms\Components\TextInput::make('item')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->prefix('RM ')
                            ->numeric(),
                        Forms\Components\Select::make('type')
                            ->required()
                            ->searchable()
                            ->native(false)
                            ->options(FinanceEnum::class),
                        Forms\Components\TextInput::make('user_id')
                            ->hidden()
                            ->maxLength(36),
                        Forms\Components\Textarea::make('remark')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create'),
            ])
            ->groups([
                'type'
            ])
            ->columns([
                Tables\Columns\TextColumn::make('item')
                    ->searchable()
                    ->weight('font-semibold')
                    ->limit(10),
                Tables\Columns\TextColumn::make('type')
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->money('RM ')
                    ->sortable()
                    ->summarize([
                        Sum::make('total_amount')->label('Total Amount')->money('RM '),
                    ]),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable(['name', 'username'])
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make()->slideOver(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])->icon('heroicon-m-ellipsis-horizontal'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            FinanceStatsOverview::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFinances::route('/'),
            'create' => Pages\CreateFinance::route('/create'),
            // 'view' => Pages\ViewFinance::route('/{record}'),
            'edit' => Pages\EditFinance::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->user()->id);
    }
}
