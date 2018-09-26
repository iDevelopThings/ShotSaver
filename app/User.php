<?php

namespace App;

use App\Models\FileUploads;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function uploads()
    {
        return $this->hasMany(FileUploads::class);
    }

    public function spaceUsed()
    {
        return round($this->uploads()->sum('size_in_bytes') / 1024 / 1024, 2);
    }

    public function hitFileLimit()
    {
        return $this->spaceUsed() >= 100;
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }
}
