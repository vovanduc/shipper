<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Shipper</title>

    <!-- Fonts -->
    {!! \Html::style('assets/admin/css/font-awesome.min.css') !!}
    <!-- <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'> -->

    <!-- Styles -->
    {!! \Html::style('assets/admin/css/bootstrap.min.css') !!}
    {!! \Html::style('assets/admin/css/select2.min.css') !!}
    {!! \Html::style('assets/admin/css/jquery-ui.css') !!}
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }

        #map {
            height: 100%;
          }
          .controls {
            margin-top: 10px;
            border: 1px solid transparent;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            height: 32px;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
          }

          #origin-input,
          #destination-input {
            background-color: #fff;
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
            margin-left: 12px;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 200px;
          }

          #destination-input {
            width: 550px !important;
          }

          #origin-input:focus,
          #destination-input:focus {
            border-color: #4d90fe;
          }

          #mode-selector {
            color: #fff;
            background-color: #4d90fe;
            margin-left: 12px;
            padding: 5px 11px 0px 11px;
          }

          #mode-selector label {
            font-family: Roboto;
            font-size: 13px;
            font-weight: 300;
          }
    </style>
    <script>
        var MAPS_PLACE_ID = "<?php echo Config::get('constants.MAPS_PLACE_ID')?>";
    </script>
</head>
<body id="app-layout">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    Quản lý vận chuyển kiện hàng
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/admin') }}"><b style="color:red">Phiên bản 1.2.0</b></a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::user() && Request::is('admin*'))
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{URL::route('admin.users.change_pass')}}"><i class="fa fa-btn fa-user"></i>Thay đổi mật khẩu</a></li>
                                <li><a href="{{URL::route('admin.users.show', Auth::user()->uuid)}}"><i class="fa fa-btn fa-user"></i>Cập nhật thông tin</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Đăng xuất</a></li>
                            </ul>
                        </li>
                    @endif
                    @if (Auth::guard('shippers')->user() && Request::is('shipper*'))
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::guard('shippers')->user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <!-- <li><a href="{{URL::route('admin.users.change_pass')}}"><i class="fa fa-btn fa-user"></i>Thay đổi mật khẩu</a></li> -->
                                <li><a href="{{ url('/shipper/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Đăng xuất</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @if (Session::has('message_success'))
            <div class="col-md-12">
                <div class="alert alert-success">
                    <strong>{!! Session::get('message_success') !!}</strong>
                </div>
            </div>
        @endif

        @if (Session::has('message_danger'))
            <div class="col-md-12">
                <div class="alert alert-danger">
                    <strong>{!! Session::get('message_danger') !!}</strong>
                </div>
            </div>
        @endif

        @if (Auth::user() && Request::is('admin*'))
            @include('admin.layouts.menu')
        @endif

        @if (Auth::guard('shippers')->user() && Request::is('shipper*'))
            @include('shipper.layouts.menu')
        @endif

        @yield('content')
    </div>

    <!-- JavaScripts -->
    {!! \Html::script('assets/admin/javascript/jquery-1.12.0.min.js') !!}
    {!! \Html::script('assets/admin/javascript/jquery-ui.js') !!}
    {!! \Html::script('assets/admin/javascript/bootstrap.min.js') !!}
    {!! \Html::script('assets/admin/javascript/select2.min.js') !!}
    <script src="//cdn.ckeditor.com/4.5.7/basic/ckeditor.js"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select_auto').select2();
            $('.datepicker').datepicker({
                showButtonPanel: true,
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-mm-yy'
            });

            if($( ".province" ).val()) {
                get_district($( ".province" ).val());
            }

            $( ".province" ).change(function()
            {
                var value = $( this ).val();
                if (value) {
                    get_district(value);
                }
            });

            function get_district(value) {

                var count_packages = '';
                if($( "#district_count_packages" ).val()) {
                    count_packages = 1;
                }

                $.ajax({
                    "url":"{{\URL::route("admin.system.get_district")}}",
                    "type":"get",
                    "data":{"id":value, "count_packages":count_packages},
                    "success":function(data)
                    {
                        if(data.value)
                        {
                            $('#district').find('option').remove();
                            $.each(data.value, function (key, item) {
                                $('#district').append($('<option>', {
                                    value: key,
                                    text : item
                                }));
                            });
                            if ($( "#get_district_id" ).val()) {
                                $('#district option[value='+$( "#get_district_id" ).val()+']').attr('selected','selected');
                            }
                        }
                    }
                })
            }
        });
        CKEDITOR.replace( 'ckeditor' );
    </script>

    @yield("javascript")

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA_dZekD_l15yoUDVMTWUa-IJ3RKcpAUAU&libraries=places&callback=initMap"
        async defer></script>
</body>
</html>
