<?php

namespace Modules\Shared\Traits;

use Illuminate\Http\JsonResponse;

trait BaseResponse
{
    /**
     * Response sukses umum
     */
    protected function success(
        string $message = 'Berhasil.',
        mixed $data = null,
        int $code = 200,
        array $extra = []
    ): JsonResponse {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        if (!empty($extra)) {
            $response = array_merge($response, $extra);
        }

        return response()->json($response, $code);
    }

    /**
     * Response untuk data paginasi
     */
    protected function paginated(
        string $message = 'Data berhasil diambil.',
        $paginator = null
    ): JsonResponse {
        if (!$paginator) {
            return $this->success($message);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $paginator->items(),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'from'         => $paginator->firstItem(),
                'to'           => $paginator->lastItem(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
            'links'   => [
                'first' => $paginator->url(1),
                'last'  => $paginator->url($paginator->lastPage()),
                'prev'  => $paginator->previousPageUrl(),
                'next'  => $paginator->nextPageUrl(),
            ],
        ], 200);
    }

    /**
     * Response error umum
     */
    protected function error(
        string $message = 'Terjadi kesalahan.',
        int $code = 400,
        mixed $errors = null
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Response untuk validasi gagal
     */
    protected function validationError(mixed $errors): JsonResponse
    {
        return $this->error('Validasi gagal.', 422, $errors);
    }

    /**
     * Response untuk data tidak ditemukan
     */
    protected function notFound(string $message = 'Data tidak ditemukan.'): JsonResponse
    {
        return $this->error($message, 404);
    }

    /**
     * Response untuk unauthorized
     */
    protected function unauthorized(string $message = 'Tidak memiliki izin.'): JsonResponse
    {
        return $this->error($message, 403);
    }

    /**
     * Response untuk internal server error
     */
    protected function serverError(
        string $message = 'Terjadi kesalahan pada server.'
    ): JsonResponse {
        return $this->error($message, 500);
    }
}
