<?php

use Livewire\Livewire;
use Domain\Form\Models\Form;
use App\Form\Livewire\ContactForm;
use Illuminate\Support\Facades\Notification;
use App\Form\Notifications\ContactMessageNotification;

test('contact-form-renders', function () {
    Livewire::test(ContactForm::class)
        ->assertOk()
        ->assertSee(__('Form::contact.fields.name.label'))
        ->assertSee(__('Form::contact.fields.email.label'))
        ->assertSee(__('Form::contact.fields.message.label'));
});

test('contact-form-validation', function () {
    Livewire::test(ContactForm::class)
        ->call('send')
        ->assertHasErrors(['name', 'email', 'message']);

    Livewire::test(ContactForm::class)
        ->set('name', 'Jo')
        ->set('email', 'john.doe')
        ->set('message', 'Hello')
        ->call('send')
        ->assertHasErrors([
            'name' => 'min',
            'email' => 'email',
            'message' => 'min',
        ]);
});

test('contact-form-sends', function () {
    // Run Form Seeder
    $this->artisan('db:seed', ['--class' => 'FormSeeder']);

    // Get the form
    $form = Form::whereSlug('contact')->first();

    $data = [
        'name' => 'John Doe',
        'email' => 'john.doe@mail.com',
        'message' => 'Hello, this is a test message.',
    ];

    Notification::fake();

    Livewire::test(ContactForm::class)
        ->set('name', $data['name'])
        ->set('email', $data['email'])
        ->set('message', $data['message'])
        ->call('send');

    // Assert the form data was created in the database.
    $this->assertDatabaseHas('form_data', [
        'form_id' => $form->id,
        'data' => json_encode($data),
    ]);

    // Assert that the email was sent to the admin contact.
    Notification::assertSentOnDemand(ContactMessageNotification::class, function ($notification, $channels, $notifiable) {
        return $notifiable->routes['mail'] === config('mail.contact.to');
    });
});
