@extends('admin.layouts.app')

@section('content')

@if(!$permission_accept_barcode)
    @include('admin.errors.permission')
@endif

@if($permission_accept_barcode)
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
                        <div class="col-md-4 col-lg-4 " align="center">
                            <ul class="list-group">
                                <li class="list-group-item list-group-item-info"><b>Vị trí</b></li>
                                <li class="list-group-item">
                                    @if($result->location)
                                        <button class="btn btn-info" type="button">
                                            {{$result->location->name}} <span class="badge">{{$result->location->quantity}}</span>
                                        </button><br/>
                                        Đang chứa: {{$result->location->packages->count()}}
                                    @else
                                        Chưa có
                                    @endif
                                </li>

                                <li class="list-group-item list-group-item-info"><b>Label</b></li>
                                <li class="list-group-item">
                                    {!!$result->show_barcode!!} <br/>
                                    {!!$result->cv_label!!} <br/>
                                    {!!$result->cv_status!!}
                                </li>

                                @if($result->parent)

                                    @if($result->package_parent->packages_parent()->where('deleted',0)->where('uuid','!=',$result->uuid)->get()->count() >0)
                                        <li class="list-group-item list-group-item-info"><b>Các label khác</b></li>
                                        @foreach($result->package_parent->packages_parent()->where('deleted',0)->where('uuid','!=',$result->uuid)->get() as $p)
                                            <li class="list-group-item">
                                                {!!$p->show_barcode!!} <br/>
                                                {!!$p->cv_label!!} <br/>
                                                {!!$p->cv_status!!}
                                            </li>
                                        @endforeach
                                    @endif

                                    @if($result->uuid != $result->parent)
                                        <li class="list-group-item list-group-item-info"><b>Thuộc label</b></li>
                                        <li class="list-group-item">
                                            {!!$result->package_parent->show_barcode!!} <br/>
                                            {!!$result->package_parent->cv_label!!}
                                        </li>
                                    @endif
                                @else
                                    @if($result->packages_parent()->where('deleted',0)->get()->count() > 0)
                                        <li class="list-group-item list-group-item-info"><b>Label con</b></li>
                                        @foreach($result->packages_parent()->where('deleted',0)->get() as $c)
                                            <li class="list-group-item">
                                                {!!$c->show_barcode!!} <br/>
                                                {!!$c->cv_label!!} <br/>
                                                {!!$c->cv_status!!}
                                            </li>
                                        @endforeach
                                    @else
                                        <br/><b>Không có label con</b>
                                    @endif
                                @endif

                            </ul>
                        </div>

                        <div class=" col-md-8 col-lg-8 ">
                            <table class="table table-user-information">
                                <tbody>
                                    <tr>
                                        <td>Invoice</td>
                                        <td>{{$result->invoice}} </td>
                                    </tr>
                                    <tr>
                                        <td>Thông tin kiện hàng</td>
                                        <td>{{$result->info}} </td>
                                    </tr>
                                    <tr>
                                        <td>Service type</td>
                                        <td>{{$result->service_type}} </td>
                                    </tr>
                                    <tr>
                                        <td>Người đi giao hàng</td>
                                        <td>{{$result->shipper}} </td>
                                    </tr>
                                    <tr>
                                        <td>Người nhận</td>
                                        <td>{{$result->customer}} </td>
                                    </tr>
                                    <tr>
                                        <td>Actual weight</td>
                                        <td>{{$result->weight}} </td>
                                    </tr>
                                    <tr>
                                        <td>LBS TO KGS</td>
                                        <td>{{$result->kgs}} </td>
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
                                        <td>Tỉnh/Thành phố</td>
                                        <td>{{$result->province}} </td>
                                    </tr>
                                    <tr>
                                        <td>Quận/Huyện</td>
                                        <td>{{$result->district}} </td>
                                    </tr>
                                    <!-- <tr>
                                        <td>Quận</td>
                                        <td>{{\Package::get_county_option($result->county)}} </td>
                                    </tr> -->
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
                                        <td>Điện thoại</td>
                                        <td>{!!$result->phone!!} </td>
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
@endif
@endsection
