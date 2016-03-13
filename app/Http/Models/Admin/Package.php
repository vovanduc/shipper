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
        'uuid','address','label','status','customer_id_from','customer_id_to','note',
        'county','place_id','latitude','longitude','price','distance','duration',
        'steps'
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

    public function getCvPriceAttribute()
    {
        if ($this->attributes['price']) return \Currency::format($this->attributes['price']);
        return '';
    }

    public function getCvDistanceAttribute()
    {
        if ($this->attributes['distance']) {
            // $temp = round($this->attributes['distance'] / 1000);
            // if ($temp <= 0) {
            //     return  $this->attributes['distance'] . ' M';
            // } else {
            //   return  $temp . ' KM';
            // }
            return  $this->attributes['distance'] . ' M';
        }
        return '';
    }

    public function getCvDurationAttribute()
    {
        if ($this->attributes['duration']) {
            $temp = round($this->attributes['duration'] / 60);
            $temp = $temp ? $temp : 1;
            return  $temp . ' phút';
        }
        return '';
    }

    public function user_created()
    {
        return $this->belongsTo('\App\Http\Models\Admin\User', 'created_by');
    }

    public function user_updated()
    {
        return $this->belongsTo('\App\Http\Models\Admin\User', 'updated_by');
    }

    public function customer_from()
    {
        return $this->belongsTo('\App\Http\Models\Admin\Customer', 'customer_id_from');
    }

    public function customer_to()
    {
        return $this->belongsTo('\App\Http\Models\Admin\Customer', 'customer_id_to');
    }

    public static function convert($data)
    {
        $data->created_by = !empty($data->user_created->name) ? $data->user_created->name : '';
        $data->updated_by = !empty($data->user_updated->name) ? $data->user_updated->name : '';
        $data->customer_id_from = !empty($data->customer_from->name) ? $data->customer_from->name : '';
        $data->customer_id_to = !empty($data->customer_to->name) ? $data->customer_to->name : '';


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

        $data[''] = 'Chọn quận';

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

    public static function convert_address_to_lat_long($value)
    {
        $data = array();

        $response = \GoogleMaps::load('directions')
               ->setParamByKey('origin', '262 Bùi Viện, Phạm Ngũ Lão, Quận 1, Hồ Chí Minh, Việt Nam')
               ->setParamByKey('destination', $value)
               ->setParamByKey('language', 'vi')
               ->setParamByKey('alternatives', true)
               ->get();

        // $response = \GoogleMaps::load('distancematrix')
        //
        //         ->setParamByKey('origins', '262 Bùi Viện, Phạm Ngũ Lão, Quận 1, Hồ Chí Minh, Việt Nam')
        //         ->setParamByKey('destinations', $value)
        //         ->setParamByKey('language', 'vi')
        //         ->setParamByKey('mode', 'driving')
        //         ->get();
        $response = json_decode($response);

        if($response->status != 'OK') {
            dd($response);exit;
        }

        $legs = $response->routes[0]->legs[0];

        if ($legs->distance->value) {
            $data['distance'] = $legs->distance->value ? $legs->distance->value : '';
        }

        if ($legs->duration->value) {
            $data['duration'] = $legs->duration->value ? $legs->duration->value : '';
        }

        if ($legs->end_location->lat) {
            $data['latitude'] = $legs->end_location->lat ? $legs->end_location->lat : '';
        }

        if ($legs->end_location->lng) {
            $data['longitude'] = $legs->end_location->lng ? $legs->end_location->lng : '';
        }

        $data['price'] = $data['distance'] * 20;
        $data['steps'] = serialize($legs->steps);

        return $data;
    }

    public static function get_county_package()
    {
        $data = array();

        for ($i=1; $i <=19 ; $i++) {
            $result= \Package::where('deleted', 0)->where('county',$i)->where('status', 2);
            if ($result->count() > 0) {
                $data[$i] = \Package::get_county_option($i).' ('.$result->count().' kiện hàng)';
            }
        }

        return $data;
    }
}
