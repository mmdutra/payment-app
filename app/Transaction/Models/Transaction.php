<?php

declare(strict_types=1);

namespace App\Transaction\Models;

use App\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['payer_id', 'payee_id', 'value', 'status'];
    public $timestamps = ['created_at'];

    public function payer()
    {
        return $this->hasOne(User::class, 'id', 'payer_id');
    }

    public function payee()
    {
        return $this->hasOne(User::class, 'id', 'payee_id');
    }
}
