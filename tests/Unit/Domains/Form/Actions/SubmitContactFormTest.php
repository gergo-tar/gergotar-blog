<?php

use Domain\Form\Models\Form;
use Domain\Form\Actions\SubmitContactForm;
use Illuminate\Support\Facades\Notification;
use App\Form\Notifications\ContactMessageNotification;

test('submit-contact-form', function () {
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

    $formData = SubmitContactForm::run($data['email'], $data['name'], $data['message']);

    // Assert the form data was created in the database.
    $this->assertDatabaseHas('form_data', [
        'form_id' => $form->id,
        'data' => json_encode($data),
    ]);
    expect($formData->form_id)->toBe($form->id);
    expect($formData->data)->toBe($data);

    // Assert that the email was sent to the admin contact.
    Notification::assertSentOnDemand(ContactMessageNotification::class, function ($notification, $channels, $notifiable) {
        return $notifiable->routes['mail'] === config('mail.contact.to');
    });
});
