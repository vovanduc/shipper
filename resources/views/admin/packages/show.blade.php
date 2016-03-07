@extends('admin.layouts.app')

@section('content')
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6">{{Lang::get('admin.package.info')}} ( {{$result->label}} )</div>
                <div class="col-md-6"><span class="pull-right"><a href="{{URL::route('admin.packages.index')}}">Trở lại</a></span></div>
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
                            <td style="width: 30%">Label</td>
                            <td style="width: 70%">{{$result->label}} </td>
                        </tr>
                        <tr>
                            <td>Khách hàng</td>
                            <td>{{$result->customer_id}} </td>
                        </tr>
                        <tr>
                            <td>Địa chỉ</td>
                            <td>{{$result->address}} </td>
                        </tr>
                        <tr>
                            <td>Quận</td>
                            <td>{{\Package::get_county_option($result->county)}} </td>
                        </tr>
                        <tr>
                            <td>Người vận chuyển</td>
                            <td>{{$result->shipper_id}} </td>
                        </tr>
                        <tr>
                            <td>Ngày đã giao hàng</td>
                            <td>
                                @if ($result->delivery_at && $result->status == 4)
                                    {{$result->delivery_at}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Ghi chú</td>
                            <td>{{$result->note}} </td>
                        </tr>
                        <tr>
                            <td>Trạng thái</td>
                            <td>{!!$result->cv_status!!} </td>
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

                <a href="{{URL::route('admin.packages.edit', $result->uuid)}}" class="btn btn-primary">Sửa thông tin</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection