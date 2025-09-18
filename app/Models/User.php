<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model
{

    use HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
    ];


    protected $hidden = [];


    protected function casts(): array
    {
        return [];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
