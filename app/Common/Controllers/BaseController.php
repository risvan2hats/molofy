<?php

namespace App\Common\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseController extends Controller
{
    /**
     * Return a successful JSON response.
     */
    protected function successResponse(
        mixed $data = null,
        string $message = 'Operation completed successfully.',
        int $status = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Return an error JSON response.
     */
    protected function errorResponse(
        string $message,
        int $status = 422,
        mixed $errors = null
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    /**
     * Return standardized pagination information.
     */
    protected function paginationData(
        LengthAwarePaginator $paginator
    ): array {
        return [
            'data' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
            'links' => $paginator->linkCollection(),
        ];
    }

    /**
     * Redirect back with a success message.
     */
    protected function redirectSuccess(
        string $message
    ): RedirectResponse {
        return back()->with('success', $message);
    }

    /**
     * Redirect back with an error message.
     */
    protected function redirectError(
        string $message
    ): RedirectResponse {
        return back()->with('error', $message);
    }
}