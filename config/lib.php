<?php

return [
	'PACKAGE' =>
		[
			'delivery_success' => 5 // Giao hàng thành công
		],
	'MODULE' =>
		[
			'home' => 'Trang chủ',
			'users' => 'Nhân viên',
            'customers' => 'Khách hàng',
            'shippers' => 'Người đi giao hàng',
            'packages' => 'Kiện hàng',
			'locations' => 'Vị trí',
			'statistics' => 'Thống kê',
			'logs' => 'Lịch sử truy cập',
		],
    'ACTION' =>
		[
			'view' => 'Xem',
            'add' => 'Thêm',
            'update' => 'Sửa',
            'delete' => 'Xóa',
		],
	'PERMISSIONS' =>
		[
			'users' => [
				'index' => false,
				'show' => false,
				'search' => false,
				'add' => false,
				'update' => false,
				'delete' => false,
				'permission' => false,
			],
            'customers' => [
				'index' => false,
				'show' => false,
				'search' => false,
				'add' => false,
				'update' => false,
				'delete' => false,
			],
            'shippers' => [
				'index' => false,
				'show' => false,
				'search' => false,
				'add' => false,
				'update' => false,
				'delete' => false,
			],
            'packages' => [
				'index' => false,
				'show' => false,
				'search' => false,
				'add' => false,
				'update' => false,
				'delete' => false,
				'money' => false,
				'find' => false,
				'barcode' => false,
			],
			'locations' => [
				'index' => false,
				'show' => false,
				'search' => false,
				'add' => false,
				'update' => false,
				'delete' => false,
			],
			'statistics' => [
				'shippers' => false,
				'customers' => false,
				'chart' => false,
			],
			'logs' => [
				'index' => false,
			],
		],
];
