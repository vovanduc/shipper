@extends('admin.layouts.app')

@section('content')
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6">{{Lang::get('admin.location.index')}} {{$result->name}}</div>
                <div class="col-md-6"><span class="pull-right"><a href="{{URL::route('admin.locations.index')}}">Trở lại</a></span></div>
            </div>
        </div>

        <div class="panel-body">

            <div class="row">
                <div class=" col-md-12 col-lg-12 ">
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
                            <td>Số lượng</td>
                            <td>{{$result->quantity}} </td>
                        </tr>
                        <tr>
                            <td>Kiện hàng</td>
                            <td>{{$result->packages->count()}} </td>
                        </tr>
                        <tr>
                            <td>Ghi chú</td>
                            <td>{{$result->note}} </td>
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

                <a href="{{URL::route('admin.locations.edit', $result->uuid)}}" class="btn btn-primary">Sửa thông tin</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection