@extends('admin.layouts.app')

@section('content')
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6">{{Lang::get('admin.package.edit')}} ( {{$result->label}} )</div>
                <div class="col-md-6"><span class="pull-right"><a href="{{URL::route('admin.packages.index')}}">Trở lại</a></span></div>
            </div>
        </div>

        <div class="panel-body">

            <div class="row">

                {!! Form::model($result, array('method' => 'put',

                                              'class' => 'form-horizontal',

                                              'route' => array('admin.packages.update', $result->uuid))) !!}
                    {!! csrf_field() !!}

                    <div class="form-group{{ $errors->has('label') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Label</label>

                        <div class="col-md-6">
                            {{Form::text("label",null,array('class' => 'form-control'))}}
                            @if ($errors->has('label'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('label') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('shipper_id') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Người đi giao hàng</label>

                        <div class="col-md-6">
                            {{Form::select("shipper_id",$shippers,null,array('class' => 'form-control select_auto'))}}
                            @if ($errors->has('shipper_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('shipper_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Người nhận</label>

                        <div class="col-md-6">
                            {{Form::select("customer_id",$customers,null,array('class' => 'form-control select_auto'))}}
                            @if ($errors->has('customer_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('customer_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('invoice') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Invoice</label>

                        <div class="col-md-6">
                            {{Form::text('invoice',null,array('class' => 'form-control'))}}
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('service_type') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Service type</label>
                        <div class="col-md-6">
                            {{Form::text("service_type",null,array('class' => 'form-control'))}}
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('weight') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Weight</label>
                        <div class="col-md-6">
                            {{Form::text("weight",null,array('class' => 'form-control'))}}
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('kgs') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Kgs</label>
                        <div class="col-md-6">
                            {{Form::text("kgs",null,array('class' => 'form-control'))}}
                            @if ($errors->has('kgs'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('kgs') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('quantity') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Số lượng</label>

                        <div class="col-md-6">
                            {{Form::text("quantity",null,array('class' => 'form-control', 'disabled'))}}
                            @if ($errors->has('quantity'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('quantity') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Vị trí</label>

                        <div class="col-md-6">
                            {{Form::select("location_id",$location_id,null,array('class' => 'form-control select_auto'))}}
                            @if ($errors->has('location_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('location_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Địa chỉ</label>
                        <div class="col-md-6">
                            <b style="color: red">Vui lòng nhập đúng địa chỉ theo gợi ý của hệ thống</b>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                        <div class="col-md-12">
                            <input id="origin-input" class="controls" type="text" placeholder="Từ địa điểm" value="{{Config::get('constants.MAPS_ADDRESS')}}" disabled>
                            <input id="destination-input" class="controls" type="text" placeholder="Đến địa điểm" name="address" value="{{$result->address}}">
                            <div id="map" style="width: 825px;height: 500px;"></div>
                            @if ($errors->has('address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    {{Form::hidden('place_id',null,array('class' => 'form-control', 'id' => 'place_id'))}}

                    <div class="form-group{{ $errors->has('county') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Quận</label>

                        <div class="col-md-6">
                            {{Form::select("county",\Package::get_county_option(),null,array('class' => 'form-control select_auto'))}}
                            @if ($errors->has('county'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('county') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Nội dung</label>

                        <div class="col-md-6">
                            {{Form::textarea('content',null,array('class' => 'form-control', 'id' => 'ckeditor'))}}
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Điện thoại</label>
                        <div class="col-md-6">
                            {{Form::text("phone",null,array('class' => 'form-control'))}}
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('note') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Ghi chú</label>

                        <div class="col-md-6">
                            {{Form::textarea('note',null,array('class' => 'form-control'))}}
                        </div>
                    </div>

                    <!-- <div class="form-group{{ $errors->has('note') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Ngày giao hàng thành công</label>

                        <div class="col-md-6">

                            {{ Form::text('delivery_at',null, array('class' => 'datepicker')) }}

                            <span class="help-block">
                                <strong style="color:red">Lưu ý: ngày giao hàng thành công chỉ có hiệu lực khi có trạng thái là
                                <b>{{\Package::get_status_option(\Config::get('lib.PACKAGE.delivery_success'))}}</b></strong>
                            </span>
                        </div>
                    </div> -->

                    <div class="form-group">
                        <label class="col-md-4 control-label">Trạng thái</label>

                        <div class="col-md-6">
                            {{ Form::radio('status', 1, null) }} {{\Package::get_status_option(1)}}<br/>
                            {{ Form::radio('status', 2, null) }} {{\Package::get_status_option(2)}}<br/>
                            {{ Form::radio('status', 3, null) }} {{\Package::get_status_option(3)}}<br/>
                            {{ Form::radio('status', 4, null) }} {{\Package::get_status_option(4)}}<br/>
                            {{ Form::radio('status', 5, null) }} {{\Package::get_status_option(5)}}<br/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Cập nhật
                            </button>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
    $(document).ready(function() {
        @if($result->delivery_at)
            $(".datepicker").datepicker('setDate', '{{$result->delivery_at}}');
        @endif
    });
</script>
{!! \Html::script('assets/admin/javascript/google_map.js') !!}
@stop
