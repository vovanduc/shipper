<?php

namespace App\Http\Models\Admin;

class Package extends \Eloquent
{
    protected $table = 'packages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid','address','label','status','customer_id','shipper_id','note','county','place_id'
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

    public function getCvStatusAttribute($value)
    {
        if ($this->attributes['status'] == 1) {
            return '<b>Đang gửi về việt nam</b>';
        } else if ($this->attributes['status'] == 2) {
            return '<b>Đã về việt nam - nội địa Tphcm</b';
        }else if ($this->attributes['status'] == 3) {
            return '<b>Đang giao hàng</b';
        }else if ($this->attributes['status'] == 4) {
            return '<b>Giao hàng thành công</b';
        }else if ($this->attributes['status'] == 5) {
            return '<b>Đã hủy</b';
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

    public function customer()
    {
        return $this->belongsTo('\App\Http\Models\Admin\Customer', 'customer_id');
    }

    public function shipper()
    {
        return $this->belongsTo('\App\Http\Models\Admin\Shipper', 'shipper_id');
    }

    public static function convert($data)
    {
        $data->created_by = !empty($data->user_created->name) ? $data->user_created->name : '';
        $data->updated_by = !empty($data->user_updated->name) ? $data->user_updated->name : '';
        $data->customer_id = !empty($data->customer->name) ? $data->customer->name : '';
        $data->shipper_id = !empty($data->shipper->name) ? $data->shipper->name : '';


        return $data;
    }

    public static function create_label(){
        $from = date('Y-m-d H:i:s', strtotime('today', time()));
        $to = date('Y-m-d 24:00:00', strtotime('today', time()));
        $count = \Package::where('created_at', '>=', $from)->where('created_at', '<=', $to)->count();
        $count++;
        $today = \Carbon::today();
        $result = $count.'-'.$today->format('d-m-y');
        return $result;
    }

    public static function get_status_option($index='')
    {
        $data = array();

        $data[1] = 'Đang gửi về việt nam';
        $data[2] = 'Đã về việt nam - nội địa Tphcm';
        $data[3] = 'Đang giao hàng';
        $data[4] = 'Giao hàng thành công';
        $data[5] = 'Đã hủy';

        if ($index) {
            return $data[$index];
        } else {
            return $data;
        }
    }

    public static function get_county_option($index='')
    {
        $data = array();

        $data[0] = 'Chọn quận';

        for ($i=1; $i <= 12; $i++) {
            $data[$i] = 'Quận '.$i;
        }

        $data[13] = 'Quận Thủ Đức';
        $data[14] = 'Quận Gò Vấp';
        $data[15] = 'Quận Bình Thạnh';
        $data[16] = 'Quận Tân Bình';
        $data[17] = 'Quận Tân Phú';
        $data[18] = 'Quận Phú Nhuận';
        $data[19] = 'Quận Bình Tân';

        if ($index) {
            return $data[$index];
        } else {
            return $data;
        }

        return $data;
    }
}
