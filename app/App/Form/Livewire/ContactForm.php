<?php

namespace App\Form\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Domain\Form\Actions\SubmitContactForm;

class ContactForm extends Component
{
    #[Validate('required|string|min:3|max:80')]
    public $name = '';

    #[Validate('required|email')]
    public $email = '';

    #[Validate('required|string|min:10|max:1000')]
    public $message = '';

    public function send()
    {
        $this->validate();

        SubmitContactForm::run($this->email, $this->name, $this->message);

        session()->flash('status', __('Form::contact.message.success'));

        $this->reset();
    }

    public function reload()
    {
        $this->reset();
        session()->remove('status');
    }

    public function render()
    {
        return view('Form::livewire.contact-form');
    }
}
