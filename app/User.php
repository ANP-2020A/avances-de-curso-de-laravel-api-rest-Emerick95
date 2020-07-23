<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    //Definiendo roles como constantes
    const ROLE_SUPERADMIN = 'ROLE_SUPERADMIN';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    //Jerarquia de roles
    //private const ROLES_HIERARCHY = [
    //    self::ROLE_SUPERADMIN => [self::ROLE_ADMIN, self::ROLE_USER],//puede hacer lo que hace un Admin y Usuario
    //    self::ROLE_ADMIN => [self::ROLE_USER], //puede hacer lo que hace un user
    //    self::ROLE_USER => [] //solo tiene unicos permisos propios
    //];

    private const ROLES_HIERARCHY = [
        self::ROLE_SUPERADMIN => [self::ROLE_ADMIN],
        self::ROLE_ADMIN => [self::ROLE_USER],
        self::ROLE_USER => []
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier(){
        return $this->getKey();
    }
    public function getJWTCustomClaims(){
        return [];
    }
    //Relaciones one to many
    public function articles() {
        return $this->hasMany('App\Article');
    }
    //Relacion one to many
    public function comments() {
        return $this->hasMany('App\Comment');
    }
    public function categories(){
        return $this->belongsToMany('App\Category') ->as('subscriptions')->withTimestamps();
    }

    //funcion para verificar si esta asignado un usuario y que permisos tiene
    //public function isGranted($role) {
    //    return $role === $this->role || in_array($role, self::ROLES_HIERARCHY[$this->role]);
    //}

    public function isGranted($role) {
        if ($role === $this->role) {
            return true;
        }
        return self::isRoleInHierarchy($role, self::ROLES_HIERARCHY[$this->role]);
    }

    private static function isRoleInHierarchy($role, $role_hierarchy) {
        if (in_array($role, $role_hierarchy)) {
            return true;
        }
        foreach ($role_hierarchy as $role_included) {
            if(self::isRoleInHierarchy($role,self::ROLES_HIERARCHY[$role_included])){
                return true;
            }
        }
        return false;
    }

}
