<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Party;
use App\Models\Wish;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'party_id',
        'party_owner',
        'name',
        'email',
        'secret_santa',
        'token'
    ];

    // relations
    public function party(){
        return $this->belongsTo(Party::class);
    }

    public function wishes(){
        return $this->hasMany(Wish::class);
    }
}
