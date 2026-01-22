<?php

namespace Wave\Contracts;

interface ActivityLoggerInterface
{
    /**
     * Log an activity for the current authenticated user.
     *
     * @param string $action The action identifier (e.g., 'password_changed', 'login')
     * @param string|null $description Human-readable description
     * @param array<string, mixed>|null $metadata Additional context data
     * @return static|null Returns the created log or null if logging is disabled/queued
     */
    public static function log(string $action, ?string $description = null, ?array $metadata = null): ?static;
}
