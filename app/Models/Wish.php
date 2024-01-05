<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Participant;

class Wish extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_id',
        'name',
        'link'
    ];

    // relations
    public function participant(){
        return $this->belongsTo(Participant::class);
    }
}
