<?php

namespace App\Models;

use App\Enums\ProfileStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => ProfileStatusEnum::class
    ];

    public function scopeActive($query)
    {
        $query->where('status', ProfileStatusEnum::ACTIVE);
    }

    public function scopeInactive($query)
    {
        $query->where('status', ProfileStatusEnum::INACTIVE);
    }

}
