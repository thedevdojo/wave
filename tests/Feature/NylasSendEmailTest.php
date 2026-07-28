<?php

use App\Services\NylasService;
use Illuminate\Support\Facades\Http;

it('successfully sends email via nylas api', function () {
    Http::fake([
        'https://api.us.nylas.com/v3/grants/mock-grant-id/messages/send' => Http::response([
            'request_id' => 'mock-req-12345',
            'data' => [
                'id' => 'mock-message-id-999',
                'subject' => 'Test Email Subject',
                'body' => 'Test Email Body',
                'to' => [
                    ['email' => 'recipient@example.com']
                ]
            ]
        ], 200)
    ]);

    $service = new NylasService();
    $response = $service->sendMessage('mock-grant-id', [
        'subject' => 'Test Email Subject',
        'body' => 'Test Email Body',
        'to' => [
            ['email' => 'recipient@example.com']
        ]
    ]);

    expect($response)->not->toBeNull();
    expect($response['data']['id'])->toBe('mock-message-id-999');
    expect($response['data']['subject'])->toBe('Test Email Subject');
});

it('handles send message failure gracefully', function () {
    Http::fake([
        'https://api.us.nylas.com/v3/grants/mock-grant-id/messages/send' => Http::response([
            'error' => 'provider_error',
            'message' => 'The provider rejected the message.'
        ], 400)
    ]);

    $service = new NylasService();
    $response = $service->sendMessage('mock-grant-id', [
        'subject' => 'Test Email Subject',
        'body' => 'Test Email Body',
        'to' => [
            ['email' => 'recipient@example.com']
        ]
    ]);

    expect($response)->toBeNull();
});
