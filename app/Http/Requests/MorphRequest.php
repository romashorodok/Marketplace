<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MorphRequest extends FormRequest
{

    public function transformTo(string $target): FormRequest
    {
        /* @var FormRequest $target */
        $request = $target::createFromBase($this);

        $request->setContainer($this->container)->setRedirector($this->redirector);

        $request->prepareForValidation();
        $request->getValidatorInstance();

        return $request;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
