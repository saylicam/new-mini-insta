<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Les attributs qui peuvent être attribués en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name', // Ajout du prénom
        'email',
        'password',
        'bio', // Ajout de la bio
        'profile_image', // Ajout de la photo de profil
    ];

    /**
     * Les attributs qui doivent être masqués pour la sérialisation.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relation entre un utilisateur et ses publications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Relation entre un utilisateur et ses followers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function followers()
    {
        return $this->hasMany(Follower::class, 'followed_id'); // Associe les followers par leur ID
    }

    /**
     * Relation entre un utilisateur et ceux qu'il suit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function following()
    {
        return $this->hasMany(Follower::class, 'follower_id'); // Associe les suivis par leur ID
    }
}
