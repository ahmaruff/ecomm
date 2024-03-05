<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    public const IS_SUPER_ADMIN = 1;
    public const IS_CUSTOMER = 2;
    public const IS_SHOP_SALES = 3;
    public const IS_SHOP_CS = 4;
    public const IS_SHOP_ADMIN = 5;

    protected $fillable = [
        'name'
    ];

    public function users() : HasMany {
        return $this->hasMany(User::class);
    }

}
