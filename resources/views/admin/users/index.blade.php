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
        		<div class="col-md-6">Quản lý nhân viên</div>
        		<div class="col-md-6"><span class="pull-right"><a href="{{URL::route('admin.users.create')}}" class="btn {{!$permission_accept_add ? 'disabled' : ''}}">Thêm</a></span></div>
        	</div>
        </div>

        <div class="panel-body">
        	<table class="table table-hover table-responsive">
        		<thead>
        			<tr>
        				<th class="text-center">#</th>
        				<th class="text-center">Tên</th>
        				<th class="text-center">Email</th>
        				<th class="text-center">Trạng thái</th>
        				<th class="text-center">Ngày tạo</th>
        				<th class="text-center">Quản lý</th>
        			</tr>
        		</thead>
        		<tbody>
        			@foreach( $result as $count => $item)
	        			<tr class="text-center">
	        				<th>{{ ++$count }}</th>
	        				<td>{{ $item->name }}</td>
	        				<td>{{ $item->email }}</td>
	        				<td>{!! $item->cv_active !!}</td>
	        				<td>{{ $item->created_at }}</td>
	        				<td>

	        					<a href="{{URL::route('admin.users.show', $item->uuid)}}" class="btn {{!$permission_accept_show ? 'disabled' : ''}}">
	        						<i class="fa fa-search"></i> Xem
	        					</a>

		        					<a href="{{URL::route('admin.users.edit', $item->uuid)}}" class="btn {{!$permission_accept_update ? 'disabled' : ''}}">
		        						<i class="fa fa-pencil"></i> Sửa
									</a>
									{!! Form::open(array('route' => array('admin.users.destroy', $item->uuid), 'method' => 'delete')) !!}
										<button Onclick="return ConfirmDelete();" type="submit" class="btn btn-xs blue btn-circle {{!$permission_accept_delete ? 'disabled' : ''}}">

											<i class="fa fa-trash-o"></i> Xóa

										</button>
										<script type="text/javascript">

											function ConfirmDelete()
										    {

                                                @if(!$permission_accept_delete)
                                                    return false;
                                                @endif

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
        		</tbody>
        	</table>
        	{!! $result->links() !!}
        </div>
    </div>
</div>
@endif
@endsection
