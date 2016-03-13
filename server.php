<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylorotwell@gmail.com>
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

require_once __DIR__.'/public/index.php';

// email và số dt
//
// dài thêm input
//
// ghi chú bự thêm
//
// auto thêm quận
//
// thêm trạng thái tại kho
//
// tính năng cập nhật nhanh trạng thái tại kho -> đang gửi về việt nam
//
// số thùng của 1 kiện hàng
//
// số kg kiện hàng
//
// quản lý khu vực
//
// chuyển demo
