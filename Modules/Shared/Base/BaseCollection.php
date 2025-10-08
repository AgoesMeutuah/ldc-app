<?php

namespace Modules\Shared\Base;

use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class BaseCollection extends ResourceCollection
{
    /**
     * Tentukan struktur dasar dari response collection.
     */
    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
        ];
    }

    /**
     * Tambahkan meta global agar semua collection seragam.
     */
    public function with($request): array
    {
        $response = [
            'success' => true,
            'message' => 'Data berhasil diambil.',
        ];

        // Jika hasilnya adalah pagination bawaan Laravel
        if (method_exists($this->resource, 'total')) {
            $response['meta'] = [
                'current_page' => $this->resource->currentPage(),
                'from'         => $this->resource->firstItem(),
                'to'           => $this->resource->lastItem(),
                'per_page'     => $this->resource->perPage(),
                'total'        => $this->resource->total(),
                'last_page'    => $this->resource->lastPage(),
            ];

            $response['links'] = [
                'first' => $this->resource->url(1),
                'last'  => $this->resource->url($this->resource->lastPage()),
                'prev'  => $this->resource->previousPageUrl(),
                'next'  => $this->resource->nextPageUrl(),
            ];
        }

        return $response;
    }
}
