<?php

namespace Domain\Newsletter\Actions;

use Domain\Newsletter\Models\Subscriber;
use Lorisleiva\Actions\Concerns\AsAction;

class NewsletterSubscribe
{
    use AsAction;

    /**
     * Subscribe to the newsletter.
     */
    public function handle(string $email, ?string $name = null, ?string $preferredLanguage = null): ?Subscriber
    {
        // Check if not already subscribed.
        if ($this->isSubscribed($email)) {
            return null;
        }

        return Subscriber::create([
            'email' => $email,
            'name' => $name,
            'preferred_language' => $preferredLanguage ?? config('app.locale'),
        ]);
    }

    /**
     * Check if the email is already subscribed.
     */
    private function isSubscribed(string $email): bool
    {
        return Subscriber::where('email', $email)->exists();
    }
}
