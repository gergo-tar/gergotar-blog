<?php

use Domain\Newsletter\Actions\NewsletterSubscribe;
use Domain\Newsletter\Models\Subscriber;

const SUB_EMAIL = 'subscribe@test.mail';
const SUB_NAME = 'Subscribe Test';

test('newsletter-subscribe', function () {
    $preferredLanguage = 'en';

    $subscriber = NewsletterSubscribe::run(SUB_EMAIL, SUB_NAME, $preferredLanguage);

    expect($subscriber)->toBeInstanceOf(Subscriber::class);
    expect($subscriber->email)->toBe(SUB_EMAIL);

    $this->assertDatabaseHas('subscribers', [
        'email' => SUB_EMAIL,
        'name' => SUB_NAME,
        'preferred_language' => $preferredLanguage,
    ]);
});

test('newsletter-subscribe-already-subscribed', function () {
    Subscriber::factory()->create([
        'email' => SUB_EMAIL,
        'name' => SUB_NAME,
    ]);

    $subscriber = NewsletterSubscribe::run(SUB_EMAIL, SUB_NAME);

    expect($subscriber)->toBeNull();
});

test('newsletter-subscribe-default-language', function () {
    $subscriber = NewsletterSubscribe::run(SUB_EMAIL, SUB_NAME);

    expect($subscriber->preferred_language)->toBe(config('app.locale'));
});
