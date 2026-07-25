<?php

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Livewire\Attributes\Layout;
use Livewire\Component;

new
#[Layout('theme::components.layouts.app')]
#[Middleware(Authenticate::class)]
class extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $links = auth()->user()->social_links ?? $this->getDefaultLinks();
        $this->form->fill($links);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('twitter')
                    ->label('Twitter / X')
                    ->placeholder('https://twitter.com/username')
                    ->url()
                    ->helperText('Your Twitter/X profile URL')
                    ->prefixIcon('heroicon-o-link'),
                TextInput::make('linkedin')
                    ->label('LinkedIn')
                    ->placeholder('https://linkedin.com/in/username')
                    ->url()
                    ->helperText('Your LinkedIn profile URL')
                    ->prefixIcon('heroicon-o-link'),
                TextInput::make('github')
                    ->label('GitHub')
                    ->placeholder('https://github.com/username')
                    ->url()
                    ->helperText('Your GitHub profile URL')
                    ->prefixIcon('heroicon-o-link'),
                TextInput::make('website')
                    ->label('Personal Website')
                    ->placeholder('https://yourwebsite.com')
                    ->url()
                    ->helperText('Your personal website or portfolio')
                    ->prefixIcon('heroicon-o-link'),
                TextInput::make('youtube')
                    ->label('YouTube')
                    ->placeholder('https://youtube.com/@username')
                    ->url()
                    ->helperText('Your YouTube channel URL')
                    ->prefixIcon('heroicon-o-link'),
                TextInput::make('instagram')
                    ->label('Instagram')
                    ->placeholder('https://instagram.com/username')
                    ->url()
                    ->helperText('Your Instagram profile URL')
                    ->prefixIcon('heroicon-o-link'),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $this->validate();

        $state = array_filter($state, fn ($value) => ! empty($value));

        auth()->user()->forceFill([
            'social_links' => $state,
        ])->save();

        Notification::make()
            ->title('Successfully saved social media links')
            ->success()
            ->send();
    }

    private function getDefaultLinks(): array
    {
        return [
            'twitter' => '',
            'linkedin' => '',
            'github' => '',
            'website' => '',
            'youtube' => '',
            'instagram' => '',
        ];
    }
}

?>

<div>
    <div class="relative">
        <x-app.settings-layout
            title="Social Media"
            description="Connect your social media accounts and personal website."
        >
            <form wire:submit="save" class="w-full max-w-lg space-y-6">
                {{ $this->form }}
                <div class="w-full pt-6 text-right">
                    <x-button type="submit">Save Links</x-button>
                </div>
            </form>
        </x-app.settings-layout>
    </div>
</div>
