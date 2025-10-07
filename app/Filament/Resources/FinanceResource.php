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
use Illuminate\Support\Facades\Auth;

class FinanceResource extends Resource
{
    protected static ?string $model = Finance::class;

    protected static ?string $navigationLabel = 'Lists';

    protected static ?string $navigationIcon = 'heroicon-s-wallet';

    protected static ?string $navigationGroup = 'Finance Menu';

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
            ->defaultSort('amount', 'desc')
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
        return parent::getEloquentQuery()->where('user_id', Auth::user()->id);
    }

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();
        if (!$user) return null;

        $salary = $user->profile?->salary ?? 0;
        $monthlyExpenses = self::getEloquentQuery()
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('amount');

        if ($salary == 0) return 'No Data';

        $savingsRate = ($salary - $monthlyExpenses) / $salary * 100;

        if ($savingsRate > 30) return 'Excellent';
        if ($savingsRate > 20) return 'Good';
        if ($savingsRate > 10) return 'Fair';
        if ($savingsRate > 0) return 'Poor';

        return 'Danger';
    }

    public static function getNavigationBadgeColor(): ?string
    {
        // $user = Auth::user();
        // if (!$user) return null;

        // $salary = $user->profile?->salary ?? 0;
        // $monthlyExpenses = self::getEloquentQuery()
        //     ->where('created_at', '>=', now()->startOfMonth())
        //     ->sum('amount');

        // if ($salary == 0) return 'gray';

        // $savingsRate = ($salary - $monthlyExpenses) / $salary * 100;

        // if ($savingsRate < 100 && $savingsRate > 75) return 'success';    // Excellent - Green
        // if ($savingsRate < 75 && $savingsRate > 50) return 'info';    // Good - Green
        // if ($savingsRate < 50 && $savingsRate > 25) return 'warning';    // Fair - Yellow
        // if ($savingsRate < 25 && $savingsRate > 10) return 'danger';     // Poor - Yellow

        return 'info';
    }
}
