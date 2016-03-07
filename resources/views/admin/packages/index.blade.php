@extends('admin.layouts.app')

@section('content')
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
        		    <div class="col-md-6">{{Lang::get('admin.package.index')}}</div>
                <div class="col-md-6"><span class="pull-right"><a href="{{URL::route('admin.packages.create')}}">Thêm</a></span></div>
            </div>
        </div>

        <div class="panel-body">
            {{ Form::open(array('route' => array('admin.packages.search'), 'method' => 'POST')) }}
            <ul class="list-group">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-xs-6" >
                            <div class="left-inner-addon">
                                {{Form::select("customer_id",$customers,$customer_id,array('class' => 'form-control select_auto', 'placeholder' => 'Tìm theo khách hàng'))}}
                            </div>
                        </div>
                        <div class="col-xs-6" >
                            <div class="right-inner-addon">
                                {{Form::select("shipper_id",$shippers,$shipper_id,array('class' => 'form-control select_auto', 'placeholder' => 'Tìm theo người vận chuyển'))}}
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-xs-6" >
                            <div class="left-inner-addon">
                                {{Form::select("status",\Package::get_status_option(),$status,array('class' => 'form-control select_auto', 'placeholder' => 'Tìm theo trạng thái'))}}
                            </div>
                        </div>
                        <div class="col-xs-6" >
                            <div class="left-inner-addon">
                                {{Form::select("county",\Package::get_county_option(),$county,array('class' => 'form-control select_auto', 'placeholder' => 'Tìm theo quận'))}}
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-xs-12" >
                            <div class="left-inner-addon">
                                <input type="text"  name="label" class="form-control" placeholder="Tìm theo label" value="{{ $label }}"/>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                  <div class="row">
                      <div class="col-xs-12" >
                          <button type="submit" class="btn btn-primary btn-block">
                            Tìm kiếm
                          </button>
                      </div>
                </li>
            </ul>
            {{ Form::close() }}

          	<table class="table table-hover table-responsive">
          		<thead>
          			<tr>
          				<th class="text-center">#</th>
          				<th class="text-center">Người vận chuyển</th>
          				<th class="text-center">Khách hàng</th>
          				<th class="text-center">Trạng thái</th>
          				<th class="text-center">Ngày tạo</th>
          				<th class="text-center">Quản lý</th>
          			</tr>
          		</thead>
          		<tbody>
                  @if($result->count())
                			@foreach( $result as $count => $item)
                          <?php
                              $item = Package::convert($item);
                          ?>
          	        			<tr class="text-center">
            	        				<th></th>
            	        				<td>{{ $item->shipper_id }}</td>
            	        				<td>{{ $item->customer_id }}</td>
            	        				<td>{!! $item->cv_status !!}</td>
            	        				<td>{{ $item->created_at }}</td>
            	        				<td>
            	        					  @if (\Auth::user()->is_admin)
            		        					<a href="{{URL::route('admin.packages.show', $item->uuid)}}">
            		        						<i class="fa fa-search"></i> Xem
            		        					</a>
            		        					<a href="{{URL::route('admin.packages.edit', $item->uuid)}}">
            		        						<i class="fa fa-pencil"></i> Sửa
            									    </a>
                									{!! Form::open(array('route' => array('admin.packages.destroy', $item->uuid), 'method' => 'delete')) !!}
                										<button Onclick="return ConfirmDelete();" type="submit" class="btn btn-xs blue btn-circle">

                											<i class="fa fa-trash-o"></i> Xóa

                										</button>
                										<script type="text/javascript">

                											function ConfirmDelete()
                										    {

                										      var x = confirm("{{Lang::get('admin.global.sure_delete')}}");

                										      if (x)

                										        return true;

                										      else

                										        return false;
                										    }

                										</script>
                									{!! Form::close() !!}
            								      @endif
            	        			  </td>
          	        			</tr>
                			@endforeach
                  @else
                      <tr class="text-center">
                          <td colspan="5">
                              {{Lang::get('admin.global.no_data')}}
                          </td>
                      </tr>
                  @endif
          		</tbody>
          	</table>
            @if (!Request::is('admin/packages/search'))
              {!! $result->links() !!}
            @endif
        </div>
    </div>
</div>
@endsection