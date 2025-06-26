<?php

namespace App\Helpers;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

/**
 * Trait Responses
 * @package App\Helpers
 */
trait Responses
{
    /**
     * @param $data
     * @param int $code
     *
     * @return JsonResponse
     */
    public function successResponseWithData($data, $code = Response::HTTP_OK)
    {
        $payload = [
            'status' => true,
            'data'   => $data
        ];

        return response()->json(
            $payload,
            $code,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * @param $messages
     * @param int $code
     */
    public function exceptionResponse($messages, $code = Response::HTTP_OK)
    {
        $payload = [
            'status' => false,
            'messages' => $messages
        ];

        $response = response()->json(
            $payload,
            $code,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );

        throw new HttpResponseException($response);
    }
}
