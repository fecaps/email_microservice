<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function addToQueue(array $data): int
    {
        $data = self::create([
            'status' => 'queued',
            'message' => json_encode([ 'data' => $data ]),
        ]);

        return $data['id'];
    }

    public function updateStatusToBounced(int $id): void
    {
        self::where([ 'id' => $id ])
            ->update([ 'status' => 'bounced' ]);
    }

    public function updateStatusToFailed(int $id): void
    {
        self::where([ 'id' => $id ])
            ->update([ 'status' => 'failed' ]);
    }

    public function updateStatusToDelivered(int $id): void
    {
        self::where([ 'id' => $id ])
            ->update([ 'status' => 'delivered' ]);
    }
}
