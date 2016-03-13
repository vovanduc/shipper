@extends('admin.layouts.app')

@section('content')
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
        		<div class="col-md-6">Tìm kiếm kiện hàng vận chuyển</div>
            </div>
        </div>

        <div class="panel-body">
            {{ Form::open(array('route' => array('admin.packages.find'), 'method' => 'get')) }}
            <ul class="list-group">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-xs-6" >
                            <div class="left-inner-addon">
                                {{Form::select("shipper",$shippers,$shipper,array('class' => 'form-control select_auto', 'placeholder' => 'Chọn người vận chuyển'))}}
                            </div>
                        </div>
                        <div class="col-xs-6" >
                            <div class="left-inner-addon">
                                {{Form::select("county",\Package::get_county_package(),$county,array('class' => 'form-control'))}}
                            </div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-xs-12" >
                            <button type="submit" class="btn btn-primary btn-block">
                            {!!$show_label!!}
                            </button>
                        </div>
                    </div>
                </li>
                @if($result)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-xs-12" >
                                <div id="map" style="width: 100%;height: 500px;"></div>
                            </div>
                        </div>
                    </li>
                @endif
            </ul>
            {{ Form::close() }}


        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
function initMap() {
    var myLatLng = {lat: 10.781407, lng: 106.665561};

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: myLatLng
    });

    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        title: 'Hello World!',
        icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
    });

    @foreach ($result as $item)
        console.log('{{ $item->uuid }}');
    @endforeach

}
</script>
@stop
