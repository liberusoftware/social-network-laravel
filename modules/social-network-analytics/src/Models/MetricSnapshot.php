<?php

namespace Liberu\SocialNetwork\Analytics\Models;

use Illuminate\Database\Eloquent\Model;

class MetricSnapshot extends Model
{
    protected $table = 'social_analytics_snapshots';

    protected $guarded = [];

    protected $casts = ['dimensions' => 'array', 'period_start' => 'date', 'period_end' => 'date'];
}
