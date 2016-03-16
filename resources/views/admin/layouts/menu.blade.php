<div class="col-md-3">
    <div class="panel panel-default">
        <div class="panel-heading">Menu</div>

        <div class="panel-body">
            <ol>
            	<li><a href="{{URL::route('admin.users.index')}}">Quản lý nhân viên</a></li>
            	<li><a href="{{URL::route('admin.customers.index')}}">Quản lý khách hàng</a></li>
                <li><a href="{{URL::route('admin.shippers.index')}}">{{Lang::get('admin.shipper.index')}}</a></li>
                <li><a href="{{URL::route('admin.packages.index')}}">{{Lang::get('admin.package.index')}}</a></li>
                <li><a href="{{URL::route('admin.logs.index')}}">{{Lang::get('admin.log.index')}}</a></li>
                <li><a href="{{URL::route('admin.packages.find')}}">Tìm kiện hàng vận chuyển</a></li>
            </ol>
        </div>
    </div>
</div>
