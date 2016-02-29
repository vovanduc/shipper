@extends('admin.layouts.app')

@section('content')
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">Thay đổi mật khẩu</div>

        <div class="panel-body">

                <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/change_pass') }}">
                    {!! csrf_field() !!}

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Mật khẩu mới</label>

                        <div class="col-md-6">
                            <input type="password" class="form-control" name="password" value="{{ old('password') }}">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Xác nhận mật khẩu</label>

                        <div class="col-md-6">
                            <input type="password" class="form-control" name="password_confirmation">

                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-btn fa-key"></i>Thay đổi mật khẩu
                            </button>
                        </div>
                    </div>
                </form>
        </div>
    </div>
</div>
@endsection
