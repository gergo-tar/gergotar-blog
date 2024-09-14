<?php

namespace Domain\Form\Actions;

use Domain\Form\Models\Form;
use Domain\Form\Models\FormData;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Facades\Notification;
use App\Form\Notifications\ContactMessageNotification;

class SubmitContactForm
{
    use AsAction;

    /**
     * Submit the contact form.
     *
     * @param string $email  The user's email.
     * @param string $name  The user's name.
     * @param string $message  The message.
     */
    public function handle(string $email, string $name, string $message): ?FormData
    {
        $form = $this->getForm();

        if (! $form) {
            return null;
        }

        $data = [
            'data' => [
                'name' => $name,
                'email' => $email,
                'message' => $message,
            ],
            'language' => app()->getLocale(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        // Load the user's location data.
        $position = Location::get();

        if ($position) {
            $data = array_merge($data, [
                'country' => $position->countryName,
                'city' => $position->cityName,
                'region' => $position->regionName,
                'postal_code' => $position->postalCode,
                'latitude' => $position->latitude,
                'longitude' => $position->longitude,
                'timezone' => $position->timezone,
            ]);
        }

        // Create a new form data entry.
        $formData = $form->formData()->create($data);

        // Send the contact message notification to the admin.
        Notification::route('mail', config('mail.contact.to'))
            ->notify(
                new ContactMessageNotification(
                    $email,
                    $name,
                    $message
                )
            );

        return $formData;
    }

    /**
     * Get the Contact form object.
     */
    private function getForm(): ?Form
    {
        $slug = 'contact';
        return Cache::remember(Form::CACHE_KEY . ".{$slug}.id", config('cache.ttl'), function () use ($slug) {
            return GetActiveFormBySlug::run($slug, ['id']);
        });
    }
}
