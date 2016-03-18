@extends('admin.layouts.app')

@section('content')
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
        		<div class="col-md-6">Quét mã vạch</div>
            </div>
        </div>

        <div class="panel-body">
            {{ Form::open(array('route' => array('admin.packages.barcode'), 'method' => 'get')) }}
            <ul class="list-group">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-xs-6" >
                            <div class="left-inner-addon">
                                Từ trạng thái
                            </div>
                        </div>
                        <div class="col-xs-6" >
                            <div class="left-inner-addon">
                                Đến trạng thái
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6" >
                            <div class="left-inner-addon">
                                {{Form::select("status_from",\Package::get_status_option(),$status_from,array('class' => 'form-control select_auto', 'placeholder' => 'Chọn trạng thái'))}}
                            </div>
                        </div>
                        <div class="col-xs-6" >
                            <div class="left-inner-addon">
                                {{Form::select("status_to",\Package::get_status_option(),$status_to,array('class' => 'form-control select_auto', 'placeholder' => 'Chọn trạng thái'))}}
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-xs-6" >
                            <div class="left-inner-addon">
                                <div class="checkbox">
                                    <label>{{\Form::checkbox('info', 1, \Request::query('info'))}}Hiển thị thông tin chi tiết kiện hàng</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-xs-12" >
                            <button type="submit" class="btn btn-primary btn-block">
                            Chọn trạng thái
                            </button>
                        </div>
                    </div>
                </li>
                @if($status_from && $status_to)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-xs-12" >
                                <input type="text"  name="label" class="form-control input-lg" placeholder="Rê chuột vào ô này trước khi quét mã vạch" value=""/>
                            </div>
                        </div>
                    </li>
                @endif
            </ul>
            {{ Form::close() }}
        </div>
    </div>
    @if($status_from && $status_to && \Request::query('label'))
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
        		<div class="col-md-6">Thông tin kiện hàng: <b>{{\Request::query('label')}}</b></div>
            </div>
        </div>

        <div class="panel-body">
            @if($result)
                @if(\Request::query('info'))
                    <div class="alert alert-success">
                        {!!$message!!}
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-lg-3 " align="center">
                            <i class="fa fa-btn fa-user" style="font-size: 150px;"></i>
                        </div>

                        <div class=" col-md-9 col-lg-9 ">
                            <table class="table table-user-information">
                                <tbody>
                                    <tr>
                                        <td colspan="2">
                                            {!!$result->show_barcode!!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%">Label</td>
                                        <td style="width: 70%">{{$result->label}} </td>
                                    </tr>
                                    <tr>
                                        <td>Người gửi</td>
                                        <td>{{$result->customer_id_from}} </td>
                                    </tr>
                                    <tr>
                                        <td>Người nhận</td>
                                        <td>{{$result->customer_id_to}} </td>
                                    </tr>
                                    <tr>
                                        <td>Số lượng</td>
                                        <td>{{$result->quantity}} </td>
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
                                    <!-- <tr>
                                        <td>Ước tính giá vận chuyển</td>
                                        <td>{{$result->cv_price}} </td>
                                    </tr> -->
                                    <tr>
                                        <td>Ước tính khoảng cách</td>
                                        <td>{{$result->cv_distance}} </td>
                                    </tr>
                                    <tr>
                                        <td>Ước tính thời gian vận chuyển</td>
                                        <td>{{$result->cv_duration}} </td>
                                    </tr>
                                    <tr>
                                        <td>Ngày đã giao hàng</td>
                                        <td>
                                            @if ($result->delivery_at && $result->status == 5)
                                                {{$result->delivery_at}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Nội dung</td>
                                        <td>{!!$result->content!!} </td>
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
                        </div>
                    </div>
                @else
                    <div class="alert alert-success">
                        {!!$message!!}
                    </div>
                @endif
            @else
                <b style="color:red">Dữ liệu không tồn tại</b>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection