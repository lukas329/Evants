<?php

namespace App\Models;

use App\Http\Controllers\EventReviewController;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'event_date',
        'event_time',
        'location',
        'type',
        'organizer_id',
        'price',
        'sport_id'
    ];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function reviews()
    {
        return $this->hasMany(UserReview::class);
    }
    public function receivedReviews()
    {
        return $this->hasMany(EventReviewController::class, 'event_id');
    }
}
