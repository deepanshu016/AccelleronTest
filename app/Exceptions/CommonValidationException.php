<?php
namespace App\Exceptions;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class CommonValidationException extends ValidationException
{
    public function formattedResponse(): JsonResponse
    {
        return response()->json([
            'message' => 'Validation failed. Please check your input.',
            'errors' => $this->validator->errors()
        ], 422);
    }
}



?>
