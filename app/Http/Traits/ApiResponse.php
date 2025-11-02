<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait ApiResponse
{
    /**
     * Returns a success response.
     */
    protected function success(
        mixed $data = null,
        ?string $message = null,
        int $status = 200
    ): JsonResponse {
        $response = [
            'success' => true,
        ];

        if ($message) {
            $response['message'] = $message;
        }

        if ($data !== null) {
            if ($data instanceof JsonResource || $data instanceof ResourceCollection) {
                return $data->additional($response)->response()->setStatusCode($status);
            }

            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }

    /**
     * Returns an error response.
     */
    protected function error(
        string $message,
        int $status = 400,
        mixed $errors = null
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }

    /**
     * Returns a success response with data.
     */
    protected function successWithData(
        mixed $data,
        ?string $message = null,
        int $status = 200
    ): JsonResponse {
        return $this->success($data, $message, $status);
    }

    /**
     * Returns a success response with message only.
     */
    protected function successWithMessage(
        string $message,
        int $status = 200
    ): JsonResponse {
        return $this->success(null, $message, $status);
    }

    /**
     * Returns a successful creation response.
     */
    protected function created(
        mixed $data = null,
        string $message = 'Resource created successfully'
    ): JsonResponse {
        return $this->success($data, $message, 201);
    }

    /**
     * Returns a successful update response.
     */
    protected function updated(
        mixed $data = null,
        string $message = 'Resource updated successfully'
    ): JsonResponse {
        return $this->success($data, $message, 200);
    }

    /**
     * Returns a successful deletion response.
     */
    protected function deleted(
        string $message = 'Resource deleted successfully'
    ): JsonResponse {
        return $this->success(null, $message, 200);
    }

    /**
     * Returns a not found response.
     */
    protected function notFound(
        string $message = 'Resource not found'
    ): JsonResponse {
        return $this->error($message, 404);
    }

    /**
     * Returns an unauthorized response.
     */
    protected function unauthorized(
        string $message = 'Unauthorized'
    ): JsonResponse {
        return $this->error($message, 401);
    }

    /**
     * Returns a forbidden response.
     */
    protected function forbidden(
        string $message = 'Forbidden'
    ): JsonResponse {
        return $this->error($message, 403);
    }

    /**
     * Returns a validation error response.
     */
    protected function validationError(
        mixed $errors,
        string $message = 'Validation error'
    ): JsonResponse {
        return $this->error($message, 422, $errors);
    }

    /**
     * Returns an internal server error response.
     */
    protected function serverError(
        string $message = 'Internal server error'
    ): JsonResponse {
        return $this->error($message, 500);
    }

    /**
     * Returns a conflict response.
     */
    protected function conflict(
        string $message = 'Conflict detected'
    ): JsonResponse {
        return $this->error($message, 409);
    }

    /**
     * Returns a no content response.
     */
    protected function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }
}
