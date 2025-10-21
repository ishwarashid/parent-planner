<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $childColor = $this->child ? $this->child->color : '#dc3545';
        // all logs are working
        logger($this->id);
        logger($this->title);
        logger($this->description);
        logger($this->start);
        logger($this->end);
        logger($this->child_id);
        logger($this->childColor);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'start' => $this->start, // Standard format for dates
            'end' => $this->end, // Only include if not null
            'child_id' => $this->child_id,
            'assigned_to' => $this->assigned_to,
            'status' => $this->status,
            'custom_status_description' => $this->custom_status_description,
            'color' => $childColor,

            // You can even include the full child object if you want!
            // 'child' => new ChildResource($this->whenLoaded('child')),
        ];
    }
}
