<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
