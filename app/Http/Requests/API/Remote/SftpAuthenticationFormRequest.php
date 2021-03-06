<?php

namespace Pterodactyl\Http\Requests\API\Remote;

use Pterodactyl\Http\Requests\Request;

class SftpAuthenticationFormRequest extends Request
{
    /**
     * Authenticate the request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Rules to apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|string',
            'password' => 'required|string',
        ];
    }

    /**
     * Return only the fields that we are interested in from the request.
     * This will include empty fields as a null value.
     *
     * @return array
     */
    public function normalize()
    {
        return $this->only(
            array_keys($this->rules())
        );
    }
}
