<?php

namespace App\Http\Models\Admin;

class Customer extends \Eloquent
{
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid','phone', 'email', 'name', 'address'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

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

    public function getUpdatedAtAttribute($date)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('H:i d-m-Y');
    }

    public function user_created()
    {
        return $this->belongsTo('\App\Http\Models\Admin\User', 'created_by');
    }

    public function user_updated()
    {
        return $this->belongsTo('\App\Http\Models\Admin\User', 'updated_by');
    }
}
