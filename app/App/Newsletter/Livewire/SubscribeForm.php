<?php

namespace App\Newsletter\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Domain\Newsletter\Actions\NewsletterSubscribe;

class SubscribeForm extends Component
{
    #[Validate('required|string|min:3|max:80')]
    public $name = '';

    #[Validate('required|email|unique:subscribers,email')]
    public $email = '';

    /**
     * Success message
     *
     * @var string
     */
    public $message = '';

    /**
     * Validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.unique' => __('Newsletter::newsletter.subscribe.validation.email.unique')
        ];
    }

    public function subscribe()
    {
        $this->validate();

        NewsletterSubscribe::run($this->email, $this->name);

        session()->flash('status', __('Newsletter::newsletter.subscribe.message.success'));

        $this->reset();
    }

    public function render()
    {
        return view('Newsletter::livewire.subscribe-form');
    }
}
