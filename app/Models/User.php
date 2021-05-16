<?php

namespace App\Models;

use App\ValueObjects\User\Type;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email'];
    protected $hidden = ['password'];
    public $timestamps = true;
}
