<?php

namespace App\Filament\Pages\Auth;

use App\Models\Profile;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Split;

class CustomRegister extends Register
{
    protected ?string $maxWidth = '6xl';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Contact')
                        ->icon('heroicon-o-identification')
                        ->completedIcon('heroicon-c-identification')
                        ->description('Please provide your contact details.')
                        ->schema([
                            Grid::make()->schema([
                                $this->getUsernameFormComponent(),
                                $this->getEmailFormComponent(),
                                Split::make([
                                    $this->getNameFormComponent(),
                                ])->columnSpanFull()
                            ])
                        ]),
                    Wizard\Step::make('Profile')
                        ->icon('heroicon-o-user')
                        ->completedIcon('heroicon-c-user')
                        ->description('Please provide your profile details.')
                        ->schema([
                            $this->getProfileFormComponent(),
                        ]),
                    Wizard\Step::make('Password')
                        ->icon('heroicon-o-lock-closed')
                        ->completedIcon('heroicon-c-lock-closed')
                        ->description('Please provide your password.')
                        ->schema([
                            $this->getPasswordFormComponent(),
                            $this->getPasswordConfirmationFormComponent(),
                        ]),
                ])->submitAction(new HtmlString(Blade::render(<<<BLADE
                    <x-filament::button
                        type="submit"
                        size="md"
                        wire:submit="register"
                    >
                        Register
                    </x-filament::button>
                    BLADE))),
            ]);
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getUsernameFormComponent(): Component
    {
        return TextInput::make('username')
            ->label(__('Username'))
            ->unique($this->getUserModel())
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label(__('Name'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getProfileFormComponent(): Component
    {
        return Section::make()
            ->schema([
                SpatieMediaLibraryFileUpload::make('user.avatar_url')->label('Avatar')
                    ->collection('avatars')
                    ->avatar(),
                Grid::make()
                    ->schema([
                        TextInput::make('profile.tel_no') // if want to include another table but has relation use (profile.user.username)
                            ->label('Phone Number')
                            ->tel()
                            ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
                        TextInput::make('profile.address'),
                        TextInput::make('profile.city'),
                        TextInput::make('profile.postcode')
                            ->numeric(),
                        TextInput::make('profile.state'),
                        TextInput::make('profile.country'),
                    ])
            ]);
    }

    protected function getGithubFormComponent(): Component
    {
        return TextInput::make('github')
            ->prefix('https://github.com/')
            ->label(__('GitHub'))
            ->maxLength(255);
    }

    protected function getTwitterFormComponent(): Component
    {
        return TextInput::make('twitter')
            ->prefix('https://x.com/')
            ->label(__('Twitter (X)'))
            ->maxLength(255);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRegistration(array $data): Model
    {
        $profileData = $data['profile'];
        $userData = array_diff_key($data, $profileData);

        $createdUser = $this->getUserModel()::create($userData);

        if ($createdUser) {
            $profileData['user_id'] = $createdUser->id;
            Profile::create($profileData);
        }
        
        return $createdUser;
    }
}
