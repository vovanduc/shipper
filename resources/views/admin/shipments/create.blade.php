@extends('admin.layouts.app')

@section('content')

@if(!$permission_accept_add)
    @include('admin.errors.permission')
@endif

@if($permission_accept_add)
<div class="col-md-9">
    <div class="panel panel-default">
      <div class="panel-heading">
          <div class="row">
              <div class="col-md-6">{{Lang::get('admin.shipment.index')}}</div>
              <div class="col-md-6"><span class="pull-right"><a href="{{URL::route('admin.shipments.index')}}">Trở lại</a></span></div>
          </div>
      </div>

        <div class="panel-body">

            <div class="row">
                {{ Form::open(array('route' => 'admin.shipments.store',

                    'class' => 'form-horizontal'))}}

                    {!! csrf_field() !!}

                    <div class="form-group{{ $errors->has('key') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Mã lô hàng</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="key" value="{{ old('key') }}">

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
                            <textarea type="text" class="form-control" name="note" value="{{ old('note') }}"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Thêm
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
