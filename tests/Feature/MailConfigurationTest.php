<?php

test('mailtrap sdk mailer is configured', function () {
    expect(config('mail.mailers.mailtrap-sdk'))->toBe(['transport' => 'mailtrap-sdk']);
    expect(config('services.mailtrap-sdk'))->toHaveKeys(['host', 'apiKey', 'inboxId']);
});

test('default mailers are still available', function () {
    expect(config('mail.mailers'))->toHaveKeys(['smtp', 'log', 'array', 'mailgun', 'mailtrap-sdk']);
});
