<?php

namespace App\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'document', 'type'];
    protected $hidden = ['password'];
    public $timestamps = true;

    public function isSeller()
    {
        return ($this->type === Type::SELLER);
    }
}
