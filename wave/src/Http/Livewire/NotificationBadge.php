<?php

namespace Wave\Http\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class NotificationBadge extends Component
{
    #[On('notifications-updated')]
    public function refreshCount(): void {}

    public function render()
    {
        return view('wave::livewire.notification-badge', ['count' => auth()->user()?->unreadNotifications()->count() ?? 0]);
    }
}
