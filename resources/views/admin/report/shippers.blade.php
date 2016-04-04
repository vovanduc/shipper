@extends('admin.layouts.app')

@section('content')

@if(!$permission_accept_shippers)
    @include('admin.errors.permission')
@endif

@if($permission_accept_shippers)
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">Báo cáo theo người giao hàng</div>
        <div class="panel-body">
            {{ Form::open(array('route' => array('admin.reports.shippers'), 'method' => 'get')) }}
            <ul class="list-group">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-xs-6" >
                            <div class="left-inner-addon">
                                Chọn nhân viên giao hàng
                            </div>
                        </div>
                        <div class="col-xs-6" >
                            <div class="left-inner-addon">
                                Theo thời gian
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6" >
                            <div class="left-inner-addon">
                                {{Form::select("shipper",$shippers,$shipper,array('class' => 'form-control select_auto', 'placeholder' => 'Chọn tất cả'))}}
                            </div>
                        </div>
                        <div class="col-xs-6" >
                                {{Form::select("time",$times,$time,array('class' => 'form-control select_auto', 'placeholder' => 'Chọn tất cả'))}}
                        </div>
                    </div>
                </li>
            {{ Form::close() }}
            <br/>
            <div class="table-responsive">
				<table class="table table-condensed">
					<thead>
                        <tr>
							<td class="text-center"><strong>Tên</strong></td>
							<td class="text-center"><strong>Kiện hàng</strong></td>
							<td class="text-center"><strong>Tiền</strong></td>
                        </tr>
					</thead>
					<tbody>
                        <?php $total = 0;?>
						@foreach($result as $item)
                            <?php

                                $packages = $item->packages()->where('status', \Config::get('lib.PACKAGE.delivery_success'));

                                if ($time > 0) {
                                    $packages = $packages->where('delivery_at','>=',$date[0])->where('delivery_at','<=',$date[1]);
                                }

                                $total += $packages->sum('price');
                            ?>
    						<tr>
    							<td class="text-center">{{$item->name}}</td>
    							<td class="text-center">{{$packages->count()}}</td>
    							<td class="text-center">
                                    @if($packages->sum('price') > 0)
                                        {{\Currency::format($packages->sum('price'))}}
                                    @else
                                        0 VND
                                    @endif
                                </td>
    						</tr>
                        @endforeach
                        <tr>
                            <td class="no-line"></td>
                            <td class="no-line text-center"><strong>Tổng tiền</strong></td>
                            <td class="no-line text-center">{{\Currency::format($total)}}</td>
                        </tr>
					</tbody>
				</table>
			</div>
        </div>
    </div>

</div>
@endif
@endsection

@section('javascript')
    <script>
        $(function() {
            $('.select_auto').change(function() {
                this.form.submit();
            });
        });
    </script>
@endsection
