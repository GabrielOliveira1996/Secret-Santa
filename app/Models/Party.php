<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Participant;

class Party extends Model
{
    use HasFactory;

    protected $fillable = [
            'date',
            'location',
            'maximumValue',
            'message'
    ];
    // relations
    public function participants(){
        return $this->hasMany(Participant::class);
    }
}
