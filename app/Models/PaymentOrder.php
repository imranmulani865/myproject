<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'plan_id',
        'amount_in_paise',
        'currency',
        'tokens',
        'status',
        'error_reason',
    ];

    protected $casts = [
        'amount_in_paise' => 'integer',
        'tokens' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedAmountAttribute(): string
    {
        return '₹' . number_format($this->amount_in_paise / 100, 2);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
