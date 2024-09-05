<?php
    use Filament\Forms\Components\TextInput;
    use Livewire\Volt\Component;
    use function Laravel\Folio\{middleware, name};
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Forms\Form;
    use Filament\Notifications\Notification;
    use Filament\Tables;
    use Filament\Tables\Table;
    use Filament\Tables\Actions\Action;
    use Filament\Tables\Columns\TextColumn;
    use Filament\Tables\Actions\DeleteAction;
    use Filament\Tables\Actions\EditAction;
    use Filament\Tables\Actions\ViewAction;

    use Illuminate\Support\Str;
    use Wave\ApiKey;
    
    middleware('auth');
    name('settings.api');

	new class extends Component implements HasForms, Tables\Contracts\HasTable
	{
        use InteractsWithForms, Tables\Concerns\InteractsWithTable;
        
        // variables for (b)rowing keys
        public $keys = [];
        
        public ?array $data = [];

        public function mount(): void
        {
            $this->form->fill();
            $this->refreshKeys();
        }

        public function form(Form $form): Form
        {
            return $form
                ->schema([
                    TextInput::make('key')
                        ->label('Create a new API Key')
                        ->required()
                ])
                ->statePath('data');
        }

        public function add(){

            $state = $this->form->getState();
            $this->validate();

            $apiKey = auth()->user()->createApiKey(Str::slug($state['key']));

            Notification::make()
                ->title('Successfully created new API Key')
                ->success()
                ->send();

            $this->form->fill();

            $this->refreshKeys();
        }

        public function table(Table $table): Table
        {
            return $table->query(Wave\ApiKey::query()->where('user_id', auth()->user()->id))
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
                            TextInput::make('key')
                            // ...
                        ]),
                    EditAction::make()
                        ->slideOver()
                        ->modalWidth('md')
                        ->form([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            // ...
                        ]),
                    DeleteAction::make(),
            ]);
        }

        public function refreshKeys(){
            $this->keys = auth()->user()->apiKeys;
        }


	}

?>

<x-layouts.app>
    @volt('settings.api') 
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
    @endvolt
</x-layouts.app>
