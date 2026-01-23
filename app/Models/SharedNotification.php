<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharedNotification extends Model
{
    protected $fillable = ['notification_audience_id', 'notification_type_id', 'notification_category_id', 'title', 'body', 'data'];
    protected $casts = ['data' => 'array'];
    protected $appends = ['type'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_user', 'shared_notification_id', 'user_id')
            ->withPivot('read_at')
            ->withTimestamps();
    }


    public function scopeUnreadBy($query, $userId)
    {
        return $query->whereHas('users', function ($q) use ($userId) {
            $q->where('user_id', $userId)
                ->whereNull('read_at');
        });
    }
public function type()
{
    return $this->belongsTo(NotificationType::class, 'notification_type_id');
}

public function getTypeAttribute(): string
{
    return $this->type?->style_class ?? 'primary';
}
}
