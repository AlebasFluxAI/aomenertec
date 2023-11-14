<?php

namespace App\Mail\User;

use App\Models\V1\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCratedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $user;
    private $subdomain;

    public function __construct(User $user, $subdomain)
    {
        $this->subdomain = $subdomain;
        $this->user = $user;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.v1.user_created', [
            "data" => $this->user,
            "subdomain" => $this->subdomain
        ])->subject("Bienvenido a Enertec")
            ->to($this->user->email);
    }
}
