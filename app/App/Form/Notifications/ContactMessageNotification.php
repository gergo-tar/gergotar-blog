<?php

namespace App\Form\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactMessageNotification extends Notification
{
    use Queueable;

    protected string $subject;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected string $email,
        protected string $name,
        protected string $message
    ) {
        $this->subject = __('Form::contact.mail.subject');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(): MailMessage
    {
        return (new MailMessage())
            ->subject($this->subject)
            ->replyTo($this->email, $this->name)
            ->line(__('Form::contact.mail.message', ['name' => $this->name]))
            ->line($this->name)
            ->line($this->email)
            ->line($this->message);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $mail = $this->toMail();

        return [
            'email' => $this->email,
            'message' => $this->message,
            'email_subject' => $this->subject,
            'email_body' => $mail->render()->toHtml(),
        ];
    }
}
