<?php
/**
 * @link http://test-station.local/
 */

namespace app\helpers\habr;

/**
 * PseudotypeCallable class.
 * $link https://habr.com/ru/post/259991/
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class PseudotypeCallable
{
	public static $_args = [];

	public static function run()
	{
		$obj1 = new Obj1;
		$obj2 = new Obj2;
		$obj3 = new Obj3;
		$obj4 = new Obj4;
		echo '===== Псевдотип «callable» (Обьект через магический метод __invoke) =====<br />';
		self::_dumpIsCallable(
			$obj1
		);
		_d($obj1);
		
		echo '===== Псевдотип «callable» (Несуществующий метод класса через магический метод __call) =====<br />';
		self::_dumpIsCallable(
			[Obj2::class, 'undefined'],
			[$obj2, 'undefined'],
			__NAMESPACE__ . '\Obj2::undefined'
		);
		
		echo '===== Псевдотип «callable» (Несуществующий метод класса через магический метод __callStatic) =====<br />';
		self::_dumpIsCallable(
			[Obj3::class, 'undefined'],
			[$obj3, 'undefined'],
			__NAMESPACE__ . '\Obj3::undefined'
		);

		echo '===== Псевдотип «callable» (Анонимная функция) =====<br />';
		$fn1 = function() {};
		self::_dumpIsCallable(
			$fn1
		);
		_d($fn1);
		
		if (PHP_VERSION >= 7.4) {
			echo '===== [php>=7.4] Псевдотип «callable» (Анонимная стрелочная функция) =====<br />';
			eval('$fn2 = fn() => null;');
			self::_dumpIsCallable(
				$fn2
			);
			_d($fn2);
		}

		echo '===== Псевдотип «callable» (Функция) =====<br />';
		function fn1() {};
		self::_dumpIsCallable(
			__NAMESPACE__ . '\fn1'
		);
		
		echo '===== Псевдотип «callable» (Метод класса) =====<br />';
		self::_dumpIsCallable(
			[$obj1, 'fn1']
		);
		
		echo '===== Псевдотип «callable» (Статический метод класса) =====<br />';
		self::_dumpIsCallable(
			[Obj1::class, 'fn2'],
			[$obj1, 'fn2'],
			__NAMESPACE__ . '\Obj1::fn2'
		);
		
		echo '===== [php>=5.4] Псевдотип «callable» (Тайп-хинтинг: хинт callable в функциях и методах) =====<br />';
		if (PHP_VERSION >= 7) {
			eval('
				namespace app\helpers\habr;
				$fn3 = function(callable $callable): callable {
					return $callable;
				};
				function f4(callable $callable): callable {
					return $callable;
				};
			');
		
		} else {
			$fn3 = function(callable $callable) {
				return $callable;
			};
			function f4(callable $callable) {
				return $callable;
			};
		}
		if (PHP_VERSION >= 7.4) {
			eval('
				$fn5 = fn(callable $callable): callable => $callable;
			');
		} else {
			$fn5 = $fn3;
		}

		_d(
			array_walk(self::$_args, $fn3),
			array_walk(self::$_args, __NAMESPACE__ . '\f4'),
			array_walk(self::$_args, $fn5),
			array_walk(self::$_args, [$obj4, 'f3']),
			array_walk(self::$_args, [Obj4::class, 'f4'])
		);
	}
	
	public static function _dumpIsCallable()
	{
		$args = array_map(function($arg) {
			$is_callable = is_callable($arg);
			if ($is_callable === true) {
				self::$_args []= $arg;
			}
			return $is_callable;
		}, func_get_args());
		call_user_func_array('\_d', $args);
	}
}

class Obj1 {
	public function __invoke() {}
	public function fn1() {}
	public static function fn2() {}
}

class Obj2 {
	public function __call($name, $arguments) {}
}

class Obj3 {
	public static function __callStatic($name, $arguments) {}
}

if (PHP_VERSION >= 7) {
	eval('
		namespace app\helpers\habr;
		class Obj4 {
			public function f3(callable $callable): callable {
				return $callable;
			}
			public static function f4(callable $callable): callable {
				return $callable;
			}
		}
	');
} else {
	class Obj4 {
		public function f3(callable $callable) {
			return $callable;
		}
		public static function f4(callable $callable) {
			return $callable;
		}
	}
}
