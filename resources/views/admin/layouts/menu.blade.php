<div class="col-md-3">

    <div class="panel panel-default">
        <div class="panel-heading">Thống kê theo kiện hàng</div>
        <div class="panel-body">
            <ol>
            	<li><a href="{{URL::route('admin.home.index')}}">Tổng quản</a></li>
                <li><a href="{{URL::route('admin.statistics.shippers')}}">Người đi giao hàng</a></li>
                <li><a href="{{URL::route('admin.statistics.customers')}}">Khách hàng</a></li>
                <li><a href="{{URL::route('admin.statistics.chart')}}">Biểu đồ</a></li>
            </ol>
        </div>
    </div>

    <?php
        $permissions = unserialize(Auth::user()->permissions);
        $permissions = $permissions['reports']['shippers'];
    ?>
    @if(isset($permissions))
        @if($permissions)
        <div class="panel panel-default">
            <div class="panel-heading">Báo cáo</div>
            <div class="panel-body">
                <ol>
                	<li><a href="{{URL::route('admin.reports.shippers')}}">Người đi giao hàng</a></li>
                </ol>
            </div>
        </div>
        @endif
    @endif

    <div class="panel panel-default">
        <div class="panel-heading">Quản lý hệ thống</div>
        <div class="panel-body">
            <ol>
            	<li><a href="{{URL::route('admin.users.index')}}">Quản lý nhân viên</a></li>
            	<li><a href="{{URL::route('admin.customers.index')}}">Quản lý khách hàng</a></li>
                <li><a href="{{URL::route('admin.shippers.index')}}">{{Lang::get('admin.shipper.index')}}</a></li>
                <li><a href="{{URL::route('admin.locations.index')}}">{{Lang::get('admin.location.index')}}</a></li>
                <li><a href="{{URL::route('admin.packages.index')}}">{{Lang::get('admin.package.index')}}</a></li>
                <li><a href="{{URL::route('admin.logs.index')}}">{{Lang::get('admin.log.index')}}</a></li>
            </ol>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Quản lý kiện hàng xuất nhập kho</div>
        <div class="panel-body">
            <ol>
                <li><a href="{{URL::route('admin.packages.find')}}">Tìm kiện hàng vận chuyển</a></li>
                <li><a href="{{URL::route('admin.packages.barcode')}}">Quét mã vạch</a></li>
            </ol>
        </div>
    </div>

</div>
