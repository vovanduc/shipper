@extends('admin.layouts.app')

@section('content')
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
        		<div class="col-md-6">{{Lang::get('admin.package.index')}}</div>
            </div>
        </div>

        <div class="panel-body">
            {{ Form::open(array('route' => array('shipper.packages.search'), 'method' => 'GET')) }}
            <ul class="list-group">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-xs-6" >
                            <div class="left-inner-addon">
                                {{Form::select("status",\Package::get_status_option(),$status,array('class' => 'form-control select_auto', 'placeholder' => 'Tìm theo trạng thái'))}}
                            </div>
                        </div>
                        <div class="col-xs-6" >
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
                    </div>
                </li>
            </ul>
            {{ Form::close() }}

          	<table class="table table-hover table-responsive">
          		<thead>
          			<tr>
          				<th class="text-center">#</th>
          				<th class="text-center">Người gửi</th>
          				<th class="text-center">Người nhận</th>
          				<th class="text-center">Trạng thái</th>
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
        	        				<td>{{ $item->from_customer }}</td>
        	        				<td>{{ $item->customer }}</td>
        	        				<td>{!! $item->cv_status !!}</td>
        	        				<td>
    		        					<a href="{{URL::route('shipper.packages.show', $item->uuid)}}">
    		        						<i class="fa fa-search"></i> Xem
    		        					</a>
        	        			    </td>
          	        			</tr>
                                @if ($item->show_barcode)
                                <tr class="text-center">
                                    <td colspan="6" style="border-top:0px">

                                        @if($item->location)
                                            <button class="btn btn-info" type="button">
                                                {{$item->location->name}} <span class="badge">{{$item->location->quantity}}</span>
                                            </button><br/>
                                        @endif

                                        @if($item->parent)
                                            @if($item->uuid != $item->parent)
                                                Thuộc label {!!$item->package_parent->cv_label!!}<br/>
                                            @endif
                                            {!!$item->show_barcode!!}
                                            <br/>{{$item->label}}
                                        @else
                                            <b>Label gốc</b><br/>{{$item->label}}

                                            @if($item->packages_parent()->where('deleted',0)->get()->count() > 0)
                                                <br/><b>Label con</b> ({{$item->packages_parent()->where('deleted',0)->get()->count()}})
                                            @else
                                                <br/><b>Không có label con</b>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @endif
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
                {!! $result->appends(['status' => $status])->links() !!}
        </div>
    </div>
</div>
@endsection
