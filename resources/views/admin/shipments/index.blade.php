@extends('admin.layouts.app')

@section('content')

@if(!$permission_accept_index)
    @include('admin.errors.permission')
@endif

@if($permission_accept_index)
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
        		    <div class="col-md-6">{{Lang::get('admin.shipment.index')}}</div>
                <div class="col-md-6"><span class="pull-right"><a href="{{URL::route('admin.shipments.create')}}" class="btn {{!$permission_accept_add ? 'disabled' : ''}}">Thêm</a></span></div>
            </div>
        </div>

        <div class="panel-body">

          	<table class="table table-hover table-responsive">
          		<thead>
          			<tr>
          				<th class="text-center">#</th>
          				<th class="text-center">Mã</th>
          				<th class="text-center">Số lượng kiện hàng</th>
          				<th class="text-center">Trạng thái</th>
          				<th class="text-center">Ngày tạo</th>
          				<th class="text-center">Quản lý</th>
          			</tr>
          		</thead>
          		<tbody>
                  @if($result->count())
                			@foreach( $result as $count => $item)
          	        			<tr class="text-center">
            	        				<th></th>
            	        				<td>{{ $item->key }}</td>
                                        <td>{{ $item->packages()->count() }}</td>
            	        				<td>{!! $item->cv_active !!}</td>
            	        				<td>{{ $item->created_at }}</td>
            	        				<td>
        		        					<a href="{{URL::route('admin.shipments.show', $item->uuid)}}" class="btn {{!$permission_accept_show ? 'disabled' : ''}}">
        		        						<i class="fa fa-search"></i> Xem
        		        					</a>
        		        					<a href="{{URL::route('admin.shipments.edit', $item->uuid)}}" class="btn {{!$permission_accept_update ? 'disabled' : ''}}">
        		        						<i class="fa fa-pencil"></i> Sửa
        									</a>
        									{!! Form::open(array('route' => array('admin.shipments.destroy', $item->uuid), 'method' => 'delete')) !!}
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
            	        			  </td>
          	        			</tr>
                			@endforeach
                  @else
                      <tr class="text-center">
                          <td colspan="6">
                              {{Lang::get('admin.global.no_data')}}
                          </td>
                      </tr>
                  @endif
          		</tbody>
          	</table>
            @if (!Request::is('admin/shipments/search'))
              {!! $result->links() !!}
            @endif
        </div>
    </div>
</div>
@endif
@endsection
