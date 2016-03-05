@extends('admin.layouts.app')

@section('content')
<div class="col-md-9">
    <div class="panel panel-default">
      <div class="panel-heading">
          <div class="row">
              <div class="col-md-6">{{Lang::get('admin.shipper.add')}}</div>
              <div class="col-md-6"><span class="pull-right"><a href="{{URL::route('admin.shippers.index')}}">Trở lại</a></span></div>
          </div>
      </div>

        <div class="panel-body">

            <div class="row">
                {{ Form::open(array('route' => 'admin.shippers.store',

                    'class' => 'form-horizontal'))}}

                    {!! csrf_field() !!}

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Tên</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}">

                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">E-Mail</label>

                        <div class="col-md-6">
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Điện thoại</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">

                            @if ($errors->has('phone'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Địa chỉ</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="address" value="{{ old('address') }}">

                            @if ($errors->has('address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                            @endif
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
@endsection
