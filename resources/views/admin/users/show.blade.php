@extends('admin.layouts.app')

@section('content')
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
                        <tr>
                            <td>Admin</td>
                            <td>{{$result->cv_is_admin}} </td>
                        </tr>
                        <tr>
                            <td>Root</td>
                            <td>{{$result->cv_is_root}} </td>
                        </tr>                   
                    </tbody>
                </table>
                  
                <a href="{{URL::route('admin.users.edit', $result->uuid)}}" class="btn btn-primary">Sửa thông tin</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
