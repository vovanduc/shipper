@extends('admin.layouts.app')

@section('content')
<style>
    .content {
        margin: 0;
        padding: 0;
        width: 100%;
        color: #B0BEC5;
        display: table;
        font-weight: 100;
        text-align: center;
        display: inline-block;
    }

    .title {
        font-size: 50px;
        margin-bottom: 40px;
    }
</style>
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
        		<div class="col-md-6">Tin từ hệ thống</div>
            </div>
        </div>

        <div class="panel-body">
            <div class="content">
                <div class="title">{{trans('admin.global.no_permission')}}</div>
            </div>
        </div>
    </div>
</div>
@endsection
