<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventReview extends Model
{
    use HasFactory;
    protected $table = 'event_reviews';

    protected $fillable = [
        'user_id',
        'event_id',
        'rating',
        'comment'
    ];
    public function reviewed()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
