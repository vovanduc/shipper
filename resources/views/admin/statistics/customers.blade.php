@extends('admin.layouts.app')

@section('content')
<style>
    .badge_mini{
        font-size: 20px;
    }
</style>
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">Top 20 khách hàng vận chuyển kiện hàng nhiều nhất tháng 3</div>
        <div class="panel-body">

            <ul class="list-group">
                <?php $count = 1?>
                @foreach($list_customer as $item)
                <li class="list-group-item <?php if($count<=3) echo 'list-group-item-success'; ?>">
                    <strong>{{$count}}/</strong>
                    <span class="badge badge_mini">{{$item->packages()->count()}}</span>
                    {{$item->name}}
                </li>
                <?php $count++?>
                @endforeach
            </ul>

        </div>
    </div>

</div>
@endsection
