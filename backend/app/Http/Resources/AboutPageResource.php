<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutPageResource extends JsonResource
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
            'hero_eyebrow' => $this->hero_eyebrow,
            'hero_title' => $this->hero_title,
            'hero_description' => $this->hero_description,
            'mission_eyebrow' => $this->mission_eyebrow,
            'mission_title' => $this->mission_title,
            'mission_description' => $this->mission_description,
            'mission_image' => asset('storage/' . $this->mission_image),
            'values_eyebrow' => $this->values_eyebrow,
            'values_title' => $this->values_title,
            'values_description' => $this->values_description,
            'team_eyebrow' => $this->team_eyebrow,
            'team_title' => $this->team_title,
            'team_description' => $this->team_description,
            'milestones_eyebrow' => $this->milestones_eyebrow,
            'milestones_title' => $this->milestones_title,
            'milestones_description' => $this->milestones_description,
            'cta_title' => $this->cta_title,
            'cta_description' => $this->cta_description,
        ];
    }
}
