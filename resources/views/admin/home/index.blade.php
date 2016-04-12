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
            <div class="row">
                @for ($i=1; $i<=8 ; $i++)
                <div class="col-sm-4 text-center" style="min-height: 200px;">
                    <a href="#" class="btn btn-block btn-primary">
                        {{\Package::get_status_option($i)}}
                    </a>
                    @if(isset($sum[$i]))
                        @foreach($sum[$i] as $item)
                            <span class="badge badge_mini">
                                {{\Shipment::whereUuid($item->shipment_id)->first()->key}}
                                ({{$item->packages_count}})
                            </span>
                        @endforeach
                    @endif
                </div>
                @endfor
            </div>
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
