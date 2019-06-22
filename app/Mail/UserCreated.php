<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    private $user;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $mailLocale;

    /**
     * @param User $user
     * @param string $password
     * @param string $locale
     */
    public function __construct(User $user, string $password, string $locale = 'en')
    {
        $this->user = $user;
        $this->password = $password;
        $this->mailLocale = $locale;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return
            $this
                ->subject(__('User created'))
                ->text('mail.' . $this->mailLocale . '.userCreated')
                ->with([
                    'user' => $this->user,
                    'password' => $this->password
                ]);
    }
}
