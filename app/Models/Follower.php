<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;

    /**
     * Les attributs pouvant être attribués en masse.
     *
     * @var array
     */
    protected $fillable = [
        'follower_id',
        'followed_id',
    ];

    /**
     * Relation avec l'utilisateur qui suit.
     */
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    /**
     * Relation avec l'utilisateur suivi.
     */
    public function followed()
    {
        return $this->belongsTo(User::class, 'followed_id');
    }
}
