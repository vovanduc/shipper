@extends('admin.layouts.app')

@section('content')
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
        		    <div class="col-md-6">Quản lý khách hàng</div>
                <div class="col-md-6"><span class="pull-right"><a href="{{URL::route('admin.customers.create')}}">Thêm</a></span></div>
            </div>
        </div>

        <div class="panel-body">
            {{ Form::open(array('route' => array('admin.customers.search'), 'method' => 'POST')) }}
            <ul class="list-group">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-xs-6" >
                            <div class="left-inner-addon">
                                <input type="text"  name="name" class="form-control" placeholder="Tên" value="{{ $name }}"/>
                            </div>
                        </div>
                        <div class="col-xs-6" >
                            <div class="right-inner-addon">
                                <input type="text" name="email" class="form-control" placeholder="Email" value="{{ $email }}"/>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-xs-6" >
                            <div class="left-inner-addon">
                                <input type="text"  name="phone" class="form-control" placeholder="Điện thoại" value="{{ $phone }}"/>
                            </div>
                        </div>
                        <div class="col-xs-6" >
                            <div class="right-inner-addon">
                                <input type="text" name="address" class="form-control" placeholder="Địa chỉ" value="{{ $address }}"/>
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
          				<th class="text-center">Tên</th>
          				<th class="text-center">Email</th>
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
            	        				<td>{{ $item->email }}</td>
            	        				<td>{!! $item->cv_active !!}</td>
            	        				<td>{{ $item->created_at }}</td>
            	        				<td>
            	        					  @if (\Auth::user()->is_admin)
            		        					<a href="{{URL::route('admin.customers.show', $item->uuid)}}">
            		        						<i class="fa fa-search"></i> Xem
            		        					</a>
            		        					<a href="{{URL::route('admin.customers.edit', $item->uuid)}}">
            		        						<i class="fa fa-pencil"></i> Sửa
            									    </a>
                									{!! Form::open(array('route' => array('admin.customers.destroy', $item->uuid), 'method' => 'delete')) !!}
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
            @if (!Request::is('admin/customers/search'))
              {!! $result->links() !!}
            @endif
        </div>
    </div>
</div>
@endsection
