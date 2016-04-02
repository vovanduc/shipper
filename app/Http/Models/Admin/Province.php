<?php

namespace App\Http\Models\Admin;

class Province extends \Eloquent
{
    protected $table = 'province';

    public function packages(){
        return $this->hasMany('\App\Http\Models\Admin\Package', 'province_id', 'provinceid');
    }
}
