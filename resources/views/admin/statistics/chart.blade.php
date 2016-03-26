@extends('admin.layouts.app')

@section('content')
<style>
    .badge_mini{
        font-size: 20px;
    }
</style>
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">Thống kê biểu đồ kiện hàng theo tháng</div>
        <div class="panel-body">

            @columnchart('Finances', 'pop_div')
            <div id="pop_div"></div>
        </div>
    </div>

</div>
@endsection
