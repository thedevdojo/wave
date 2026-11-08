<?php

use Illuminate\Support\Facades\Mail;
use Mailtrap\Bridge\Transport\MailtrapSdkTransport;

test('mailtrap sdk mailer is configured', function () {
    expect(config('mail.mailers.mailtrap-sdk'))->toBe(['transport' => 'mailtrap-sdk']);
    expect(config('services.mailtrap-sdk'))->toHaveKeys(['host', 'apiKey', 'inboxId']);
});

test('default mailers are still available', function () {
    expect(config('mail.mailers'))->toHaveKeys(['smtp', 'log', 'array', 'mailgun', 'mailtrap-sdk']);
});

test('mailtrap sdk mailer sends through the email api', function () {
    config(['services.mailtrap-sdk' => [
        'host' => 'send.api.mailtrap.io',
        'apiKey' => 'test-api-key',
        'inboxId' => null,
    ]]);

    $transport = Mail::mailer('mailtrap-sdk')->getSymfonyTransport();

    expect($transport)->toBeInstanceOf(MailtrapSdkTransport::class);
    expect((string) $transport)->toBe('mailtrap+sdk://send.api.mailtrap.io');
});

test('mailtrap sdk mailer sends to an email sandbox', function () {
    config(['services.mailtrap-sdk' => [
        'host' => 'sandbox.api.mailtrap.io',
        'apiKey' => 'test-api-key',
        'inboxId' => '123',
    ]]);

    $transport = Mail::mailer('mailtrap-sdk')->getSymfonyTransport();

    expect((string) $transport)->toBe('mailtrap+sdk://sandbox.api.mailtrap.io?inboxId=123');
});
