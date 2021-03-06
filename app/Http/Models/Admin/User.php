<?php

namespace App\Http\Models\Admin;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Models\Admin;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid','username', 'email', 'password','name','permissions'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getCvActiveAttribute($value)
    {
        if ($this->attributes['active'] == 1) {
            return '<span class="label label-success">Đang hoạt động</span>';
        } else {
            return '<span class="label label-danger">Không hoạt động</span';
        }
    }

    public function getCreatedAtAttribute($date)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('H:i d-m-Y');
    }

    public function getCvIsAdminAttribute($value)
    {
        if ($this->attributes['is_admin'] == 1) {
            return 'Có';
        } else {
            return 'Không';
        }
    }

    public function getCvIsRootAttribute($value)
    {
        if ($this->attributes['is_root'] == 1) {
            return 'Có';
        } else {
            return 'Không';
        }
    }

    public static function get_permissions($uuid) {
        $array_1 = \Config::get('lib.PERMISSIONS');
        $array_2 = \User::whereUuid($uuid)->first()->permissions;
        if($array_2 == '') {
            $array_2 = \Config::get('lib.PERMISSIONS');
        } else {
            $array_2 = unserialize($array_2);
        }
        $array = array_merge($array_1, $array_2);
        return $array;
    }
}
