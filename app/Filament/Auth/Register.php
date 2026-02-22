<?php

namespace App\Filament\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\RenderHook;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Register extends BaseRegister
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getTermsFormComponent(),
            ]);
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label('Name*')
            ->placeholder('Enter Full Name')
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Contact Number / Email*')
            ->placeholder('Enter Number / Email')
            ->email()
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel());
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Password*')
            ->placeholder('Enter Password')
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->dehydrateStateUsing(fn ($state) => bcrypt($state))
            ->same('passwordConfirmation');
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('passwordConfirmation')
            ->label('Confirm Password*')
            ->placeholder('Re-enter Password')
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->dehydrated(false);
    }

    protected function getTermsFormComponent(): Component
    {
        return Checkbox::make('acceptTerms')
            ->label(new HtmlString('By hitting the Register button, you agree to the <a href="#" class="fi-link fi-color-primary">Terms &amp; Conditions</a> &amp; <a href="#" class="fi-link fi-color-primary">Privacy Policy</a>'))
            ->accepted()
            ->dehydrated(false);
    }

    public function loginAction(): Action
    {
        return Action::make('login')
            ->link()
            ->label('Login')
            ->url(filament()->getLoginUrl());
    }

    public function getRegisterFormAction(): Action
    {
        return Action::make('register')
            ->label('SIGN UP')
            ->submit('register');
    }

    public function getTitle(): string | Htmlable
    {
        return 'Register';
    }

    public function getHeading(): string | Htmlable
    {
        return 'Register';
    }

    public function getSubheading(): string | Htmlable | null
    {
        if (! filament()->hasLogin()) {
            return null;
        }

        return new HtmlString('Already have an account? ' . $this->loginAction->toHtml());
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                View::make('filament.auth.partials.switch-tabs')->extraAttributes(['class' => 'mb-6']),
                RenderHook::make(PanelsRenderHook::AUTH_REGISTER_FORM_BEFORE),
                $this->getFormContentComponent(),
                RenderHook::make(PanelsRenderHook::AUTH_REGISTER_FORM_AFTER),
                View::make('filament.auth.partials.social-divider'),
            ]);
    }
}
