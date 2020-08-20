<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberNotificationView extends Model
{
    protected $fillable  = [
        'member_notification_id', 'user_id', 'is_open'
    ];
}
