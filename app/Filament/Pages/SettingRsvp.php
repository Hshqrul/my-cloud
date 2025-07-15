<?php

namespace App\Filament\Pages;

use Exception;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Livewire\WithFileUploads;

class SettingRsvp extends Page implements HasForms
{
    use InteractsWithForms;
    
    use WithFileUploads;

    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-c-cog';

    protected static string $view = 'filament.pages.setting-rsvp';

    protected static ?int $navigationSort = 1;

    protected function getHeaderActions(): array
    {
        return [
            // ...
        ];
    }

    public function mount(): void
    {
        $this->form->fill(auth()->user()->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->model(auth()->user())
            ->schema([
                Grid::make(2)->schema([
                    SpatieMediaLibraryFileUpload::make('logo_light')
                        ->label('Logo Image')
                        ->collection('logo_light')
                        ->imageEditor(),
                    SpatieMediaLibraryFileUpload::make('logo_dark')
                        ->label('Logo Dark Mode')
                        ->collection('logo_dark')
                        ->imageEditor(),
                ])
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Save')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            $user = auth()->user();

            if (!empty($data['logo_light'])) {
                $user->clearMediaCollection('logo_light');
                $user->addMedia($data['logo_light'])->toMediaCollection('logo_light');
            }

            if (!empty($data['logo_dark'])) {
                $user->clearMediaCollection('logo_dark');
                $user->addMedia($data['logo_dark'])->toMediaCollection('logo_dark');
            }

            Notification::make()
                ->success()
                ->title('Saved successfully')
                ->send();
        } catch (Exception $e) {
            Notification::make()
                ->danger()
                ->title('Save failed')
                ->body($e->getMessage())
                ->send();
        }
    }
}
