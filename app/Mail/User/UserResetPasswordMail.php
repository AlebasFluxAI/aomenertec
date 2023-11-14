<?php

namespace App\Mail\User;

use App\Http\Resources\V1\Icon;
use App\Models\V1\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserResetPasswordMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $user;
    private $otp;

    public function __construct(User $user, $otp)
    {
        $this->user = $user;
        $this->otp = $otp;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.v1.user_reset_password', [
            "user" => $this->user,
            "url_recover" => route("password.reset.reset", ["otp" => $this->otp->otp]),
            "logo_url" => Icon::getUserIconUser($this->user)
        ])->subject("Recuperar contraseña")
            ->to($this->user->email);
    }
}
