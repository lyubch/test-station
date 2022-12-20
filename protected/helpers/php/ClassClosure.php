<?php
/**
 * @link http://test-station.local/
 */

namespace app\helpers\php;

/**
 * ClassClosure class.
 * $link https://www.php.net/manual/ru/class.closure.php
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class ClassClosure
{
	public static function run()
	{
		echo '<h4><<<Встроенные интерфейсы и классы>>></h4>';
		$obj1 = new Obj1;
		$obj2 = new Obj2;
		
		echo '===== Класс Closure (Зарезервированный класс Closure для анонимных функций или замыканий "closures") =====<br />';
		$fn1 = function() {};
		$fn1();
		_d($fn1 instanceof \Closure, $fn1);
		
		echo '===== Класс Closure (Closure метод bind (статический вариант метода bindTo)) =====<br />';
		$fn2  = function() {
			return $this->a;
		};
		$fn3  = static function() {
			return self::$b;
		};
		//_d($fn2()); //error (не правильный контекст)
		//_d($fn3()); //error (не правильный контекст)
		if (PHP_VERSION >= 7) {
			$fn2_1 = \Closure::bind($fn2, $obj1, Obj1::class/*|$obj1*/);
			_d($fn2_1());
		}
		$fn3_1 = \Closure::bind($fn3, null, Obj1::class/*|$obj1*/);
		_d($fn3_1());
		
		echo '===== Класс Closure (Closure метод bindTo) =====<br />';
		//_d($fn2()); //error (не правильный контекст)
		//_d($fn3()); //error (не правильный контекст)
		if (PHP_VERSION >= 7) {
			$fn2_2 = $fn2->bindTo($obj1, Obj1::class/*|$obj1*/);
			_d($fn2_2());
		}
		$fn3_2 = $fn3->bindTo(null, Obj1::class/*|$obj1*/);
		_d($fn3_2());
		
		echo '===== [php>=7] Класс Closure (Closure метод call) =====<br />';
		$fn4  = function($v) {
			return $this->a + $v;
		};
		$fn5  = function($v) {
			return self::$b + $v;
		};
		//_d($fn4(5)); //error (не правильный контекст)
		//_d($fn5(10)); //error (не правильный контекст)
		if (PHP_VERSION >= 7) {
			_d($fn4->call($obj1, 5));
			_d($fn5->call($obj1, 10));
		}
		
		echo '===== [php>=7.1] Класс Closure (Closure метод fromCallable) =====<br />';
		if (PHP_VERSION >= 7.1) {
			$fn_a1 = \Closure::fromCallable([$obj2, 'a']);
			$fn_b1 = \Closure::fromCallable([Obj2::class, 'b']);
			_d($fn_a1, $fn_b1);
			function a2() {
				return $this->a;
			};
			$fn_a2 = \Closure::fromCallable(__NAMESPACE__ . '\a2');
			_d($fn_a2);
		}

		echo '===== [php>=7] Класс Closure (анонимная статическая функция не может быть вызвана методом call) =====<br />';
		if (PHP_VERSION >= 7) {
			_d($fn2->call($obj1));
		}
		//_d($fn3->call($obj1)); //Error
		
		echo '===== Класс Closure (нельзя изменить контекст для не анонимных функций) =====<br />';
		//_d($fn_a1->call($obj1)); //Error
		//_d($fn_b1->call($obj1)); //Error
		//$fn_a1_1 = $fn_a1->bindTo($obj1, Obj1::class); //Error
		//$fn_b1_1 = $fn_b1->bindTo(null, Obj1::class); //Error
		//_d($fn_a2->call($obj1)); //Error
	}
}

class Obj1 {
	private $a = 10;
	private static $b = 20;
}

class Obj2 {
	public function a() {
		return $this->a;
	}
	public static function b() {
		return self::$b;
	}
}
