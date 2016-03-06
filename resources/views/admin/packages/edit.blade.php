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

                    <div class="form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Khách hàng</label>

                        <div class="col-md-6">
                            {{Form::select("customer_id",$customers,null,array('class' => 'form-control select_auto'))}}
                            @if ($errors->has('customer_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('customer_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('shipper_id') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Người vận chuyển</label>

                        <div class="col-md-6">
                            {{Form::select("shipper_id",$shippers,null,array('class' => 'form-control select_auto'))}}
                            @if ($errors->has('shipper_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('shipper_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Địa chỉ</label>

                        <div class="col-md-6">
                            {{Form::text('address',null,array('class' => 'form-control'))}}
                            @if ($errors->has('address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

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

                    <div class="form-group{{ $errors->has('note') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Ghi chú</label>

                        <div class="col-md-6">
                            {{Form::text('note',null,array('class' => 'form-control'))}}
                        </div>
                    </div>

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
