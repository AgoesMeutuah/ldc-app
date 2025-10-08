<?php

namespace Modules\Shared\Base;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseResource extends JsonResource
{
    /**
     * Tentukan struktur default response API
     */
    public function toArray($request): array
    {
        return [
            'id'         => $this->whenHas('id', $this->id),
            'created_at' => $this->whenHas('created_at', $this->created_at?->format('Y-m-d H:i:s')),
            'updated_at' => $this->whenHas('updated_at', $this->updated_at?->format('Y-m-d H:i:s')),
        ];
    }

    /**
     * Tambahkan meta global agar semua resource API seragam
     */
    public function with($request): array
    {
        return [
            'success' => true,
            'message' => 'Data berhasil diambil.',
        ];
    }
}
