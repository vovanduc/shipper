@extends('admin.layouts.app')

@section('content')

@if(!$permission_accept_add)
    @include('admin.errors.permission')
@endif

@if($permission_accept_add)
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
                            {{Form::text("label",null,array('class' => 'form-control'))}}
                            @if ($errors->has('label'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('label') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('customer_from') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Người gửi</label>

                        <div class="col-md-6">
                            {{Form::select("customer_from",$customers,null,array('class' => 'form-control select_auto', 'id' => 'customer_from'))}}

                            @if ($errors->has('customer_from'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('customer_from') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <a data-toggle="modal" data-target="#add_nguoigui" href="#">Thêm người gửi</a>
                            <!-- Modal -->
                            <div id="add_nguoigui" class="modal fade" tabindex="-1" data-width="800" style="display: none;margin-top: 100px;">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title">Thêm mới người gửi</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Tên</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="customer_name" value="">
                                                    <p>Có thể để trống và cập nhật sau</p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label">E-Mail</label>

                                                <div class="col-md-8">
                                                    <input type="email" class="form-control" name="customer_email" value="">
                                                    <p>Có thể để trống và cập nhật sau</p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Điện thoại</label>

                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="customer_phone" value="">
                                                    <p>Có thể để trống và cập nhật sau</p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Địa chỉ</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="customer_address" value="">
                                                    <p>Có thể để trống và cập nhật sau</p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-12" style="color: red">
                                                    <label class="show_errors"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" data-dismiss="modal" class="btn btn-default">Đóng</button>
                                    <button type="button" class="btn btn-primary" id="update_nguoigui">Lưu</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Người nhận</label>

                        <div class="col-md-6">
                            {{Form::select("customer_id",$customers,null,array('class' => 'form-control select_auto', 'id' => 'customer_id'))}}

                            @if ($errors->has('customer_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('customer_id') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <a data-toggle="modal" data-target="#add_nguoinhan" href="#">Thêm người nhận</a>
                            <!-- Modal -->
                            <div id="add_nguoinhan" class="modal fade" tabindex="-1" data-width="800" style="display: none;margin-top: 100px;">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title">Thêm mới người nhận</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Tên</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="customer_name_nguoinhan" value="">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label">E-Mail</label>

                                                <div class="col-md-8">
                                                    <input type="email" class="form-control" name="customer_email_nguoinhan" value="">
                                                    <p>Có thể để trống và cập nhật sau</p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Điện thoại</label>

                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="customer_phone_nguoinhan" value="">
                                                    <p>Có thể để trống và cập nhật sau</p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Địa chỉ</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="customer_address_nguoinhan" value="">
                                                    <p>Có thể để trống và cập nhật sau</p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-12" style="color: red">
                                                    <label class="show_errors_nguoinhan"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" data-dismiss="modal" class="btn btn-default">Đóng</button>
                                    <button type="button" class="btn btn-primary" id="update_nguoinhan">Lưu</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('invoice') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Invoice</label>
                        <div class="col-md-6">
                            {{Form::text("invoice",null,array('class' => 'form-control'))}}
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('shipment_id') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Thông tin lô hàng</label>
                        <div class="col-md-6">
                            {{Form::select("shipment_id",$shipments,null,array('class' => 'form-control select_auto'))}}
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Ngày tháng năm</label>
                        <div class="col-md-6">
                            {{Form::text("date",null,array('class' => 'form-control datepicker'))}}
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('service_type') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Service type</label>
                        <div class="col-md-6">
                            {{Form::text("service_type",null,array('class' => 'form-control'))}}
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('weight') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Weight</label>
                        <div class="col-md-6">
                            {{Form::text("weight",null,array('class' => 'form-control'))}}
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('kgs') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Kgs</label>
                        <div class="col-md-6">
                            {{Form::text("kgs",null,array('class' => 'form-control'))}}
                            @if ($errors->has('kgs'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('kgs') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- <div class="form-group{{ $errors->has('quantity') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Số lượng</label>
                        <div class="col-md-6">
                            {{Form::text("quantity",1,null,array('class' => 'form-control'))}}
                            @if ($errors->has('quantity'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('quantity') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div> -->

                    <div class="form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Vị trí</label>

                        <div class="col-md-6">
                            {{Form::select("location_id",$location_id,null,array('class' => 'form-control select_auto'))}}

                            @if ($errors->has('location_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('location_id') }}</strong>
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

                    <!-- <div class="form-group{{ $errors->has('county') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Quận</label>

                        <div class="col-md-6">
                            {{Form::select("county",\Package::get_county_option(),null,array('class' => 'form-control select_auto'))}}

                            @if ($errors->has('county'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('county') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div> -->

                    <div class="form-group">
                        <label class="col-md-4 control-label">Tỉnh/Thành phố</label>

                        <div class="col-md-6">
                            <?php
                                $province = \Province::lists("name","provinceid");
                            ?>
                            {{Form::select("province_id",$province,null,array('class' => 'form-control province select_auto', 'placeholder' => 'Chọn tỉnh/Thành phố'))}}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Quận/Huyện</label>

                        <div class="col-md-6">
                            {{Form::hidden('district_id',null,array('id' => 'get_district_id'))}}
                            {{Form::select("district_id",array(),null,array('class' => 'form-control ', 'placeholder' => 'Quận/Huyện', 'id' => 'district'))}}
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Nội dung</label>
                        <div class="col-md-6">
                            <textarea id="ckeditor" type="text" class="form-control" name="content" value="{{ old('content') }}"></textarea>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Điện thoại</label>
                        <div class="col-md-6">
                            {{Form::text("phone",null,array('class' => 'form-control'))}}
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
@endif
@endsection

@section('javascript')
<script>
    $(document).ready(function() {
        $(".datepicker").datepicker('setDate', new Date());

        $('#update_nguoigui').on('click', function(){
            var customer_name = $('input[name="customer_name"]').val();
            var customer_email = $('input[name="customer_email"]').val();
            var customer_phone = $('input[name="customer_phone"]').val();
            var customer_address = $('input[name="customer_address"]').val();
            $('.show_errors').html('Chờ trong giây lát ...');

            $.ajax({
                "url":"{{\URL::route("admin.system.add_customer")}}",
                "type":"GET",
                "data":{"customer_name":customer_name, "customer_email":customer_email, "customer_address":customer_address},
                "success":function(data)
                {
                    if(data.error)
                    {
                        $('.show_errors').html(data.error);
                    }
                    if(data.success) {
                        $('.show_errors').html('');
                        $('#add_nguoigui').modal('hide');
                        $('#add_nguoinhan').modal('hide');
                        $('input[name="customer_name"]').val('');
                        $('input[name="customer_email"]').val('');
                        $('input[name="customer_phone"]').val('');
                        $('input[name="customer_address"]').val('');
                        var option = [{ id: data.customer.uuid, text: data.customer.name }];
                        $("#customer_from").select2({
                          data: option
                        });
                        $("#customer_from").select2().val(data.customer.uuid).trigger("change");
                        return false;
                    }
                }
            })
        });

        // ################################################
        $('#update_nguoinhan').on('click', function(){
            var customer_name = $('input[name="customer_name_nguoinhan"]').val();
            var customer_email = $('input[name="customer_email_nguoinhan"]').val();
            var customer_phone = $('input[name="customer_phone_nguoinhan"]').val();
            var customer_address = $('input[name="customer_address_nguoinhan"]').val();
            $('.show_errors_nguoinhan').html('Chờ trong giây lát ...');

            $.ajax({
                "url":"{{\URL::route("admin.system.add_customer")}}",
                "type":"GET",
                "data":{"customer_name":customer_name, "customer_email":customer_email, "customer_address":customer_address},
                "success":function(data)
                {
                    if(data.error)
                    {
                        $('.show_errors_nguoinhan').html(data.error);
                    }
                    if(data.success) {
                        $('.show_errors').html('');
                        $('#add_nguoigui').modal('hide');
                        $('#add_nguoinhan').modal('hide');
                        $('input[name="customer_name_nguoinhan"]').val('');
                        $('input[name="customer_email_nguoinhan"]').val('');
                        $('input[name="customer_phone_nguoinhan"]').val('');
                        $('input[name="customer_address_nguoinhan"]').val('');
                        var option = [{ id: data.customer.uuid, text: data.customer.name }];
                        $("#customer_id").select2({
                          data: option
                        });
                        $("#customer_id").select2().val(data.customer.uuid).trigger("change");
                        return false;
                    }
                }
            })
        });
    });
</script>
{!! \Html::script('assets/admin/javascript/google_map.js') !!}
@stop
