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

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ContactMailRequest
 * @package App\Http\Requests
 */
class ContactMailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|min:2|max:100',
            'email' => 'required|email',
            'message' => 'required',
        ];
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return (string)$this->input('full_name');
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return (string)$this->input('email');
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return (string)$this->input('message');
    }
}
