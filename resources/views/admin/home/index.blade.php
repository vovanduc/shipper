@extends('admin.layouts.app')

@section('content')
<style>
    .badge_mini{
        font-size: 20px;
        margin-top: 20px;
    }
</style>
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">Thống kê trạng thái kiện hàng</div>
        <div class="panel-body">
            @for ($i=1; $i<=9 ; $i++)
            <div class="row">
                <div class="col-sm-12 text-center">
                    <a href="#" class="btn btn-block btn-primary">
                        {{\Package::get_status_option($i)}}
                    </a>

                    @foreach($sum[$i] as $item)
                        <span class="badge badge_mini">
                            @if(\Shipment::whereUuid($item->shipment_id)->first())
                                {{\Shipment::whereUuid($item->shipment_id)->first()->key}}
                            @else
                                Chưa có lô hàng
                            @endif
                            ({{$item->packages_count}})
                        </span>
                    @endforeach
                    </br></br>
                </div>
            </div>
            @endfor
        </div>
    </div>

    <!-- <div class="panel panel-default">
        <div class="panel-heading">Thống kê vị trí kiện hàng</div>
        <div class="panel-body">
            <div class="row">
                @foreach($list_location as $item)
                <?php
                    $item = Location::convert($item);
                ?>
                <div class="col-sm-4 text-center">
                    <a href="#" class="btn btn-block btn-primary">{{$item->name}}</a>
                    <span class="badge badge_mini">{{$item->quantity}}</span>
                    <br/><br/>
                    <b>Sử dụng<b/>
                    <br/>
                    <span class="badge badge_mini">{{$item->packages->count()}}</span>
                    <br/><br/>
                    <b>Còn trống<b/>
                    <br/>
                    <span class="badge badge_mini">{{$item->quantity - $item->packages->count()}}</span>
                </div>
                @endforeach
            </div>

        </div>
    </div> -->
</div>
@endsection
