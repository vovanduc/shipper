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
        <div class="panel-heading">Tổng quan</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6 col-lg-6" align="center">
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-info"><b>Tổng tiền vận chuyển trong ngày</b></li>
                        <li class="list-group-item">
                            {{$money['day']}} <br/>
                            {{$money['day_packages']}} kiện hàng
                        </li>
                        <li class="list-group-item list-group-item-info"><b>Tổng tiền vận chuyển trong tuần</b></li>
                        <li class="list-group-item">
                            {{$money['week']}} <br/>
                            {{$money['week_packages']}} kiện hàng
                        </li>
                    </ul>
                </div>
                <div class="col-md-6 col-lg-6" align="center">
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-info"><b>Tổng tiền vận chuyển trong tháng</b></li>
                        <li class="list-group-item">
                            {{$money['month']}} <br/>
                            {{$money['month_packages']}} kiện hàng
                        </li>
                        <li class="list-group-item list-group-item-info"><b>Tổng tiền vận chuyển trong năm</b></li>
                        <li class="list-group-item">
                            {{$money['year']}} <br/>
                            {{$money['year_packages']}} kiện hàng
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
