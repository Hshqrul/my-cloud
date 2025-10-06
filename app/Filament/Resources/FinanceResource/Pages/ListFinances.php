<?php

namespace App\Filament\Resources\FinanceResource\Pages;

use Filament\Forms;
use Filament\Actions;
use Filament\Support\Enums\MaxWidth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\FinanceResource;

class ListFinances extends ListRecords
{
    protected static string $resource = FinanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('add_salary')
                ->label('Add Salary')
                ->color('success')
                // ->icon('heroicon-s-currency-dollar')
                ->modalHeading('Add Salary')
                ->modalWidth(MaxWidth::ExtraLarge)
                ->modalSubmitActionLabel('Submit')
                ->modalCancelActionLabel('Cancel')
                ->form([
                    Forms\Components\TextInput::make('salary')
                        ->required()
                        ->prefix('RM ')
                        ->inputMode('decimal')
                        ->numeric(),
                ])
                ->action(function (array $data): void {
                    $user = auth()->user();
                    $profile = $user->profile;
                    try {
                        $profile->salary = $data['salary'];
                        $profile->save();

                        Notification::make()
                            ->success()
                            ->title('Salary added successfully')
                            ->send();
                    } catch (\Throwable $th) {
                        Notification::make()
                            ->danger()
                            ->title('Failed to add salary')
                            ->body($th->getMessage())
                            ->send();
                    }
                })
                ->requiresConfirmation()
                ->hidden(!empty(auth()->user()->profile->salary)),
            Actions\Action::make('update_salary')
                ->label('Update Salary')
                ->color('primary')
                // ->icon('heroicon-s-pencil-square')
                ->modalHeading('Update Salary')
                ->modalWidth(MaxWidth::ExtraLarge)
                ->modalSubmitActionLabel('Update')
                ->modalCancelActionLabel('Cancel')
                ->form([
                    Forms\Components\TextInput::make('salary')
                        ->required()
                        ->prefix('RM ')
                        ->inputMode('decimal')
                        ->numeric()
                        ->default(fn() => auth()->user()?->profile?->salary),
                ])
                ->action(function (array $data): void {
                    $user = auth()->user();
                    $profile = $user->profile;
                    try {
                        $profile->salary = $data['salary'];
                        $profile->save();

                        Notification::make()
                            ->success()
                            ->title('Salary updated successfully')
                            ->send();
                    } catch (\Throwable $th) {
                        Notification::make()
                            ->danger()
                            ->title('Failed to update salary')
                            ->body($th->getMessage())
                            ->send();
                    }
                })
                ->visible(fn() => !empty(auth()->user()?->profile?->salary)),
        ];
    }

    public function getHeaderWidgets(): array
    {
        return [
            FinanceResource\Widgets\FinanceStatsOverview::class,
        ];
    }
}
