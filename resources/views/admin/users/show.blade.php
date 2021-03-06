@extends('admin.layouts.app')

@section('content')

@if(!$permission_accept_show)
    @include('admin.errors.permission')
@endif

@if($permission_accept_show)
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">Nhân viên {{$result->username}} ( {{$result->email}} )</div>

        <div class="panel-body">

            <div class="row">
                <div class="col-md-3 col-lg-3 " align="center">
                    <i class="fa fa-btn fa-user" style="font-size: 150px;"></i>
                </div>

                <div class=" col-md-9 col-lg-9 ">
                <table class="table table-user-information">
                    <tbody>
                        <tr>
                            <td>ID</td>
                            <td>{{$result->uuid}} </td>
                        </tr>
                        <tr>
                            <td>Username</td>
                            <td>{{$result->username}} </td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td>
                                @if($result->password)
                                    ******
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Tên</td>
                            <td>{{$result->name}} </td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td>{{$result->email}} </td>
                        </tr>
                        <tr>
                            <td>Trạng thái</td>
                            <td>{!!$result->cv_active!!} </td>
                        </tr>
                        <tr>
                            <td>Ngày tạo</td>
                            <td>{{$result->created_at}} </td>
                        </tr>
                        <!-- <tr>
                            <td>Admin</td>
                            <td>{{$result->cv_is_admin}} </td>
                        </tr>
                        <tr>
                            <td>Root</td>
                            <td>{{$result->cv_is_root}} </td>
                        </tr> -->
                        <tr>
                            <td>Phân quyền</td>
                            <td>
                                <?php
                                    $array = \User::get_permissions($result->uuid);
                                ?>
                                @foreach($array as $key => $value)
                                    <li class="list-group-item list-group-item-info"><b>{{\Config::get('lib.MODULE.'.$key)}}</b></li>
                                    @foreach($value as $key_temp => $permision)
                                    <li class="list-group-item">
                                        {{Lang::get('admin.permissions.'.$key_temp)}}
                                        <span class="pull-right">
                                            @if($permision)
                                                Có
                                            @else
                                                Không
                                            @endif
                                        </span>
                                    </li>
                                    @endforeach
                                @endforeach
                            </td>
                        </tr>
                    </tbody>
                </table>
                @if (\Auth::user()->uuid == $result->uuid)
                    <a href="{{URL::route('admin.users.edit', $result->uuid)}}" class="btn btn-primary {{!$permission_accept_update ? 'disabled' : ''}}">Sửa thông tin</a>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
