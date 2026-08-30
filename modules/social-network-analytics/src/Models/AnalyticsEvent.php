<?php

namespace Liberu\SocialNetwork\Analytics\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsEvent extends Model
{
    protected $table = 'social_analytics_events';

    protected $guarded = [];

    protected $casts = ['dimensions' => 'array', 'occurred_on' => 'date'];
}
