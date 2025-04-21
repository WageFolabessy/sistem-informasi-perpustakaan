<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Enums\BookingStatus;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_user_id',
        'book_id',
        'status',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'expiry_date' => 'datetime',
        'status' => BookingStatus::class,
    ];

    public function siteUser(): BelongsTo
    {
        return $this->belongsTo(SiteUser::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function borrowing(): HasOne
    {
        return $this->hasOne(Borrowing::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', BookingStatus::Active);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', BookingStatus::Expired);
    }
}
