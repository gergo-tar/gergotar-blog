<?php

use Livewire\Livewire;
use Domain\Newsletter\Models\Subscriber;
use App\Newsletter\Livewire\SubscribeForm;

test('subscribe-form-renders', function () {
    Livewire::test(SubscribeForm::class)
        ->assertOk()
        ->assertSee(__('Newsletter::newsletter.subscribe.placeholder.name'))
        ->assertSee(__('Newsletter::newsletter.subscribe.placeholder.email'));
});

test('subscribe-form-validation', function () {
    Livewire::test(SubscribeForm::class)
        ->call('subscribe')
        ->assertHasErrors(['name', 'email']);

    Livewire::test(SubscribeForm::class)
        ->set('name', 'Jo')
        ->set('email', 'john.doe')
        ->call('subscribe')
        ->assertHasErrors([
            'name' => 'min',
            'email' => 'email',
        ]);
});

test('subscribe-form-validation-unique-email', function () {
    $subscriber = Subscriber::factory()->create();

    Livewire::test(SubscribeForm::class)
        ->set('name', $subscriber->name)
        ->set('email', $subscriber->email)
        ->call('subscribe')
        ->assertHasErrors([
            'email' => 'unique',
        ]);
});

test('subscribe-form-subscribe', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'john.doe@mail.com',
    ];

    Livewire::test(SubscribeForm::class)
        ->set('name', $data['name'])
        ->set('email', $data['email'])
        ->call('subscribe');

    // Assert the subscriber was created in the database.
    $this->assertDatabaseHas('subscribers', $data);
});
