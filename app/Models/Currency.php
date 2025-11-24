<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'currencies';
    protected $primaryKey = 'currency_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = [
        'icon_data',
    ];
    protected $fillable = [
        'currency_code',
        'currency_name',
        'buy_rate',
        'sell_rate',
        'icon_data',
        'icon_type',
        'user_id',
        'is_deleted'
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
        'buy_rate' => 'double',
        'sell_rate' => 'double',
        'last_updated' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function getIconUrlAttribute(): ?string
    {
        if (!$this->icon_data || !$this->icon_type) {
            return null;
        }

        return 'data:' . $this->icon_type . ';base64,' . base64_encode($this->icon_data);
    }

}
