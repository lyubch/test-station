<?php
/**
 * @link http://test-station.local/
 */

namespace app\helpers\php\type;

/**
 * TypeBool class.
 * $link https://www.php.net/manual/ru/language.types.boolean.php
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class TypeBool
{
	public static function run()
	{
		$obj1 = new Obj1;

		echo '===== Типы - bool (имя типа) =====<br />';
		$var1 = _v();
		_d(gettype($var1));
		_vd($var1);
		if (PHP_VERSION >= 8) {
			_d(get_debug_type($var1));
		}
		
		echo '===== Типы - bool (проверка типа) =====<br />';
		$var1 = true;
		_d($var1 === true, is_bool($var1));
		
		echo '===== Типы - bool (приведение типа) =====<br />';
		$var1 = 'example of string 1';
		$var2 = 'example of string 2';
		$var3 = 'example of string 2';
		_d((bool)$var1, (boolean)$var2, boolval($var3));

		echo '===== Типы - bool (сравнение типа) =====<br />';
		_d(false == 0, false == '0', false == null, false == '', false == []);
		
		echo '===== Типы - bool (инициализация/обьявление типа из переменной) =====<br />';
		$var1 = _v();
		_d($var1);
		if (PHP_VERSION >= 8) {
			_d($obj1->var_1, Obj1::$var_2);
		}
		if (PHP_VERSION >= 7.4) {
			_d($obj1->var_3, Obj1::$var_4);
		}

		echo '===== Типы - bool (инициализация/обьявление типа из функции) =====<br />';
		if (PHP_VERSION >= 8) {
			eval('
				$fn_1 = function(int|float|bool $arg): float|bool {
					return $arg;
				};
				$fn_2 = static function(int|float|bool $arg): float|bool {
					return $arg;
				};
				$fn_3 = fn(int|float|bool $arg): float|bool => $arg;
				$fn_4 = static fn(int|float|bool $arg): float|bool => $arg;
			');
			_d(fn_1(_v()));
			_d($fn_1(_v()), $fn_2(_v()), $fn_3(_v()), $fn_4(_v()));
			_d($obj1->fn_1(_v()), Obj1::fn_2(_v()));
		}
		if (PHP_VERSION >= 7.4) {
			eval('
				$fn_5 = function(?float $arg): ?float {
					return $arg;
				};
				$fn_6 = static function(?float $arg): ?float {
					return $arg;
				};
				$fn_7 = fn(?float $arg): ?float => $arg;
				$fn_8 = static fn(?float $arg): ?float => $arg;
			');
			_d(fn_2(_v()));
			_d($fn_5(_v()), $fn_6(_v()), $fn_7(_v()), $fn_8(_v()));
			_d($obj1->fn_3(_v()), Obj1::fn_4(_v()));
		}
	}
}

if (PHP_VERSION >= 8) {
	eval('
		namespace app\helpers\php\type;
		class Obj1 {
			// Type bool
			public int|float|bool $var_1 = true;
			public static int|float|bool $var_2 = false;
			public ?bool $var_3 = false;
			public static ?bool $var_4 = true;
			public function fn_1(int|float|bool $arg): float|bool {
				return $arg;
			}
			public static function fn_2(int|float|bool $arg): float|bool {
				return $arg;
			}
			public function fn_3(?bool $arg): ?bool {
				return $arg;
			}
			public static function fn_4(?bool $arg): ?bool {
				return $arg;
			}
		}
	');
} elseif (PHP_VERSION >= 7.4) {
	eval('
		namespace app\helpers\php\type;
		class Obj1 {
			// Type bool
			public ?bool $var_3 = true;
			public static ?bool $var_4 = false;
			public function fn_3(?bool $arg): ?bool {
				return $arg;
			}
			public static function fn_4(?bool $arg): ?bool {
				return $arg;
			}
		}
	');
} else {
	class Obj1 {}
}

if (PHP_VERSION >= 8) {
	eval('
		function fn_1(int|float|bool $arg): float|bool {
			return $arg;
		}
	');
}
if (PHP_VERSION >= 7.4) {
	eval('
		function fn_2(?float $arg): ?float {
			return $arg;
		}
	');
}

function _v() {
	$values = [true, false];
	$random_key = array_rand($values, 1);
	return $values[$random_key];
}
