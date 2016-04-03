<?php

namespace App\Http\Models\Admin;

class District extends \Eloquent
{
    protected $table = 'district';

    public function packages(){
        return $this->hasMany('\App\Http\Models\Admin\Package', 'district_id', 'districtid');
    }
}
