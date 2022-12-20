<?php
/**
 * @link http://test-station.local/
 */

namespace app\helpers\habr;

/**
 * AboutInterfaces class.
 * $link https://habr.com/ru/post/328890/
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class AboutInterfaces
{
	public static function run()
	{
		echo '<h4><<< Об интерфейсах, совместимости сигнатур >>></h4>';
		$obj1 = new Obj1;
		$obj2 = new Obj2;

		echo '===== Об интерфейсах (могут содержать только публичные константы, свойства запрещены, константы можно переопределять в классе-наследнике или интерфейсе наследнике с php>=8.1) =====<br />';
		_d(Obj1::CN1, Obj1::CN2);
		_d($obj1->fn1(), $obj1->fn2());
		_d($obj1 instanceof Int1, $obj1 instanceof Int2);
		
		echo '===== Об интерфейсах (наследование интерфейса-наследника от интерфейса-предка) =====<br />';
		_d(Obj2::C1, Obj2::C2, Obj2::C3);
		_d($obj2->fn1(10));
		_d($obj2->fn2(10), $obj2->fn2(10, 'A'));
		_d($obj2->fn3(10), $obj2->fn3(10, 'A', 'B', 'C'));
		
		echo '===== Об интерфейсах (поддерживает множественное наследование) =====<br />';
		_d($obj2 instanceof Int3, $obj2 instanceof Int4, $obj2 instanceof Int5);
		
		echo '===== Об интерфейсах (получить полное имя интерфейса) =====<br />';
		_d(Int1::class, Int2::class, Int3::class, Int4::class, Int5::class);
	}
}

interface Int1 {
	const CN1 = [
		'a' => 'A',
	];
	// Ошибка - Interfaces may not include properties
	//public $prop1 = 'A';
	//Сразу после сигнатуры метода нужно поставить `;`
	public function fn1();
	public function fn2();
}

interface Int2 {
	const CN2 = [
		'b' => 'B',
	];
	// Ошибка - Interfaces may not include properties
	//public $prop1 = 'A';
	//Сразу после сигнатуры метода нужно поставить `;`
	public function fn1();
}

class Obj1 implements Int1, Int2 {
	// Ошибка
	const CN1 = [
		'c' => 'C',
	];
	public function fn1() {
		return get_defined_vars();
	}
	public function fn2() {
		return get_defined_vars();
	}
}

interface Int3 {
	const C1 = 'Int2_C1';
	public function fn1(int $arg1);
}

interface Int4 {
	public function fn2(int $arg1);
	public function fn3(int $arg1);
}

interface Int5 extends Int3, Int4 {
	const C2 = 'Int3_C2';
	const C3 = 'Int3_C3';
	// Error - must be compatible with Int2::fn2(int $arg1)
	//public function fn2(int $arg1, string $arg2);
	//можно добавлять тип возвращаемого значения и/или необязательный параметр/параметры,
	//которы(й/е) не указан(ы) в интерфейсе-родителе
	public function fn2(int $arg1, string|null $arg2 = null);
	public function fn3(int $arg1, string ...$args): array;
}

class Obj2 implements Int5 {
	const C3 = 'Obj2_C3';
	//можно добавлять тип возвращаемого значения, который не указан в интерфейсе
	public function fn1(int $arg1): array {
		return get_defined_vars();
	}
	//можно добавлять необязательный параметр/параметры, которы(й/е) не указан(ы) в интерфейсе
	public function fn2(int $arg1, string|null $arg2 = null): array {
		return get_defined_vars();
	}
	public function fn3(int $arg1, string ...$args): array {
		return get_defined_vars();
	}
}
