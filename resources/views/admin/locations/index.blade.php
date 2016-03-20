@extends('admin.layouts.app')

@section('content')
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
        		    <div class="col-md-6">{{Lang::get('admin.location.index')}}</div>
                <div class="col-md-6"><span class="pull-right"><a href="{{URL::route('admin.locations.create')}}">Thêm</a></span></div>
            </div>
        </div>

        <div class="panel-body">

          	<table class="table table-hover table-responsive">
          		<thead>
          			<tr>
          				<th class="text-center">#</th>
          				<th class="text-center">Tên</th>
          				<th class="text-center">Số lượng</th>
                        <th class="text-center">Kiện hàng</th>
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
            	        				<td>{{ $item->name }}</td>
            	        				<td>{{ $item->quantity }}</td>
                                        <td>{{ $item->packages->count() }}</td>
            	        				<td>{!! $item->cv_active !!}</td>
            	        				<td>{{ $item->created_at }}</td>
            	        				<td>
            	        					  @if (\Auth::user()->is_admin)
            		        					<a href="{{URL::route('admin.locations.show', $item->uuid)}}">
            		        						<i class="fa fa-search"></i> Xem
            		        					</a>
            		        					<a href="{{URL::route('admin.locations.edit', $item->uuid)}}">
            		        						<i class="fa fa-pencil"></i> Sửa
            									    </a>
                									<!-- {!! Form::open(array('route' => array('admin.locations.destroy', $item->uuid), 'method' => 'delete')) !!}
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
                									{!! Form::close() !!} -->
            								      @endif
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
            @if (!Request::is('admin/locations/search'))
              {!! $result->links() !!}
            @endif
        </div>
    </div>
</div>
@endsection
