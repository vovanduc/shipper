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
                            <div class="left-inner-addon">
                                {{Form::select("user_id",$users,$user_id,array('class' => 'form-control select_auto', 'placeholder' => 'Tìm theo nhân viên'))}}
                            </div>
                        </div>
                    </div><br/>
                    <div class="row">
                        <div class="col-xs-6" >
                            <div class="left-inner-addon">
                                {{Form::select("action_id",$actions,$action_id,array('class' => 'form-control select_auto', 'placeholder' => 'Tìm theo hành động'))}}
                            </div>
                        </div>
                        <div class="col-xs-6" >
                            <div class="right-inner-addon">
                                {{Form::select("module_id",$modules,$module_id,array('class' => 'form-control select_auto', 'placeholder' => 'Tìm theo khu vực'))}}
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
                            <?php $user = \User::where('uuid', $item->user_id)->first(); ?>
      	        			<tr class="text-center">
    	        				<td>
                                    @if($user)
                                        <a target="_blank" href="{{URL::route('admin.users.show',$user->uuid)}}">{{ $user->name }}</a>
                                    @endif
                                </td>
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
