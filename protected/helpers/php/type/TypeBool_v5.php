<?php
/**
 * @link http://test-station.local/
 */

namespace app\helpers\php\type;

/**
 * TypeBool_v5 class.
 * $link https://www.php.net/manual/ru/language.types.boolean.php
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class TypeBool_v5
{
	public static function run()
	{
		$obj1 = new Obj1;

		echo '===== Типы - bool (имя типа) =====<br />';
		$var1 = true;
		_d(gettype($var1));
		echo '</br>';var_dump($var1);echo '</br>';

		echo '===== Типы - bool (проверка типа) =====<br />';
		$var1 = true;
		_d($var1 === true, is_bool($var1));
		
		echo '===== Типы - bool (приведение типа) =====<br />';
		$var1 = 'example of string 1';
		_d((bool)$var1, (boolean)$var1, boolval($var1));
		_d($obj1->fn_1(1), Obj1::fn_2(1));
		_d($obj1->fn_1(1.1), Obj1::fn_2(1.1));
		_d($obj1->fn_1('A'), Obj1::fn_2('A'));
		_d($obj1->fn_1(0), Obj1::fn_2(0));
		_d($obj1->fn_1(''), Obj1::fn_2(''));

		echo '===== Типы - bool (сравнение типа) =====<br />';
		_d(false == 0, false == '0', false == null, false == '', false == [], empty(false));
		_d(true == 1, true == '1', true == 'a', true == [0], !empty(true));
		
		echo '===== Типы - bool (инициализация/обьявление типа из переменной) =====<br />';
		$var1 = true;
		_d($var1);

		echo '===== Типы - bool (инициализация/обьявление типа из функции) =====<br />';
		_d($obj1->fn_1(true), Obj1::fn_2(true));
		_d($obj1->fn_1(false), Obj1::fn_2(false));
	}
}

class Obj1 {
	// Type bool
	public function fn_1(bool $arg): bool {
		$fn_1 = function(bool $arg): bool {
			return $arg;
		};
		return fn_1($fn_1($arg));
	}
	public static function fn_2(bool $arg): bool {
		$fn_1 = static function(bool $arg): bool {
			return $arg;
		};
		return $fn_1($arg);
	}
}

function fn_1(bool $arg): bool {
	return $arg;
}
