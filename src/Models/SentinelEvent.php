<?php

namespace NazirulAmin\SentinelActor\Models;

use Illuminate\Database\Eloquent\Model;

class SentinelEvent extends Model
{
    protected $table = 'sentinel_events';

    protected $fillable = [
        'application_id',
        'event_type',
        'level',
        'message',
        'context',
        'timestamp',
    ];

    protected $casts = [
        'context' => 'array',
        'timestamp' => 'datetime',
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        if (! isset($this->connection)) {
            $this->setConnection(config('database.default'));
        }

        parent::__construct($attributes);
    }
}
