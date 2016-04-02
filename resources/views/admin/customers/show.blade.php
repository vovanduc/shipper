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
                <div class="col-md-6">Khách hàng {{$result->username}} ( {{$result->email}} )</div>
                <div class="col-md-6"><span class="pull-right"><a href="{{URL::route('admin.customers.index')}}">Trở lại</a></span></div>
            </div>
        </div>

        <div class="panel-body">

            <div class="row">
                <div class="col-md-3 col-lg-3 " align="center">
                    <i class="fa fa-btn fa-user" style="font-size: 150px;"></i>
                </div>

                <div class=" col-md-9 col-lg-9 ">
                <table class="table table-user-information">
                    <tbody>
                        <tr>
                            <td>ID</td>
                            <td>{{$result->uuid}} </td>
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

                <a href="{{URL::route('admin.customers.edit', $result->uuid)}}" class="btn btn-primary">Sửa thông tin</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
