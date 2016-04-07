@extends('admin.layouts.app')

@section('content')

@if(!$permission_accept_update)
    @include('admin.errors.permission')
@endif

@if($permission_accept_update)
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6">{{Lang::get('admin.shipment.edit')}} ( {{$result->key}} )</div>
                <div class="col-md-6"><span class="pull-right"><a href="{{URL::route('admin.shipments.index')}}">Trở lại</a></span></div>
            </div>
        </div>

        <div class="panel-body">

            <div class="row">

                {!! Form::model($result, array('method' => 'put',

                                              'class' => 'form-horizontal',

                                              'route' => array('admin.shipments.update', $result->uuid))) !!}
                    {!! csrf_field() !!}

                    <div class="form-group{{ $errors->has('key') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Mã lô hàng</label>

                        <div class="col-md-6">
                            {{Form::text('key',null,array('class' => 'form-control'))}}
                            @if ($errors->has('key'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('key') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('note') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Ghi chú</label>
                        <div class="col-md-6">
                            {{Form::textarea('note',null,array('class' => 'form-control'))}}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Cập nhật trạng thái cho tất cả <b style="color:red">{{$result->packages()->count()}}</b> kiện hàng</label>
                        <div class="col-md-6">
                            {{Form::select("status",\Package::get_status_option(),$status,array('class' => 'form-control select_auto', 'placeholder' => 'Chọn trạng thái'))}}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Trạng thái</label>

                        <div class="col-md-6">
                            {{ Form::radio('active', 1) }} Hoạt động
                            {{ Form::radio('active', 0) }} Không
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
@endif
@endsection
