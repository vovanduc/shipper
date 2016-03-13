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
