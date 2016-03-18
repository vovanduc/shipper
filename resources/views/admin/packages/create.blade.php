@extends('admin.layouts.app')

@section('content')
<div class="col-md-9">
    <div class="panel panel-default">
      <div class="panel-heading">
          <div class="row">
              <div class="col-md-6">{{Lang::get('admin.package.add')}}</div>
              <div class="col-md-6"><span class="pull-right"><a href="{{URL::route('admin.packages.index')}}">Trở lại</a></span></div>
          </div>
      </div>

        <div class="panel-body">

            <div class="row">
                {{ Form::open(array('route' => 'admin.packages.store',

                    'class' => 'form-horizontal'))}}

                    {!! csrf_field() !!}

                    <div class="form-group{{ $errors->has('label') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Label</label>

                        <div class="col-md-6">
                            <b>Tự động tạo khi thêm kiện hàng</b>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('customer_id_from') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Người gửi</label>

                        <div class="col-md-6">
                            {{Form::select("customer_id_from",$customer_id_from,null,array('class' => 'form-control select_auto'))}}

                            @if ($errors->has('customer_id_from'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('customer_id_from') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('customer_id_to') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Người nhận</label>

                        <div class="col-md-6">
                            {{Form::select("customer_id_to",$customer_id_to,null,array('class' => 'form-control select_auto'))}}

                            @if ($errors->has('customer_id_to'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('customer_id_to') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('quantity') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Số lượng</label>
                        <div class="col-md-6">
                            {{Form::text("quantity",1,null,array('class' => 'form-control'))}}
                            @if ($errors->has('quantity'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('quantity') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Địa chỉ</label>

                        <div class="col-md-6">
                            <b style="color: red">Vui lòng nhập đúng địa chỉ theo gợi ý của hệ thống</b>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                        <div class="col-md-12">
                            <input id="origin-input" class="controls" type="text" placeholder="Từ địa điểm" value="{{Config::get('constants.MAPS_ADDRESS')}}" disabled>
                            <input id="destination-input" class="controls" type="text" placeholder="Đến địa điểm" name="address">
                            <div id="map" style="width: 825px;height: 500px;"></div>
                            @if ($errors->has('address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <input type="hidden" class="form-control" name="place_id" id="place_id">

                    <div class="form-group{{ $errors->has('county') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Quận</label>

                        <div class="col-md-6">
                            {{Form::select("county",\Package::get_county_option(),null,array('class' => 'form-control select_auto'))}}

                            @if ($errors->has('county'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('county') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Nội dung</label>
                        <div class="col-md-6">
                            <textarea id="ckeditor" type="text" class="form-control" name="content" value="{{ old('content') }}"></textarea>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('note') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Ghi chú</label>
                        <div class="col-md-6">
                            <textarea type="text" class="form-control" name="note" value="{{ old('note') }}"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Thêm
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
{!! \Html::script('assets/admin/javascript/google_map.js') !!}
@stop
