<?php
/**
 * @link http://test-station.local/
 */

namespace app\helpers\php\type;

/**
 * TypeNull class.
 * $link https://www.php.net/manual/ru/language.types.null.php
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class TypeNull
{
	public static function run()
	{
		$obj1 = new Obj1;

		echo '===== Типы - Type System - null (имя типа) =====<br />';
		$var1 = null;
		_d(gettype($var1));
		_vd($var1);
		if (PHP_VERSION >= 8) {
			_d(get_debug_type($var1));
		}
		
		echo '===== Типы - Type System - null (проверка типа) =====<br />';
		$var1 = null;
		_d($var1 === null, is_null($var1));
		
		echo '===== Типы - Type System - null (приведение типа) =====<br />';
		$var1 = 'example of string 1';
		$var2 = 'example of string 2';
		$var1 = null;
		_d($var1);
		if (PHP_VERSION < 8) {
			eval('
				_d((unset)$var2); // `unset` устарела и удалена из php>=8
			');
		}

		echo '===== Типы - Type System - null (сравнение типа) =====<br />';
		_d(null == 0, null == '0', null == false, null == '', null == []);
		
		echo '===== Типы - Type System - null (инициализация/обьявление типа из переменной) =====<br />';
		global $global_var1;
		static $static_var1;
		$var1 = null;
		_d($global_var1, $static_var1, $var1);
		if (PHP_VERSION >= 8) {
			_d($obj1->var_null_1, Obj1::$var_null_2);
		}
		if (PHP_VERSION >= 7.4) {
			_d($obj1->var_null_3, Obj1::$var_null_4);
		}

		echo '===== Типы - Type System - null (инициализация/обьявление типа из функции) =====<br />';
		if (PHP_VERSION >= 8) {
			eval('
				$fn_null_1 = function(int|float|null $var = null): float|null {
					return null;
				};
				$fn_null_2 = static function(int|float|null $var = null): float|null {
					return null;
				};
				$fn_null_3 = fn(int|float|null $var = null): float|null => null;
				$fn_null_4 = static fn(int|float|null $var = null): float|null => null;
			');
			_d(fn_null_1(null));
			_d($fn_null_1(null), $fn_null_2(null), $fn_null_3(null), $fn_null_4(null));
			_d($obj1->fn_null_1(null), Obj1::fn_null_2(null));
		}
		if (PHP_VERSION >= 7.4) {
			eval('
				$fn_null_5 = function(?float $var = null): ?float {
					return null;
				};
				$fn_null_6 = static function(?float $var = null): ?float {
					return null;
				};
				$fn_null_7 = fn(?float $var = null): ?float => null;
				$fn_null_8 = static fn(?float $var = null): ?float => null;
			');
			_d(fn_null_2(null));
			_d($fn_null_5(null), $fn_null_6(null), $fn_null_7(null), $fn_null_8(null));
			_d($obj1->fn_null_3(null), Obj1::fn_null_4(null));
		}
	}
}

if (PHP_VERSION >= 8) {
	eval('
		namespace app\helpers\php\type;
		class Obj1 {
			// Type null
			public int|float|null $var_null_1 = null;
			public static int|float|null $var_null_2 = null;
			public ?float $var_null_3 = null;
			public static ?float $var_null_4 = null;
			public function fn_null_1(int|float|null $var = null): float|null {
				return null;
			}
			public static function fn_null_2(int|float|null $var = null): float|null {
				return null;
			}
			public function fn_null_3(?float $var = null): ?float {
				return null;
			}
			public static function fn_null_4(?float $var = null): ?float {
				return null;
			}
		}
	');
} elseif (PHP_VERSION >= 7.4) {
	eval('
		namespace app\helpers\php\type;
		class Obj1 {
			// Type null
			public ?float $var_null_3 = null;
			public static ?float $var_null_4 = null;
			public function fn_null_3(?float $var = null): ?float {
				return null;
			}
			public static function fn_null_4(?float $var = null): ?float {
				return null;
			}
		}
	');
} else {
	class Obj1 {}
}

if (PHP_VERSION >= 8) {
	eval('
		function fn_null_1(int|float|null $var = null): float|null {
			return null;
		}
	');
}
if (PHP_VERSION >= 7.4) {
	eval('
		function fn_null_2(?float $var = null): ?float {
			return null;
		}
	');
}
