<?php

namespace App\Http\Models\Admin;
use Illuminate\Events\Dispatcher;
class Package extends \Eloquent
{
    protected $table = 'packages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid','address','label','status','customer_id','shipper_id','note',
        'county','place_id','latitude','longitude','price','distance','duration',
        'steps','content','quantity','parent','location_id','delivery_at','invoice',
        'service_type','weight','content','kgs','phone','customer_from','province_id','district_id','info','date'
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
            return '<h4><span class="label" style="background-color:#743C08">Đang ở tại kho Mỹ</span></h4>';
        } else if ($this->attributes['status'] == 2) {
            return '<h4><span class="label" style="background-color:#FF2E63">Đang gửi về Việt Nam</span></h4>';
        } else if ($this->attributes['status'] == 3) {
            return '<h4><span class="label" style="background-color:#8DC6FF">Đã về Việt Nam - nội địa Tphcm</span></h4>';
        } else if ($this->attributes['status'] == 4) {
            return '<h4><span class="label" style="background-color:#4F1C4C">Đang giao hàng</span></h4>';
        } else if ($this->attributes['status'] == 5) {
            return '<h4><span class="label" style="background-color:#08D9D6">Giao hàng thành công</span></h4>';
        } else if ($this->attributes['status'] == 6) {
            return '<h4><span class="label" style="background-color:#A42127">Đã hủy</span></h4>';
        } else if ($this->attributes['status'] == 7) {
            return '<h4><span class="label" style="background-color:#4F1C4C">Đang giao hàng tại Tphcm</span></h4>';
        } else if ($this->attributes['status'] == 8) {
            return '<h4><span class="label" style="background-color:#4F1C4C">Đang giao hàng ngoại thành</span></h4>';
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

    public function getDeliveryAtAttribute($date)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y');
    }

    public function getDateAttribute($date)
    {
        if($date == '0000-00-00 00:00:00') return '';
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y');
    }

    public function getCvPriceAttribute()
    {
        if ($this->attributes['price']) {
            return \Currency::format($this->attributes['price']);
        }
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

    public function getShowBarcodeAttribute()
    {
        if ($this->attributes['label']) {
            return '<a target="_blank" href="' . \DNS1D::getBarcodePNGPath($this->attributes['label'], "C128",1,50) . '">
            <img src="' . \DNS1D::getBarcodePNGPath($this->attributes['label'], "C128",1,50) . '" alt="barcode"/></a>';
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

    public function from_customer()
    {
        return $this->belongsTo('\App\Http\Models\Admin\Customer', 'customer_from','uuid');
    }

    public function customer()
    {
        return $this->belongsTo('\App\Http\Models\Admin\Customer', 'customer_id','uuid');
    }

    public function shipper()
    {
        return $this->belongsTo('\App\Http\Models\Admin\Shipper', 'shipper_id','uuid');
    }

    public function location()
    {
        return $this->hasOne('\App\Http\Models\Admin\Location', 'uuid', 'location_id');
    }

    public function package_parent()
    {
        return $this->hasOne('\App\Http\Models\Admin\Package', 'uuid','parent');
    }

    public function packages_parent()
    {
        return $this->hasMany('\App\Http\Models\Admin\Package', 'parent','uuid');
    }

    public function province()
    {
        return $this->hasOne('\App\Http\Models\Admin\Province', 'provinceid','province_id');
    }

    public function district()
    {
        return $this->hasOne('\App\Http\Models\Admin\District', 'districtid','district_id');
    }

    public static function convert($data)
    {
        $data->created_by = !empty($data->user_created->name) ? $data->user_created->name : '';
        $data->updated_by = !empty($data->user_updated->name) ? $data->user_updated->name : '';
        $data->customer = !empty($data->customer->name) ? $data->customer->name : '';
        $data->shipper = !empty($data->shipper->name) ? $data->shipper->name : '';
        $data->from_customer = !empty($data->from_customer->name) ? $data->from_customer->name : '';
        $data->province = !empty($data->province->name) ? $data->province->name : '';
        $data->district = !empty($data->district->name) ? $data->district->name : '';
        return $data;
    }

    public static function create_label(){
        $start = (new \Carbon('now'))->hour(0)->minute(0)->second(0);
        $end = (new \Carbon('now'))->hour(23)->minute(59)->second(59);
        $count = \Package::whereBetween('created_at', [$start , $end])->count();
        $count = $count + 1;
        $today = \Carbon::today();
        $result = $count.'-'.$today->format('d-m-y');
        return $result;
    }

    public static function get_status_option($index='')
    {
        $data = array();

        $data[1] = 'Đang ở tại kho Mỹ';
        $data[2] = 'Đang gửi về việt nam';
        $data[3] = 'Đã về việt nam - nội địa Tphcm';
        $data[4] = 'Đang giao hàng';
        $data[5] = 'Giao hàng thành công';
        $data[6] = 'Đã hủy';
        $data[7] = 'Đang giao hàng tại Tphcm';
        $data[8] = 'Đang giao hàng ngoại thành';

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
        $data[20] = 'Huyện Củ Chi';
        $data[21] = 'Huyện Hóc Môn';
        $data[22] = 'Huyện Bình Chánh';
        $data[23] = 'Huyện Nhà Bè';
        $data[24] = 'Huyện Cần Giờ';

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
               ->setParamByKey('origin', \Config::get('constants.MAPS_ADDRESS'))
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

        //$data['steps'] = serialize($legs->steps);

        return $data;
    }

    public static function get_county_package()
    {
        $data = array();

        for ($i=1; $i <=19 ; $i++) {
            $result= \Package::where('deleted', 0)->where('county',$i)->where('status', 3);
            if ($result->count() > 0) {
                $data[$i] = \Package::get_county_option($i).' ('.$result->count().' kiện hàng)';
            }
        }

        return $data;
    }

    public static function get_province_package()
    {
        $data = array();
        $province = \Province::get();
        foreach($province as $item) {
            if($item->packages()->whereStatus(3)->count()) {
                $data[$item->provinceid] = $item->name;
            }
        }

        return $data;
    }

    public function getCvLabelAttribute()
    {
        if ($this->attributes['label']) {
            $temp = \URL::route('admin.packages.show',$this->attributes['uuid']);
            return '<a href="'.$temp.'" target="_blank">'.$this->attributes['label'].'</a>';
        }
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeDeleted($query, $value)
    {
        return $query->where('deleted', $value);
    }
}
