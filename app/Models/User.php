<?php

namespace App\Models;

use App\ValueObjects\User\Type;
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
