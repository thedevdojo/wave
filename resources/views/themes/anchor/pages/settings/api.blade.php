<?php
    use Filament\Forms\Components\TextInput;
    use Livewire\Volt\Component;
    use function Laravel\Folio\{name};
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Forms\Form;
    use Filament\Notifications\Notification;
    use Filament\Tables;
    use Filament\Tables\Table;
    use Filament\Tables\Actions\Action;

    use Illuminate\Support\Str;
    use Wave\ApiKey;
    
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

        protected function getTableQuery() 
        {
            return Wave\ApiKey::query()->where('user_id', auth()->user()->id);
        }

        protected function getTableColumns(): array
        {
            return [
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('created_at')
                                            ->label('Created')
            ];
        }

        /*public function table(Table $table): Table
        {
            return $table;
        }*/

        public function refreshKeys(){
            $this->keys = auth()->user()->apiKeys;
        }


	}

?>

<x-layouts.app>
    @volt('settings.api') 
        <div class="relative">
            <x-app.settings-layout
                title="Security"
                description="Update and change your current account password."
            >
                <div class="flex flex-col">

                    <form wire:submit="add" class="w-full max-w-lg">
                        {{ $this->form }}
                        <div class="pt-6 w-full text-right">
                            <x-button type="submit">Create New Key</x-button>
                        </div>
                    </form>
                    <hr class="my-12 border-zinc-200">
                    @if(count($keys) > 0)

                    <div>
                        {{ 
                            $this->table->actions([
                                    Filament\Tables\Actions\Action::make('edit')
                                        ->url(fn (Wave\ApiKey $record): string => route('settings.profile'))
                                        ->openUrlInNewTab(),
                                    Filament\Tables\Actions\DeleteAction::make()
                                        ->successRedirectUrl(route('settings.profile'))
                            ]) 
                        }}
</div>

                        <p class="block text-sm font-medium leading-5 text-zinc-700">Current API Keys</p>

                        <div class="overflow-hidden mt-2 border border-zinc-150 sm:rounded">
                            <table class="min-w-full divide-y divide-zinc-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider leading-4 text-left uppercase text-zinc-500 bg-zinc-100">
                                            Name
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider leading-4 text-left uppercase text-zinc-500 bg-zinc-100">
                                            Created
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider leading-4 text-left uppercase text-zinc-500 bg-zinc-100">
                                            Last Used
                                        </th>
                                        <th class="px-6 py-3 bg-zinc-100"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($keys as $apiKey)
                                        <!-- Odd row -->
                                        <tr class="@if($loop->index%2 == 0){{ 'bg-white' }}@else{{ 'bg-zinc-50' }}@endif">
                                            <td class="px-6 py-4 text-sm font-medium leading-5 text-zinc-900 whitespace-no-wrap">
                                                {{ $apiKey->name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm leading-5 text-zinc-500 whitespace-no-wrap">
                                                {{ $apiKey->created_at->format('F j, Y') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm leading-5 text-zinc-500 whitespace-no-wrap">
                                                @if(is_null($apiKey->last_used)){{ 'Never Used' }}@else{{ $apiKey->last_used }}@endif
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium leading-5 text-right whitespace-no-wrap">
                                                <button wire:click="readApiModal('{{ $apiKey->id }}')" class="mr-2 text-indigo-600 hover:underline focus:outline-none">
                                                    View
                                                </button>
                                                <button wire:click="editApiModal('{{ $apiKey->id }}')" class="mr-2 text-indigo-600 hover:underline focus:outline-none">
                                                    Edit
                                                </button>
                                                <button wire:click="deleteApiModal('{{ $apiKey->id }}')" class="text-indigo-600 hover:underline focus:outline-none">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                    @else
                        <p class="w-full text-sm text-center text-zinc-600">No API Keys Created Yet.</p>
                    @endif
                    
                </div>
            </x-app.settings-layout>
        </div>
    @endvolt
</x-layouts.app>
