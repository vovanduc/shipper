@extends('admin.layouts.app')

@section('content')
<div class="col-md-12 text-center">
    <!-- <div class="panel panel-default">
        <div class="panel-heading">Welcome</div>

        <div class="panel-body">
            Your Application's Landing Page.
        </div>
    </div> -->
    <div class="col-md-6">
        <header class="jumbotron hero-spacer">
            <h4>Khu vực đăng nhập dành cho người quản lý hệ thống</h4><br/>
            <i class="fa fa-btn fa-group" style="font-size: 150px;"></i><br/><br/>
            <p><a class="btn btn-primary btn-large btn-block" href="{{URL::route('admin.home.index')}}">Truy cập</a></p>
        </header>
    </div>
    <div class="col-md-6">
        <header class="jumbotron hero-spacer">
            <h4>Khu vực đăng nhập dành cho người giao hàng</h4><br/>
            <i class="fa fa-btn fa-user" style="font-size: 150px;"></i><br/><br/>
            <p><a class="btn btn-primary btn-large btn-block" href="{{URL::route('shipper.auth.login')}}">Truy cập</a></p>
        </header>
    </div>
</div>
@endsection
