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
                                {{Form::select("shipper",$shippers,$shipper,array('class' => 'form-control select_auto', 'placeholder' => 'Chọn người đi giao hàng'))}}
                            </div>
                        </div>
                        <div class="col-xs-6" >
                            <div class="left-inner-addon">
                                <div class="left-inner-addon">
                                    {{Form::select("province_id",\Package::get_province_package(),$province_id,array('class' => 'form-control province select_auto', 'placeholder' => 'Chọn tỉnh/Thành phố'))}}
                                </div>
                                <br/>
                                <div class="left-inner-addon">
                                    <input type="hidden" id="get_district_id" value="{{ $district_id }}"/>
                                    {{Form::select("district_id",array(),$district_id,array('class' => 'form-control', 'placeholder' => 'Quận/Huyện', 'id' => 'district'))}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-xs-12" >
                            <div class="left-inner-addon">
                                {{Form::text("label",$label,array('class' => 'form-control', 'placeholder' => 'Nhập label'))}}
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
                <!-- <div id="map" style="width: 100%;height: 500px;"></div> -->
            {{ Form::close() }}

            @if($mess)
                <li class="list-group-item">
                    <div class="row">
                        <div class="alert alert-success">
                            {!!$mess!!}
                        </div>
                    </div>
                </li>
            @endif

            @if($result)
                @foreach($result as $item)
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-xs-12" >
                            {{$item->address}}<br/>
                            <a href="{{URL::route('admin.packages.show', $item->uuid)}}" target="_blank">
                                Xem chi tiết
                            </a>
                            {{ Form::open(array('route' => array('admin.packages.find'), 'method' => 'get')) }}
                                {{Form::hidden("shipper",$shipper,array('class' => 'form-control'))}}
                                {{Form::hidden("province_id",$province_id,array('class' => 'form-control'))}}
                                {{Form::hidden("district_id",$district_id,array('class' => 'form-control'))}}
                                {{Form::hidden("package_id",$item->uuid,array('class' => 'form-control'))}}
                                <span class="pull-right">
                                    <button class="btn btn-primary" type="submit" href="{{URL::route('admin.packages.show', $item->uuid)}}" target="_blank">
                                    Chọn kiện hàng
                                    </button>
                                </span>
                            {{ Form::close() }}
                        </div>
                    </div>
                </li>
                @endforeach
            @endif
            </ul>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>

$(function() {
    $('.select_auto').change(function() {
        this.form.submit();
    });
    $('#district').change(function() {
        this.form.submit();
    });
});

// function initMap() {
//     var myLatLng = {lat: 10.779682, lng: 106.661464};
//
//     var map = new google.maps.Map(document.getElementById('map'), {
//         zoom: 12,
//         mapTypeId: 'roadmap',
//         center: myLatLng
//     });
//
//     var marker = new google.maps.Marker({
//         position: myLatLng,
//         map: map,
//         icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
//     });
//
//
//     @foreach ($result as $item)
//         console.log('{{ $item->uuid }}');
//         var myLatLng = {lat: {{ $item->latitude }}, lng: {{ $item->longitude }}};
//
//         var marker = new google.maps.Marker({
//             position: myLatLng,
//             map: map,
//             title: '{!! $item->address !!}'
//         });
//     @endforeach
// }

function initMap() {
    var directionsService = new google.maps.DirectionsService;
    var directionsDisplay = new google.maps.DirectionsRenderer;
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 17,
        center: {lat: 10.779682, lng: 106.661464}
    });
    directionsDisplay.setMap(map);
    calculateAndDisplayRoute(directionsService, directionsDisplay);
}

function calculateAndDisplayRoute(directionsService, directionsDisplay) {
    var waypts = [];
    var checkboxArray = document.getElementById('waypoints');

    <?php $count = 1?>
    var end_address = '';
    @foreach ($result as $item)
        @if($count != count($result))
            waypts.push({
                location: '{!! $item->address !!}',
                stopover: true
            });
        @endif
        @if($count == count($result))
            end_address = '{!! $item->address !!}';
        @endif
        <?php $count++?>
    @endforeach



    directionsService.route({
    origin: "{!!Config::get('constants.MAPS_ADDRESS')!!}",
    destination: end_address,
    waypoints: waypts,
    optimizeWaypoints: true,
    travelMode: google.maps.TravelMode.DRIVING
    }, function(response, status) {
        if (status === google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);console.log(response);
            var route = response.routes[0];
            var summaryPanel = document.getElementById('directions-panel');
            summaryPanel.innerHTML = '';
            // For each route, display summary information.
            for (var i = 0; i < route.legs.length; i++) {
                var routeSegment = i + 1;
                summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment +
                    '</b><br>';
                summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
                summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
                summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br>';
            }
        } else {
            window.alert('Directions request failed due to ' + status);
        }
    });
}

</script>
@stop
