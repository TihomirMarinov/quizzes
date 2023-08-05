<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Validation\Validator;
use Illuminate\Contracts\Support\Responsable;


trait HttpDefaultResponses {
    protected function errorUnauthorized(string $message = null)
    {
        return response()->json([
            'error' => $message ?: trans('error.unauthorized')
        ], Response::HTTP_UNAUTHORIZED);
    }

    protected function errorForbidden(string $message = null)
    {
        return response()->json([
            'message' => $message ?: trans('error.forbidden')
        ], Response::HTTP_FORBIDDEN);
    }

    protected function errorNotFound(string $message = null)
    {
        return response()->json([
            'message' => $message ?: trans('error.not_found')
        ], Response::HTTP_NOT_FOUND);
    }

    protected function errorBadRequest(Validator $validator = null, string $message = null)
    {
        return response()->json(array_filter([
            'message' => $message ?: trans('error.bad_request'),
            'errors' => $validator ? $validator->messages() : null,
        ]), Response::HTTP_BAD_REQUEST);
    }

    protected function errorInternalServer(string $message = null)
    {
        return response()->json([
            'message' => $message ?: trans('error.internal_server_error'),
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    protected function successNoContent()
    {
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    protected function successCreated(Responsable $resource = null)
    {
        return response()->json(['data' => $resource ?? ''], Response::HTTP_CREATED);
    }
}
