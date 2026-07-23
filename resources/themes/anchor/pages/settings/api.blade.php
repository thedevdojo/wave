<?php

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Wave\ActivityLog;
use Wave\ApiKey;

new
#[Layout('theme::components.layouts.app')]
#[Middleware(Authenticate::class)]
class extends Component implements HasForms, HasActions, Tables\Contracts\HasTable
{
    use InteractsWithForms, InteractsWithActions, Tables\Concerns\InteractsWithTable;

    public $keys = [];

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
        $this->refreshKeys();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label('Create a new API Key')
                    ->required(),
            ])
            ->statePath('data');
    }

    public function add(): void
    {
        $state = $this->form->getState();
        $this->validate();

        auth()->user()->createApiKey(Str::slug($state['key']));

        ActivityLog::log('api_key_created', 'API key created: '.$state['key'], [
            'key_name' => $state['key'],
        ]);

        Notification::make()
            ->title('Successfully created new API Key')
            ->success()
            ->send();

        $this->form->fill();

        $this->refreshKeys();
    }

    public function table(Table $table): Table
    {
        return $table->query(ApiKey::query()->where('user_id', auth()->user()->id))
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('created_at')->label('Created'),
            ])
            ->actions([
                ViewAction::make()
                    ->slideOver()
                    ->modalWidth('md')
                    ->form([
                        TextInput::make('name'),
                        TextInput::make('key'),
                    ]),
                EditAction::make()
                    ->slideOver()
                    ->modalWidth('md')
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->after(function ($record) {
                        ActivityLog::log('api_key_updated', 'API key updated: '.$record->name, [
                            'key_name' => $record->name,
                        ]);
                    }),
                DeleteAction::make()
                    ->after(function ($record) {
                        ActivityLog::log('api_key_deleted', 'API key deleted: '.$record->name, [
                            'key_name' => $record->name,
                        ]);
                    }),
            ]);
    }

    public function refreshKeys(): void
    {
        $this->keys = auth()->user()->apiKeys;
    }
}

?>

<div>
    <div class="relative">
        <x-app.settings-layout
            title="API Keys"
            description="Manage your API Keys"
        >
            <div class="flex flex-col">
                <form wire:submit="add" class="w-full max-w-lg">
                    {{ $this->form }}
                    <div class="w-full pt-6 text-right">
                        <x-button type="submit">Create New Key</x-button>
                    </div>
                </form>
                <hr class="my-8 border-zinc-200">
                <x-elements.label class="block text-sm font-medium leading-5 text-zinc-700">Current API Keys</x-elements.label>
                <div class="pt-5">
                    {{ $this->table }}
                </div>
            </div>
        </x-app.settings-layout>
    </div>
</div>
