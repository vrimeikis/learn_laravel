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

namespace Tests\Feature\Front;

use App\Mail\ContactMessage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Class ContactControllerTest
 * @package Tests\Feature\Front
 */
class ContactControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_get_response_ok(): void
    {
        $response = $this->get(route('contacts'));

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function it_should_send_email(): void
    {
        Mail::fake();

        $response = $this->post(route('contacts'), [
            'full_name' => 'some name',
            'email' => 'mail@mail.com',
            'message' => 'some text',
        ]);

        Mail::assertSent(ContactMessage::class);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors();
    }

    /**
     * @test
     */
    public function it_should_not_send_email_with_bad_mail(): void
    {
        Mail::fake();

        $response = $this->post(route('contacts'), [
            'full_name' => 'some name',
            'email' => 'mail',
            'message' => 'some text',
        ]);

        Mail::assertNotSent(ContactMessage::class);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasErrors(['email']);
    }
}
