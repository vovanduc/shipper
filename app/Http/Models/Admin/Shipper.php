<?php

namespace App\Http\Models\Admin;

class Shipper extends \Eloquent
{
    protected $table = 'shippers';

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

    public function setCreatedByAttribute($data)
    {
        $this->attributes['created_by'] = $data ? $data->name : '';
    }

    public function setUpdatedByAttribute($data)
    {
        $this->attributes['updated_by'] = $data ? $data->name : '';
    }

    public static function convert($data)
    {
        $data->created_by = $data->user_created;
        $data->updated_by = $data->user_updated;
        return $data;
    }

    public function packages(){
        return $this->hasMany('\App\Http\Models\Admin\Package', 'shipper_id');
    }
}
