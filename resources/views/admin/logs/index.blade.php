@extends('admin.layouts.app')

@section('content')
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
        		<div class="col-md-6">{{Lang::get('admin.log.index')}}</div>
            </div>
        </div>

        <div class="panel-body">
            {{ Form::open(array('route' => array('admin.logs.search'), 'method' => 'POST')) }}
            <ul class="list-group">
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
          				<th class="text-center">Nhân viên</th>
          				<th class="text-center">Hành động</th>
          				<th class="text-center">Mô tả</th>
                        <th class="text-center">Khu vực</th>
          				<th class="text-center">Ngày truy cập</th>
          			</tr>
          		</thead>
          		<tbody>
                  @if($result->count())
            			@foreach( $result as $count => $item)
      	        			<tr class="text-center">
    	        				<td>{{ $item->user->name }}</td>
    	        				<td>{!! $item->getIconMarkup() !!}</td>
    	        				<td>{!! $item->getLinkedDescription() !!}</td>
                                <td>{{ $item->content_type }}</td>
    	        				<td>{{ $item->created_at }}</td>
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
            @if (!Request::is('admin/logs/search'))
              {!! $result->links() !!}
            @endif
        </div>
    </div>
</div>
@endsection
