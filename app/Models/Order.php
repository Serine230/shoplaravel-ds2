<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'subtotal', 'shipping', 'total', 'status',
        'payment_method', 'shipping_address', 'shipping_city',
        'shipping_phone', 'notes', 'tracking_number',
    ];

    const STATUS_LABELS = [
        'en_attente' => ['label' => 'En attente',  'color' => 'yellow'],
        'validee'    => ['label' => 'Validée',      'color' => 'blue'],
        'expediee'   => ['label' => 'Expédiée',     'color' => 'purple'],
        'livree'     => ['label' => 'Livrée',       'color' => 'green'],
        'annulee'    => ['label' => 'Annulée',      'color' => 'red'],
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status]['label'] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_LABELS[$this->status]['color'] ?? 'gray';
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['en_attente', 'validee']);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
