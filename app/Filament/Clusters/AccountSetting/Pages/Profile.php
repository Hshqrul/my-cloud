<?php

namespace App\Filament\Clusters\AccountSetting\Pages;

use Exception;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Termwind\Enums\Color;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Forms\Components\ViewField;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Split;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Filament\Clusters\AccountSetting;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Contracts\Auth\Authenticatable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class Profile extends Page implements HasForms
{
    use HasPageShield;
    use InteractsWithForms, InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-c-user-circle';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.clusters.account-setting.pages.profile';

    protected static ?string $cluster = AccountSetting::class;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Start;

    public ?array $data = [];

    protected string $userModel;

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

    /**
     * Defines the form schema of the page.
     *
     * @param  \Filament\Forms\Form  $form
     * @return \Filament\Forms\Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General')
                    ->description('General settings related to your profile.')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('media')
                            ->label('Profile Photo')
                            // ->avatar()
                            ->imageEditor()
                            ->collection('avatars'),
                        Grid::make()->schema([
                            TextInput::make('username')
                                ->label(__('Username'))
                                ->required()
                                ->validationMessages([
                                    'unique' => 'The :attribute has already been registered. Please choose another one.',
                                ])
                                ->unique($this->getUserModel()),
                            TextInput::make('email')
                                ->label('Email Address')
                                ->email()
                                ->validationMessages([
                                    'unique' => 'The :attribute has already been registered.',
                                ])
                                ->unique($this->getUserModel()),
                            Split::make([
                                TextInput::make('name')
                                    ->label(__('Name'))
                            ])->columnSpanFull()
                        ])
                    ]),
                Section::make('Personal Information')
                    ->description('Personal information related to your profile.')
                    ->relationship('profile')
                    ->columns(2)
                    ->schema([
                        TextInput::make('tel_no')
                            ->label('Phone Number')
                            ->tel()
                            ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
                        TextInput::make('address'),
                        TextInput::make('city'),
                        TextInput::make('postcode')
                            ->numeric(),
                        TextInput::make('state'),
                        TextInput::make('country'),
                    ]),

                Section::make(__('Danger'))
                    ->description(__('Destructive settings that cannot be undone.'))
                    ->schema([
                        Placeholder::make('DeleteAccount')
                            ->content(__('Once you delete your account, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.')),

                        Actions::make([
                            Actions\Action::make('deleteAccount')
                                ->label(__('Delete Account'))
                                ->icon('heroicon-m-trash')
                                ->color('danger')
                                ->requiresConfirmation()
                                ->modalHeading(__('Delete Account'))
                                ->modalDescription(__('Are you sure you want to delete your account?'))
                                ->modalSubmitActionLabel(__('Yes, delete account'))
                                ->form([
                                    TextInput::make('password')
                                        ->password()
                                        ->revealable()
                                        ->label(__('Password'))
                                        ->required(),
                                ])
                                ->action(function (array $data) {

                                    if (! Hash::check($data['password'], Auth::user()->password)) {
                                        $this->sendErrorDeleteAccount('Password does not match.');

                                        return;
                                    }

                                    auth()->user()?->delete();
                                }),
                        ]),
                    ]),
            ])
            ->statePath('data')
            ->model(auth()->user());
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('Update')
                // ->color('primary')
                ->submit('Update'),
        ];
    }

    public function update()
    {
        try {
            auth()->user()->update(
                $this->form->getState()
            );

            Notification::make()
                ->title('Profile updated!')
                ->success()
                ->send();
        } catch (\Throwable $th) {
            Notification::make()
                ->title('Failed to update.')
                ->danger()
                ->send();
        }
    }

    protected function getUserModel(): string
    {
        if (isset($this->userModel)) {
            return $this->userModel;
        }

        /** @var SessionGuard $authGuard */
        $authGuard = Filament::auth();

        /** @var EloquentUserProvider $provider */
        $provider = $authGuard->getProvider();

        return $this->userModel = $provider->getModel();
    }

    public function sendErrorDeleteAccount(string $message): void
    {
        Notification::make()
            ->danger()
            ->title('Error')
            ->body($message)
            ->send();
    }
    
}
