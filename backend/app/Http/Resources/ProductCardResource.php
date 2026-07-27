<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'brand_id' => $this->brand_id,
            'category_id' => $this->category_id,
            'brand' => $this->whenLoaded('brand'),
            'category' => $this->whenLoaded('category'),
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'discount' => $this->discount,
            'sku' => $this->sku,
            'image' => asset('storage/' . $this->image),
        ];
    }
}
