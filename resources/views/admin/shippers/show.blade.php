@extends('admin.layouts.app')

@section('content')

@if(!$permission_accept_show)
    @include('admin.errors.permission')
@endif

@if($permission_accept_show)
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6">{{Lang::get('admin.shipper.info')}} {{$result->username}} ( {{$result->email}} )</div>
                <div class="col-md-6"><span class="pull-right"><a href="{{URL::route('admin.shippers.index')}}">Trở lại</a></span></div>
            </div>
        </div>

        <div class="panel-body">

            <div class="row">
                <!-- <div class="col-md-4 col-lg-4 " align="center">
                    <i class="fa fa-btn fa-user" style="font-size: 150px;"></i>
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-info"><b>Tổng tiền vận chuyển trong ngày</b></li>
                        <li class="list-group-item">
                            {{$money['day']}}
                        </li>
                        <li class="list-group-item list-group-item-info"><b>Tổng tiền vận chuyển trong tuần</b></li>
                        <li class="list-group-item">
                            {{$money['week']}}
                        </li>
                        <li class="list-group-item list-group-item-info"><b>Tổng tiền vận chuyển trong tháng</b></li>
                        <li class="list-group-item">
                            {{$money['month']}}
                        </li>
                        <li class="list-group-item list-group-item-info"><b>Tổng tiền vận chuyển trong năm</b></li>
                        <li class="list-group-item">
                            {{$money['year']}}
                        </li>
                    </ul>
                </div> -->

                <div class=" col-md-12 col-lg-12 ">
                <table class="table table-user-information">
                    <tbody>
                        <tr>
                            <td>ID</td>
                            <td>{{$result->uuid}} </td>
                        </tr>
                        <tr>
                            <td>Username</td>
                            <td>{{$result->username}} </td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td>
                                @if($result->password)
                                    ******
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Tên</td>
                            <td>{{$result->name}} </td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>{{$result->email}} </td>
                        </tr>
                        <tr>
                            <td>Điện thoại</td>
                            <td>{{$result->phone}} </td>
                        </tr>
                        <tr>
                            <td>Địa chỉ</td>
                            <td>{{$result->address}} </td>
                        </tr>
                        <tr>
                            <td>Trạng thái</td>
                            <td>{!!$result->cv_active!!} </td>
                        </tr>
                        <tr>
                            <td>Người tạo</td>
                            <td>{{$result->created_by}} </td>
                        </tr>
                        <tr>
                            <td>Ngày tạo</td>
                            <td>{{$result->created_at}} </td>
                        </tr>
                        <tr>
                            <td>Người cập nhật</td>
                            <td>{{$result->updated_by}} </td>
                        </tr>
                        <tr>
                            <td>Ngày cập nhật</td>
                            <td>{{$result->updated_at}} </td>
                        </tr>
                    </tbody>
                </table>

                <a href="{{URL::route('admin.shippers.edit', $result->uuid)}}" class="btn btn-primary {{!$permission_accept_update ? 'disabled' : ''}}">Sửa thông tin</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
