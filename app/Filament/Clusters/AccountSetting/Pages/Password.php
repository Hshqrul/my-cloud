<?php

namespace App\Filament\Clusters\AccountSetting\Pages;

use Exception;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Contracts\HasForms;
use App\Filament\Clusters\AccountSetting;
use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Contracts\Auth\Authenticatable;
use Filament\Forms\Concerns\InteractsWithForms;

class Password extends Page implements HasForms
{
    use HasPageShield;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-c-lock-closed';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.clusters.account-setting.pages.password';

    protected static ?string $cluster = AccountSetting::class;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Start;

    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $data = $this->getUser()?->attributesToArray();

        $this->form->fill($data);
    }

    public function getUser(): Authenticatable & Model
    {
        $user = Filament::auth()->user();

        if (! $user instanceof Model) {
            throw new Exception('The authenticated user object must be an Eloquent model to allow the profile page to update it.');
        }

        return $user;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Password')->icon('heroicon-s-lock-closed')
                    ->label(__('Password'))
                    ->schema([
                        TextInput::make('current_password')
                            ->label(__('filament-breezy::default.password_confirm.current_password'))
                            ->columnSpanFull()
                            ->password()
                            ->currentPassword()
                            ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                            ->dehydrated(fn(?string $state): bool => filled($state))
                            ->revealable()
                            ->required(),
                        TextInput::make('new_password')
                            ->label(__('filament-breezy::default.fields.new_password'))
                            ->columnSpanFull()
                            ->password()
                            ->rules([
                                'confirmed',
                                'required',
                                'string',
                                'min:8',             // must be at least 8 characters in length
                                'regex:/[A-Z]/',     // must contain at least one uppercase letter
                                'regex:/[a-z]/',     // must contain at least one lowercase letter
                                'regex:/[0-9]/',     // must contain at least one digit
                                'regex:/[@$!%*#?&]/', // must contain at least one special character
                            ])
                            ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                            ->revealable()
                            ->same('new_password_confirmation')
                            ->required(),
                        TextInput::make('new_password_confirmation')
                            ->label(__('filament-breezy::default.fields.new_password_confirmation'))
                            ->columnSpanFull()
                            ->password()
                            ->dehydrated(false)
                            ->revealable()
                            ->required(),
                    ])
            ])
            ->operation('edit')
            ->model($this->getUser())
            ->statePath('data');
    }

    public function submit()
    {
        $data = collect($this->form->getState())->only('new_password')->all();
        auth()->user()->update([
            'password' => $data['new_password'],
        ]);
        session()->forget('password_hash_' . Filament::getCurrentPanel()->getAuthGuard());
        Filament::auth()->login($this->getUser());
        $this->reset(['data']);
        Notification::make()
            ->success()
            ->title(__('filament-breezy::default.profile.password.notify'))
            ->send();
    }
}
