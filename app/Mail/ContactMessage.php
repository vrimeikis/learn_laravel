<?php
/**
 * @copyright C VR Solutions 2018
 *
 * This software is the property of VR Solutions
 * and is protected by copyright law – it is NOT freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact VR Solutions:
 * E-mail: vytautas.rimeikis@gmail.com
 * http://www.vrwebdeveloper.lt
 */

declare(strict_types = 1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var string
     */
    private $fullName;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $message;

    /**
     * Create a new message instance.
     *
     * @param string $fullName
     * @param string $email
     * @param string $message
     */
    public function __construct(string $fullName, string $email, string $message)
    {
        $this->fullName = $fullName;
        $this->email = $email;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): ContactMessage
    {
        return $this
            ->to('vytautas.rimeikis@gmail.com')
            ->from($this->email, $this->fullName)
            ->subject('New contact request')
            ->view('emails.contact_message', [
                'name' => $this->fullName,
                'email' => $this->email,
                'content' => $this->message,
            ]);
    }
}
