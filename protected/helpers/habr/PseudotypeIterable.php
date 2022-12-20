<?php
/**
 * @link http://test-station.local/
 */

namespace app\helpers\habr;

/**
 * PseudotypeIterable class.
 * $link https://habr.com/ru/post/324934/
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class PseudotypeIterable
{
	public static function run()
	{
		$obj1 = new Obj1;
		$objChild1 = new ObjChild1;
		$obj2 = new Obj2;
		$obj3 = new Obj3;
		$obj4 = new Obj4;
		echo '<h4><<<Псевдотип «iterable»>>></h4>';

		echo '===== Итерация по массиву (foreach - обладает собственным внутренним указателем не доступным через функции key,current) =====<br />';
		$array1 = ['A', 'B', 'C', 'D', 'E'];

		foreach ($array1 as $key => $value) {
			//не работает внутри `foreach`, указатель не сдвигается (php 5.6, 7.4, 8.2)
			_d(key($array1), current($array1));
		}
		
		echo '===== Итерация по массиву (for - обладает собственным внутренним указателем не доступным через функции key,current) =====<br />';
		for ($key=0; $key<count($array1); $key++) {
			//не работает внутри `foreach`, указатель не сдвигается (php 5.6, 7.4, 8.2)
			_d(key($array1), current($array1));
		}
		
		echo '===== Итерация по массиву (reset,key,current,next - внутренний указатель массива доступен через функции key,current) =====<br />';
		reset($array1);
		while (($key=key($array1)) !== null) {
			_d($key, current($array1));
			next($array1);
		}
		
		echo '===== Итерация по объекту (внутри текущего контекста) =====<br />';
		$obj1->iterate();
		
		echo '===== Итерация по объекту (внутри дочернего контекста) =====<br />';
		$objChild1->iterate();
		
		echo '===== Итерация по объекту (снаружи) =====<br />';
		foreach ($obj1 as $key => $value) {
			_d($key, $value);
		}
		
		echo '===== Итерация по объекту с интерфейсом Iterator (SPL) =====<br />';
		foreach ($obj2 as $key => $value) {
			_d($key, $value);
			//не возвращает корректные ключ и значение поскольку `$obj2` не является итерируемым массивом (php 5.6, 7.4, 8.2)
			//_d(key($obj2), current($obj2));
			//key,current работают корректно через `$obj2->_storage`
			_d(key($obj2->_storage), current($obj2->_storage));
		}
		
		echo '===== Итерация по объекту ArrayIterator (один из классов итераторов библиотеки для PHP - SPL) =====<br />';
		$arrayIterator1 = new \ArrayIterator($array1);
		_d($arrayIterator1);
		foreach ($arrayIterator1 as $key => $value) {
			_d($key, $value);
		}
		// Дополнительно расходует память при создании новых итераторов с теми же значениями
		//_d(memory_get_usage());
		//$arrayIterator2 = new \ArrayIterator($array1);
		//foreach ($arrayIterator2 as $key => $value) {}
		//_d(memory_get_usage());
		
		echo '===== Итерация по объекту ArrayIterator (через (new \ArrayObject(...))->getIterator() - SPL) =====<br />';
		$arrayObject1 = new \ArrayObject($array1);
		$arrayIterator2 = $arrayObject1->getIterator();
		_d($array1, $arrayObject1, $arrayIterator2);
		foreach ($arrayIterator2 as $key => $value) {
			_d($key, $value);
		}
		$array2 = iterator_to_array($arrayIterator2);
		$array3 = $arrayObject1->getArrayCopy();
		_d($array2, $array3, $array1===$array2, $array1===$array3);
		
		echo '===== Итерация по объекту с интерфейсом IteratorAggregate (SPL) =====<br />';
		foreach ($obj3 as $key => $value) {
			_d($key, $value);
		}
		
		echo '===== Итерация по классу Generator (проверка свойств) =====<br />';
		$fn1 = function() {
			for ($i=1; $i<=3; $i++) {
				yield $i;
			}
		};
		$generator1 = $fn1();
		foreach ($generator1 as $key => $value) {
			_d($key, $value);
		}
		
		echo '===== Псевдотип iterable - Traversable|array (проверка свойств) =====<br />';
		_d($obj4->prop1, $obj4::$prop2);
		$obj4->prop1 = $obj2;
		$obj4->prop1 = $obj3;
		$obj4->prop1 = $arrayIterator1;
		$obj4->prop1 = $generator1;
		$obj4::$prop2 = $obj2;
		$obj4::$prop2 = $obj3;
		$obj4::$prop2 = $arrayIterator1;
		$obj4::$prop2 = $generator1;
		try {
			$obj4->prop1 = 1;
		} catch (\Throwable $error) {
			_d($error->getMessage());
		}
		try {
			$obj4::$prop2 = 1;
		} catch (\Throwable $error) {
			_d($error->getMessage());
		}
		
		echo '===== Псевдотип iterable - Traversable|array (проверка методов) =====<br />';
		$obj4->fn1($obj2);
		$obj4->fn1($obj3);
		$obj4->fn1($arrayIterator1);
		$obj4->fn1($generator1);
		$obj4::fn2($obj2);
		$obj4::fn2($obj3);
		$obj4::fn2($arrayIterator1);
		$obj4::fn2($generator1);
		try {
			$obj4->fn1(1);
		} catch (\Throwable $error) {
			_d($error->getMessage());
		}
		try {
			$obj4::fn2(1);
		} catch (\Throwable $error) {
			_d($error->getMessage());
		}
	}
}

class Obj1 {
	public $a = 'A';
	protected $b = 'B';
	private $c = 'C';
	public function iterate() {
		foreach ($this as $key => $value) {
			_d($key, $value);
		}
	}
}

class ObjChild1 extends Obj1 {
	public function iterate() {
		foreach ($this as $key => $value) {
			_d($key, $value);
		}
	}
}

class Obj2 implements \Iterator {
	public $_storage = ['A', 'B', 'C', 'D', 'E'];
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

class Obj3 implements \IteratorAggregate {
	public $_storage = ['A', 'B', 'C', 'D', 'E'];
	public function getIterator() {
		return new \ArrayIterator($this->_storage);
	}
}

class Obj4 {
	public iterable $prop1 = ['A', 'B', 'C'];
	public static iterable $prop2 = ['A', 'B', 'C'];
	public function fn1(iterable $iterable): iterable {
		return $iterable;
	}
	public static function fn2(iterable $iterable): iterable {
		return $iterable;
	}
}
