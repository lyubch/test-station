<?php
/**
 * @link http://test-station.local/
 */

namespace app\helpers\php;

/**
 * FunctionArguments class.
 * $link https://www.php.net/manual/ru/functions.arguments.php
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class FunctionArguments
{
	public static function run()
	{
		echo '<h4><<<Аргументы функции>>></h4>';
		$obj1 = new Obj1;
		$obj2 = new Obj2;

		//PHP Compile Error – Только последний параметр может быть переменным
		//$fn1 = function(...$args1, $args2) {};

		echo '===== Списки аргументов переменной длины `...` (аргументы передаются в виде массива) [php>=5.6] =====<br />';
		$fn1 = function(...$args) {
			return $args;
		};
		_d($fn1('A', 'B', 'C'));
		
		echo '===== Списки аргументов переменной длины `...` (можно добится аналогичного поведения используя функции `func_get_args`, `func_num_args`, но не рекомендуется поскольку есть более новый аналог `...`) =====<br />';
		$fn1 = function($args) {
			return func_get_args();
		};
		_d($fn1('A', 'B', 'C'));
		
		echo '===== Списки аргументов переменной длины `...` (распаковка iterable(array|Traversable) в список аргументов) =====<br />';
		$fn1            = function() {
			for ($i='A'; $i<='C'; $i++) {
				yield $i;
			}
		};
		$array1         = ['A', 'B', 'C'];
		$arrayIterator1 = new \ArrayIterator($array1);
		$generator1     = $fn1();
		_d(...['A', 'B', 'C']);
		_d(...$array1);
		_d(...$arrayIterator1);
		_d(...$generator1);
		_d(...$obj1);
		_d(...$obj2);
		
		if (PHP_VERSION >= 7.4) {
			echo '===== Списки аргументов переменной длины `...` (обьявление типа) =====<br />';
			eval('
				$fn2 = function(int ...$args) {
					return $args;
				};
			');
			try {
				_d($fn2('A'));
			} catch (\Throwable $error) {
				_d($error->getMessage());
			}
			_d($fn2(false, '1', 2, 3.125, '4.25'));

			echo '===== Списки аргументов переменной длины `...` (передача по ссылке) =====<br />';
			eval('
				$fn3 = function(int &...$args) {
					foreach ($args as &$arg) {
						$arg += 100;
					}
				};
			');
			try {
				_d($fn3('A'));
			} catch (\Throwable $error) {
				_d($error->getMessage());
			}
			$a = false;
			$b = '1';
			$c = 2;
			$d = 3.125;
			$e = '4.25';
			$fn3($a, $b, $c, $d, $e);
			_d($a, $b, $c, $d, $e);
		}

		if (PHP_VERSION >= 8.2) {
			echo '===== Список аргументов функции с завершающей запятой (php>=8) =====<br />';
			eval('
				$fn4 = function(
					$arg1,
				) {};
			');
			_d($fn4(1));
		}
			
		echo '===== аргументы по умолчанию (скалярные, массивы, спец-тип `null`) =====<br />';
		$fn5 = function(
			$arg1 = 1,
			$arg2 = 2.125,
			$arg3 = false,
			$arg4 = 'ABC',
			$arg5 = ['A', 'B', 'C'],
			$arg6 = null
		) {
			return get_defined_vars();
		};
		_d($fn5());
		
		if (PHP_VERSION >= 8.1) {
			echo '===== аргументы по умолчанию (php>=8.1, new ClassName()) =====<br />';
			eval('
				namespace app\helpers\php;
				$fn6 = function(
					$arg1 = new Obj1,
					$arg2 = new Obj2()
				) {
					return get_defined_vars();
				};
			');
			_d($fn6());
		}
		
		if (PHP_VERSION >= 8.0) {
			echo '===== именованные аргументы (php>=8.0, можно использовать для пропуска нескольких необязательных параметров) =====<br />';
			$fn7 = function(
				$arg1 = 'A',
				$arg2 = 'B',
				$arg3 = 'C'
			) {
				return implode('+', get_defined_vars());
			};
			eval('_d($fn7(arg3: \'XYZ\', arg2: \'OOO\'));');
			eval('_d(array_fill(start_index: 0, count: 3, value: \'A\'));');
			eval('_d(array_fill(0, 3, value: \'A\'));');
			//Не поддерживается
			//$arg = 'arg3';
			//_d($fn7($arg: 'XYZ'));
			
			echo '===== именованные аргументы (php>=8.0, можно комбинировать с позиционными аргументами, при этом именованные аргументы должны следовать после позиционных аргументов) =====<br />';
			eval('_d(array_fill(0, 3, value: \'B\'));');
			//Error: Named parameter $param overwrites previous argument
			//_d($fn7(arg1: 1, arg1: 2));
		}
		
		if (PHP_VERSION >= 8.1) {
			echo '===== именованные аргументы после распаковки `...` (php>=8.1, именованный аргумент не должен переопределять уже распакованные аргументы) =====<br />';
			eval('
				namespace app\helpers\php;
				$fn8 = function(
					$arg1,
					$arg2,
					$arg3 = \'C\',
					$arg4 = \'D\'
				) {
					return get_defined_vars();
				};
				_d($fn8(...[\'A\', \'B\'], arg4: \'XYZ\'));
				_d($fn8(...[\'arg1\'=>\'A\', \'arg2\'=>\'B\'], arg4: \'XYZ\'));
				try {
					_d($fn8(...[\'A\', \'B\'], arg2: \'XYZ\'));
				} catch (\Throwable $error) {
					_d($error->getMessage());
				}
			');
		}
	}
}

class Obj1 implements \Iterator {
	public $_storage = ['A', 'B', 'C'];
	public function current() {
		return current($this->_storage);
	}
	public function key() {
		return key($this->_storage);
	}
	public function next() {
		next($this->_storage);
	}
	public function rewind() {
		reset($this->_storage);
	}
	public function valid() {
		return key($this->_storage) !== null;
	}
}

class Obj2 implements \IteratorAggregate {
	public $_storage = ['A', 'B', 'C'];
	public function getIterator() {
		return new \ArrayIterator($this->_storage);
	}
}
