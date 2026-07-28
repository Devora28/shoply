<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class ProductResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'price' => $this->price,
            'discount' => $this->discount,
            'sku' => $this->sku,
            'stock' => $this->stock,
            'image' => asset('storage/' . $this->image),
            'sold_count' => $this->sold_count,
            'view_count' => $this->view_count,
            'is_featured' => $this->is_featured,
            'weight' => $this->weight,
            'meta' => $this->meta,
            'brand' => $this->whenLoaded('brand'),
            'category' => $this->whenLoaded('category'),
            'images' => $this->whenLoaded('images'),
        ];
    }
}
