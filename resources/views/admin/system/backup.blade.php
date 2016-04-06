@extends('admin.layouts.app')

@section('content')

<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">Sao lưu dữ liệu</div>
        <div class="panel-body">

            @if($files)
            <ul class="list-group">
                @foreach($files as $item)
                    <?php
                        $timestamp = \Storage::lastModified($item);
                        $time = Carbon::now()->timestamp($timestamp)->format('H:i d-m-Y');
                    ?>
                    <li class="list-group-item">
                        {{$item}}
                        <span class="pull-right">
                            {{\Storage::size($item)}} Byte <b>( {{$time}} )</b>
                        </span>
                    </li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
</div>

@endsection
