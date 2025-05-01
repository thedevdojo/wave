<?php
    use App\Models\Project;
    use Filament\Forms\{Form, Concerns\InteractsWithForms, Contracts\HasForms};
    use Filament\Forms\Components\{TextArea, TextInput, DatePicker};
    use Filament\Notifications\Notification;
    use Filament\Tables;
    use Filament\Tables\{Table, Concerns\InteractsWithTable, Contracts\HasTable, Actions\Action, Actions\CreateAction, Actions\DeleteAction, Actions\EditAction, Actions\ViewAction, Columns\TextColumn};
    use Livewire\Volt\Component;
    use function Laravel\Folio\{middleware, name};

    middleware('auth');
    name('projects');

    new class extends Component implements HasForms, Tables\Contracts\HasTable
    {
        use InteractsWithForms, InteractsWithTable;

        public ?array $data = [];

        public function table(Table $table): Table
        {
            return $table
                ->query(Project::query()->where('user_id', auth()->id()))
                ->columns([
                    TextColumn::make('name')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('description')
                        ->limit(50)
                        ->searchable(),
                    TextColumn::make('start_date')
                        ->date()
                        ->sortable(),
                    TextColumn::make('end_date')
                        ->date()
                        ->sortable(),
                    TextColumn::make('created_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                ])
                ->defaultSort('created_at', 'desc')
                ->actions([
                    ViewAction::make()
                        ->slideOver()
                        ->modalWidth('md')
                        ->form([
                            TextInput::make('name')
                                ->disabled(),
                            Textarea::make('description')
                                ->disabled(),
                            DatePicker::make('start_date')
                                ->disabled(),
                            DatePicker::make('end_date')
                                ->disabled(),
                        ]),
                    EditAction::make()
                        ->slideOver()
                        ->modalWidth('md')
                        ->form([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            Textarea::make('description')
                                ->maxLength(1000),
                            DatePicker::make('start_date'),
                            DatePicker::make('end_date')
                                ->after('start_date'),
                        ]),
                    DeleteAction::make()
                        ->after(function () {
                            Notification::make()
                                ->success()
                                ->title('Project deleted')
                                ->send();
                        })
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        return $data;
                    })
                    ->after(function () {
                        Notification::make()
                            ->success()
                            ->title('Project created')
                            ->send();
                    }),
                ])
                ->filters([
                    // Add any filters you want here
                ])
                ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                        Tables\Actions\DeleteBulkAction::make(),
                    ]),
                ]);
        }

        public function form(Form $form): Form
        {
            return $form
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Textarea::make('description')
                        ->maxLength(1000),
                    DatePicker::make('start_date'),
                    DatePicker::make('end_date')
                        ->after('start_date'),
                ])
                ->statePath('data');
        }

        public function create(): void
        {
            $data = $this->form->getState();
            $project = auth()->user()->projects()->create($data);
            $this->form->fill();
            $this->dispatch('close-modal', id: 'create-project');

            Notification::make()
                ->success()
                ->title('Project created successfully')
                ->send();
        }
    }
?>

<x-layouts.app>
    @volt('projects')
        <x-app.container class="max-w-5xl">
            <div class="flex items-center justify-between mb-5">
                <x-app.heading title="Projects" description="Check out your projects below" :border="false"/>
                <x-modal id="create-project" width="md" :slide-over="true">
                    <x-slot name="trigger">
                        <x-button>New Project</x-button>
                    </x-slot>
                    <x-slot name="header">
                        <h2 class="text-lg font-medium">Create Project</h2>
                    </x-slot>
                    <form wire:submit="create" class="space-y-6">
                        {{ $this->form }}
                        <div class="flex justify-end mt-6">
                            <x-button type="submit" wire:target="create">
                                Create Project
                            </x-button>
                        </div>
                    </form>
                </x-modal>
            </div>
            <div class="overflow-x-auto border rounded-lg">
                {{ $this->table }}
            </div>
        </x-app.container>
    @endvolt
</x-layouts.app>
