<?php
/**
 * @link http://test-station.local/
 */

namespace app\helpers\php;

/**
 * OperatorsString class.
 * $link https://www.php.net/manual/ru/language.operators.string.php
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class OperatorsString
{
	public static function run()
	{
		echo '<h4><<<Строковые операторы>>></h4>';
		$obj1 = new Obj1;
		
		echo '===== Строковые операторы (оператор конкатенации `.`) =====<br />';
		$str1 = 'A' . '+' . 'B';
		_d($str1);
		
		echo '===== Строковые операторы (оператор присваивания с конкатенацией `.+`) =====<br />';
		$str1 .= '+' . 'C';
		_d($str1);
	}
}

class Obj1 {}
