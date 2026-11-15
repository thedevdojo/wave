<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Wave\ActivityLog;
use Wave\Jobs\DeliverNotificationBroadcast;
use Wave\NotificationBroadcast;

class Notifications extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'phosphor-bell-duotone';

    protected static ?int $navigationSort = 8;

    protected string $view = 'filament.pages.notifications';

    public ?array $data = [];

    #[Locked]
    public string $submissionId;

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public function mount(): void
    {
        abort_unless(static::canAccess(), 403);
        $this->submissionId = (string) Str::uuid();
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->required()->maxLength(191),
            Select::make('category')->label('Notification label')->options(NotificationBroadcast::categories())->required(),
            RichEditor::make('body')->label('Message')->required()->maxLength(50000)
                ->toolbarButtons(['bold', 'italic', 'underline', 'strike', 'link', 'h2', 'h3', 'blockquote', 'bulletList', 'orderedList', 'undo', 'redo'])
                ->columnSpanFull(),
        ])->statePath('data');
    }

    public function sendAction(): Action
    {
        return Action::make('send')->label('Send notification')->requiresConfirmation()
            ->modalHeading('Send to all users?')
            ->modalDescription('This publishes an in-app notification to all current users. It does not send email.')
            ->modalSubmitActionLabel('Send notification')
            ->action(fn () => $this->send());
    }

    public function send(): void
    {
        abort_unless(static::canAccess(), 403);
        $state = $this->form->getState();
        $body = Str::sanitizeHtml($state['body']);
        if (trim(strip_tags(html_entity_decode($body))) === '') {
            throw ValidationException::withMessages(['data.body' => 'Enter a message.']);
        }
        DB::transaction(function () use ($state, $body) {
            $broadcast = NotificationBroadcast::firstOrCreate(['id' => $this->submissionId], [
                'title' => $state['title'], 'category' => $state['category'], 'body' => $body,
            ]);
            if ($broadcast->wasRecentlyCreated) {
                DeliverNotificationBroadcast::dispatch($broadcast->id)->afterCommit();
                ActivityLog::log('notification_broadcast_queued', 'Site-wide notification queued', ['broadcast_id' => $broadcast->id, 'category' => $broadcast->category]);
            }
        });
        Notification::make()->success()->title('Notification queued for delivery')->send();
        $this->submissionId = (string) Str::uuid();
        $this->form->fill();
    }
}
