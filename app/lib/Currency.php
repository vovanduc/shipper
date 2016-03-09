<?php
namespace App\lib;
class Currency{
	public static function format($number)
	{
		$symbol = ' VND';
		$symbol_thousand = '.';
		$decimal_place = 0;
		$price = number_format($number, $decimal_place, '', $symbol_thousand);
		return $price.$symbol;
	}
}
