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
                                Báo cáo theo tháng
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6" >
                            <div class="left-inner-addon">
                                <?php
                                    $months = array();
                                    $months[0] = 'Chọn tháng';
                                    for ($i=1; $i <=12 ; $i++) {
                                        $months[$i] = 'Tháng '.$i;
                                    }
                                ?>
                                {{Form::select("month",$months,$month,array('class' => 'form-control select', 'placeholder' => ''))}}
                            </div>
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
                                <td class="text-right"><strong>Kiện hàng</strong></td>
                                <td class="text-right"><strong>Kg</strong></td>
      							<td class="text-right"><strong>Tiền</strong></td>
                            </tr>
    					</thead>
					<tbody>
                        <?php
                            $total = 0;
                            $total_kg = 0;
                            $total_count = 0;
                        ?>
                        @foreach($result as $item)
                        <?php
                            $data = $item->packages()->whereStatus(\Config::get('lib.PACKAGE.delivery_success'))
                            ->whereMonth('delivery_at', '=', $month);
                            $total_kg += $data->sum('kgs');
                            $total += $data->sum('price');
                            $total_count += $data->count();
                        ?>
                        <tr>
                            <td class="text-center">{{$item->name}}</td>
                            <td class="text-right">{{$data->count()}}</td>
                            <td class="text-right">{{$data->sum('kgs')}}</td>
                            <td class="text-right">{{\Currency::format($data->sum('price'))}}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="no-line text-center"><strong>Tổng cộng</strong></td>
                            <td class="no-line text-right"><b>{{$total_count}}</b></td>
                            <td class="no-line text-right"><b>{{$total_kg}}</b></td>
                            <td class="no-line text-right"><b>{{\Currency::format($total)}}</b></td>
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
            $('.select').change(function() {
                this.form.submit();
            });
        });
    </script>
@endsection
