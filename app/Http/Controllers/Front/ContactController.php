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

namespace App\Http\Controllers\Front;

use App\Http\Requests\ContactMailRequest;
use App\Mail\ContactMessage;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

/**
 * Class ContactController
 * @package App\Http\Controllers\Front
 */
class ContactController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('front.contacts');
    }

    /**
     * @param ContactMailRequest $request
     * @return RedirectResponse
     */
    public function sendMessage(ContactMailRequest $request): RedirectResponse
    {
        try {
            Mail::send(new ContactMessage(
                $request->getFullName(),
                $request->getEmail(),
                $request->getMessage()
            ));
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->with('error', $exception->getMessage())
                ->withInput();
        }

        return redirect()->route('contacts')->with('status', 'Message send!');
    }
}
