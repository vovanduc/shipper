@extends('admin.layouts.app')

@section('content')
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">Thêm mới nhân viên</div>

        <div class="panel-body">

            <div class="row">
                {{ Form::open(array('route' => 'admin.users.store',

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

                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Username</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="username" value="{{ old('username') }}">

                            @if ($errors->has('username'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('username') }}</strong>
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

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Mật khẩu</label>

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

                    <?php
                        $permissions = unserialize(Auth::user()->permissions);
                        $permissions = $permissions['users']['permission'];
                    ?>
                    @if($permissions)
                    <div class="form-group">
                        <label class="col-md-4 control-label">Phân quyền</label>

                        <div class="col-md-6">
                            <ul class="list-group">
                                @foreach(\Config::get('lib.PERMISSIONS') as $key => $value)
                                    <li class="list-group-item list-group-item-info"><b>{{\Config::get('lib.MODULE.'.$key)}}</b></li>
                                    @foreach($value as $key_temp => $permision)
                                    <li class="list-group-item">
                                        {{Lang::get('admin.permissions.'.$key_temp)}}
                                        <span class="pull-right">
                                            {{ Form::checkbox('permissions['.$key.']['.$key_temp.']', true, true) }}
                                        </span>
                                    </li>
                                    @endforeach
                                @endforeach
                            </ul>

                        </div>
                    </div>
                    @endif

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
