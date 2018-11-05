<?php
/**
 * Created by PhpStorm.
 * User: iamzozo
 * Date: 2017. 06. 08.
 * Time: 9:20
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class CustomFormRequest extends FormRequest
{
    public function response(array $errors)
    {
        if ($this->expectsJson()) {
            return new JsonResponse([
                'error' => array_flatten($errors),
            ], 422);
        }
    }
}