<?php
/**
 * @link http://test-station.local/
 */

namespace app\helpers\php;

/**
 * FunctionsAnonymous class.
 * $link https://www.php.net/manual/ru/functions.anonymous.php
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class FunctionsAnonymous
{
	public static function run()
	{
		echo '<h4><<<Функции>>></h4>';
		$obj1 = new Obj1;
		$fn_null_1 = function(?int $arg): ?int {
			return $arg;
		};
		
		echo '===== Анонимные функции (null) =====<br />';
		_assert($fn_null_1(null) === null, '`?*` allow `null`.');
		
		
		$fn1 = (function($arg1) {
			return $arg1;
		})(7);
		
		_d($fn1);

	}
}

class Obj1 {}
