<?php
/**
 * @link http://test-station.local/
 */

namespace app\helpers\php;

/**
 * Generators class.
 * $link https://www.php.net/manual/ru/language.generators.php
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class Generators
{
	public static function run()
	{
		echo '<h4><<<Встроенные интерфейсы и классы>>></h4>';
		echo '<h4><<<`Yield` - Аналог `return`, но вместо возврата и остановки функции - `yield` возвращает и приостанавливает функцию, а при повторном вызове продолжает исполнение.>>></h4>';
		
		$array1 = ['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D', 'e' => 'E'];
		$fn1 = function() use($array1) {
			foreach ($array1 as $key => $value) {
				yield $value;
			}
			return 'Done';
		};
		
		echo '===== Класс Generator (Любая функция, содержащая yield, является функцией генератора) =====<br />';
		$generator1 = $fn1();
		_d($fn1, $generator1);
		
		echo '===== Класс Generator (класс `\Generator` нельзя создавать вручную) =====<br />';
		try {
			$generator1 = new \Generator();
		} catch (\Throwable $error) {
			_d($error->getMessage());
		}

		echo '===== Класс Generator (методы `rewind`,`key`,`current`,`valid`,`next` класса `\Generator`) =====<br />';
		$generator1->rewind(); //перемотать на начало
		for ($i=0; $i<3; $i++) {
			_d($i, $generator1->key(), $generator1->current(), $generator1->valid(), $generator1->next());
		}
		
		echo '===== Класс Generator (Нельзя перемотать `rewind` генератор на начало если он был уже запущен функцией `next`) =====<br />';
		try {
			$generator1->rewind();
		} catch (\Throwable $error) {
			_d($error->getMessage());
		}

		echo '===== Класс Generator (Можно закончить итерацию генератора позже с сохранённого места) =====<br />';
		while ($generator1->valid() !== false) {
			_d($generator1->key(), $generator1->current(), $generator1->valid(), $generator1->next());
		}

		echo '===== Класс Generator (функция `getReturn` генератора возвращает дополнительное значение возвращаемое конструкцией `return`) =====<br />';
		_d($generator1->getReturn(), $generator1->key(), $generator1->current(), $generator1->valid(), $generator1->next());

		echo '===== Класс Generator (функция `send` генератора - непонятное поведение генератора с ловлей отправленого значения внутри генератора `$yielding=yield`) =====<br />';
		_d($generator1->send(1), $generator1->valid(), $generator1->key(), $generator1->current(), $generator1->next(), $generator1->key(), $generator1->current());
		
		echo '===== Класс Generator (функция `throw` генератора мгновенно выбрасывает переданное исключение) =====<br />';
		try {
			$generator1->throw(new \Exception('Test call `throw`.'));
		} catch (\Throwable $error) {
			_d($error->getMessage());
		}

		echo '===== Класс Generator (функция `__wakeup` генератора выбрасывает исключение поскольку генераторы не могут быть сериализированы) =====<br />';
		try {
			serialize($generator1);
		} catch (\Throwable $error) {
			_d($error->getMessage());
		}

		echo '===== Класс Generator (Возврат значения) =====<br />';
		foreach ($fn1() as $key => $value) {
			_d($key, $value);
		}
		
		echo '===== Класс Generator (возврат пары ключ => значение) =====<br />';
		$fn2 = function() use($array1) {
			foreach ($array1 as $key => $value) {
				yield $key => $value;
			}
		};
		foreach ($fn2() as $key => $value) {
			_d($key, $value);
		}
		
		echo '===== Класс Generator (вполучение пар ключ/значение в выражениях требует оборачивания их в скобки) =====<br />';
		$fn3 = function() use($array1) {
			foreach ($array1 as $key => $value) {
				$data = (yield $key => $value);
			}
		};
		foreach ($fn3() as $key => $value) {
			_d($key, $value);
		}
		
		echo '===== Класс Generator (вызов `yield` без аргументов вернёт `null`) =====<br />';
		$fn4 = function() use($array1) {
			foreach ($array1 as $key => $value) {
				switch ($key) {
					case 'a':
						yield;
						break;
					case 'b':
						yield null;
						break;
					case 'c':
						yield $key => null;
						break;
					case 'd':
						yield null => $value;
						break;
					case 'e':
						yield $value;
						break;
				}
			}
		};
		foreach ($fn4() as $key => $value) {
			_d($key, $value);
		}
		
		echo '===== Класс Generator (возврат ссылок из функции через `yield`) =====<br />';
		$fn5 = function&($max) {
			$value = 0;
			while ($value < $max) {
				yield $value;
			}
		};
		foreach ($fn5(5) as &$value) {
			_d($value++);
		}
		
		if (PHP_VERSION >= 7.0) {
			echo '===== Класс Generator (`yield from` - делегирование генератора) =====<br />';
			$fn6 = function() {
				yield 8;
			};
			eval('
				$fn7 = function() use($fn6) {
					yield 7;
					yield from $fn6();
				};
				$fn8 = function() use($fn7) {
					yield 1;
					yield 2;
					yield from [3, 4];
					yield from new \ArrayIterator([5, 6]);
					yield from $fn7();
					yield 9;
					yield 10;
				};
			');
			foreach ($fn8() as $key => $value) {
				_d($key, $value);
			}
			
			echo '===== Класс Generator (`yield from` - по умолчанию не сбрасывает ключи) =====<br />';
			_d(iterator_to_array($fn8()));
			_d(iterator_to_array($fn8(), false));
			
			echo '===== Класс Generator (оператор `return` внутри генератора и функция генератора `getReturn`) =====<br />';
			$fn9 = function() {
				yield 4;
				return 5;
			};
			$fn10 = function() use($fn9) {
				yield 1;
				yield 2;
				yield 3;
				return yield from $fn9();
			};
			$generator2 = $fn10();
			foreach ($generator2 as $key => $value) {
				_d($key, $value);
			}
			_d($generator2->getReturn());
		}

		echo '===== Класс Generator (тест использования оперативной памяти функцией `range` от 0 до 500_000) =====<br />';
		$memory_start = memory_get_usage(false);
		$memory_test1 = range(0, 500000);
		$memory_end = memory_get_usage(false);
		$memory_usage_mb1 = ((($memory_end - $memory_start) / 1024) / 1024);
		_d($memory_usage_mb1);
		
		echo '===== Класс Generator (тест использования оперативной памяти генератором от 0 до 500_000) =====<br />';
		$memory_start = memory_get_usage(false);
		$memory_test2 = function() {
			for ($i=0; $i<=500000; $i++) {
				yield $i;
			}
		};
		$generator3 = $memory_test2();
		$memory_end = memory_get_usage(false);
		$memory_usage_mb2 = ((($memory_end - $memory_start) / 1024) / 1024);
		_d($memory_usage_mb2);
		
		echo '===== Класс Generator (тест использования оперативной памяти превращением генератора от 0 до 500_000 в массив) =====<br />';
		$memory_start = memory_get_usage(false);
		$memory_test3 = iterator_to_array($generator3);
		$memory_end = memory_get_usage(false);
		$memory_usage_mb3 = ((($memory_end - $memory_start) / 1024) / 1024);
		_d($memory_usage_mb3);
	}
}

class Obj {}
