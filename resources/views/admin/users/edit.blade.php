@extends('admin.layouts.app')

@section('content')

@if(!$permission_accept_update)
    @include('admin.errors.permission')
@endif

@if($permission_accept_update)
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">Nhân viên {{$result->username}} ( {{$result->email}} )</div>

        <div class="panel-body">

            <div class="row">

                {!! Form::model($result, array('method' => 'put',

                                              'class' => 'form-horizontal',

                                              'route' => array('admin.users.update', $result->uuid))) !!}
                    {!! csrf_field() !!}

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Tên</label>

                        <div class="col-md-6">
                            {{Form::text('name',null,array('class' => 'form-control'))}}
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">E-Mail Address</label>

                        <div class="col-md-6">
                            {{Form::text('email',null,array('class' => 'form-control'))}}
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

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
                        <label class="col-md-4 control-label">Trạng thái</label>

                        <div class="col-md-6">
                            {{ Form::radio('active', 1) }} Hoạt động
                            {{ Form::radio('active', 0) }} Không
                        </div>
                    </div>

                    <!-- <div class="form-group">
                        <label class="col-md-4 control-label">Admin</label>

                        <div class="col-md-6">
                            {{ Form::radio('is_admin', 1) }} Có
                            {{ Form::radio('is_admin', 0) }} Không
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Root</label>

                        <div class="col-md-6">
                            {{ Form::radio('is_root', 1) }} Có
                            {{ Form::radio('is_root', 0) }} Không
                        </div>
                    </div> -->

                    <?php
                        $permissions = unserialize(Auth::user()->permissions);
                        $permissions = $permissions['users']['permission'];
                    ?>
                    @if($permission_accept_permission)
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
                                            @if(isset($result->permissions[$key][$key_temp]))
                                                {{ Form::checkbox('permissions['.$key.']['.$key_temp.']', true, $result->permissions[$key][$key_temp]) }}
                                            @else
                                                {{ Form::checkbox('permissions['.$key.']['.$key_temp.']', true, true) }}
                                            @endif
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
