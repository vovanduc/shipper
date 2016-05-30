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
      							<td class="text-center">

                                </td>
                                <td class="text-center"><strong>Kg</strong></td>
      							<td class="text-center"><strong>Tiền</strong></td>
                            </tr>
    					</thead>
					<tbody>
                        <?php
                            $total = 0;
                            $total_kg = 0;
                        ?>
						@foreach($result as $item)

                        @endforeach

                        <tr>
                          <td class="no-line"></td>
                          <td class="no-line text-center"><strong>Tổng cộng</strong></td>
                          <td class="no-line text-center"><b>{{$total_kg}}</b></td>
                          <td class="no-line text-center"><b>{{\Currency::format($total)}}</b></td>
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
