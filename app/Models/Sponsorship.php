<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsorship extends Model
{
    use HasFactory;

    protected $fillable = ['sponsee_id', 'sponsor_id'];

    public function sponsee()
    {
        return $this->belongsTo(User::class, 'sponsee_id');
    }

    public function sponsor()
    {
        return $this->belongsTo(User::class, 'sponsor_id');
    }
}
