@extends('admin.layouts.app')

@section('content')
<style>
    .badge_mini{
        font-size: 30px;
        margin-top: 20px;
    }
</style>
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">Thống kê trạng thái kiện hàng</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-4 text-center">
                    <a href="#" class="btn btn-block btn-primary">Đang ở tại kho Mỹ</a>
                    <span class="badge badge_mini">{{$sum[1]}}</span>
                </div>
                <div class="col-sm-4 text-center">
                    <a href="#" class="btn btn-block btn-primary">Đang gửi về Việt Nam</a>
                    <span class="badge badge_mini">{{$sum[2]}}</span>
                </div>
                <div class="col-sm-4 text-center">
                    <a href="#" class="btn btn-block btn-primary">Đã về Việt Nam - nội địa Tphcm</a>
                    <span class="badge badge_mini">{{$sum[3]}}</span>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-4 text-center">
                    <a href="#" class="btn btn-block btn-primary">Đang giao hàng</a>
                    <span class="badge badge_mini">{{$sum[4]}}</span>
                </div>
                <div class="col-sm-4 text-center">
                    <a href="#" class="btn btn-block btn-primary">Giao hàng thành công</a>
                    <span class="badge badge_mini">{{$sum[5]}}</span>
                </div>
                <div class="col-sm-4 text-center">
                    <a href="#" class="btn btn-block btn-primary">Đã hủy</a>
                    <span class="badge badge_mini">{{$sum[6]}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
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
    </div>
</div>
@endsection
