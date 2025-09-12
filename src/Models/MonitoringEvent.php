<?php

namespace NazirulAmin\LaravelMonitoringClient\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoringEvent extends Model
{
    protected $table = 'monitoring_events';

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
