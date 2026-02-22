<?php

namespace App\Filament\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\RenderHook;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Login extends BaseLogin
{
    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Contact Number / Email*')
            ->placeholder('Enter number / Email address')
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Password*')
            ->hint(filament()->hasPasswordReset() ? new HtmlString('<a class="fi-link fi-size-sm fi-color-custom fi-color-primary" href="' . filament()->getRequestPasswordResetUrl() . '" tabindex="3">Forget Password?</a>') : null)
            ->placeholder('Enter Password')
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->autocomplete('current-password')
            ->required()
            ->extraInputAttributes(['tabindex' => 2]);
    }

    protected function getRememberFormComponent(): Component
    {
        return Checkbox::make('remember')
            ->label('Keep me logged in');
    }

    public function registerAction(): Action
    {
        return Action::make('register')
            ->link()
            ->label('Sign Up')
            ->url(filament()->getRegistrationUrl());
    }

    public function getTitle(): string | Htmlable
    {
        return 'Login';
    }

    public function getHeading(): string | Htmlable
    {
        return 'Welcome Back!';
    }

    public function getSubheading(): string | Htmlable | null
    {
        if (! filament()->hasRegistration()) {
            return null;
        }

        return new HtmlString('Still don\'t have an account? ' . $this->registerAction->toHtml());
    }

    protected function getAuthenticateFormAction(): Action
    {
        return Action::make('authenticate')
            ->label('LOGIN')
            ->submit('authenticate');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                View::make('filament.auth.partials.switch-tabs')->extraAttributes(['class' => 'mb-6']),
                RenderHook::make(PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE),
                $this->getFormContentComponent(),
                $this->getMultiFactorChallengeFormContentComponent(),
                RenderHook::make(PanelsRenderHook::AUTH_LOGIN_FORM_AFTER),
                View::make('filament.auth.partials.social-divider'),
            ]);
    }
}
