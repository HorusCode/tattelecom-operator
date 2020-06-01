<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'statement_id' => $this->statement_id,
            'work_status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'service_user' =>  $this->serviceUser,
            'operator_user' => $this->operatorUser,
            'statement' => StatementResource::make($this->statement),
            'client' => $this->statement->client()->with('services')->first()
        ];
    }
}
