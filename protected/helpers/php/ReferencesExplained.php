<?php
/**
 * @link http://test-station.local/
 */

namespace app\helpers\php;

/**
 * ReferencesExplained class.
 * $link https://www.php.net/references
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class ReferencesExplained
{
	public static function run()
	{
		echo '<h4><<<Объяснение ссылок>>></h4>';
		echo '===== Присвоение по ссылке =====<br />';
		$a = 1;
		$b =& $a;
		$c =& $b;
		$a++;
		_d($a, $b, $c);
		$b++;
		_d($a, $b, $c);
		$c++;
		_d($a, $b, $c);
		
		echo '===== Присвоение по ссылке (создание неинициализированной переменной) =====<br />';
		function defineVar(&$var) {};
		_d(array_key_exists('newVar1', get_defined_vars()), defineVar($newVar1), array_key_exists('newVar1', get_defined_vars()));
		
		echo '===== Присвоение по ссылке (создание неинициализированного ключа массива) =====<br />';
		$array = [];
		_d(array_key_exists('newVar1', $array), defineVar($array['newVar1']), array_key_exists('newVar1', $array));
		
		echo '===== Присвоение по ссылке (создание неинициализированного свойства обьекта) =====<br />';
		$obj = new Obj;
		_d(property_exists($obj, 'newVar1'), defineVar($obj->newVar1), property_exists($obj, 'newVar1'));
		
		echo '===== Присвоение по ссылке (работает через $GLOBAL, а через global нет) =====<br />';
		_d(array_key_exists('globalVar1', $GLOBALS) ? $GLOBALS['globalVar1'] : false, array_key_exists('globalVar2', $GLOBALS) ? $GLOBALS['globalVar2'] : false);
		function globalVarsInit() {
			global $globalVar1, $globalVar2;
			$val1 = 1;
			$val2 = 2;
			$globalVar1 =& $val1;
			$GLOBALS['globalVar2'] =& $val2;
		};
		globalVarsInit();
		_d(array_key_exists('globalVar1', $GLOBALS) ? $GLOBALS['globalVar1'] : false, array_key_exists('globalVar2', $GLOBALS) ? $GLOBALS['globalVar2'] : false);

		echo '===== Присвоение по ссылке (foreach) =====<br />';
		$var = 0;
		$row =& $var;
		_d($row, $var);
		foreach ([1,2,3] as $row) {}
		_d($row, $var);
		
		echo '===== Присвоение по ссылке (array) =====<br />';
		$var1    = 0;
		$var2    = [1, 2];
		$arr1    = [&$var1, &$var2[0]];
		$arr1[2] =& $var2[1];
		_d($arr1);
		$arr1[0]++; $arr1[1]++; $arr1[2]++;
		_d($arr1);
		// ссылки на элементы массивы являются опасными поскольку при присвоении всего
		// массива не по ссылке - все равно передаются.
		$arr2 = $arr1;
		$arr2[0]++; $arr2[1]++; $arr2[2]++;
		_d($arr1);
		
		echo '===== Присвоение по ссылке (global нельзя присваивать по ссылке, не будет работать, поскольку global и так является ссылкой) =====<br />';
		function apply_global_ref() {
			global $testGlobal1;
			$obj = new \stdClass();
			$testGlobal1 =& $obj;
		}
		function apply_global_noref() {
			global $testGlobal2;
			$obj = new \stdClass();
			$testGlobal2 = $obj;
		}
		apply_global_ref();
		_d($GLOBALS['testGlobal1']);
		apply_global_noref();
		_d($GLOBALS['testGlobal2']);

		echo '===== Присвоение по ссылке (static local нельзя присваивать по ссылке, не будет работать, поскольку static local и так является ссылкой) =====<br />';
		function get_static_ref() {
			static $obj;
			_d('get_static_ref', $obj);
			if (!isset($obj)) {
				$std = new \stdClass();
				$obj =& $std;
			}
			if (!isset($obj->prop)) {
				$obj->prop = 1;
			} else {
				$obj->prop++;
			}
			_d('get_static_ref->prop', $obj->prop);
			return $obj;
		}
		function get_static_noref() {
			static $obj;
			_d('get_static_noref', $obj);
			if (!isset($obj)) {
				$std = new \stdClass();
				$obj = $std;
			}
			if (!isset($obj->prop)) {
				$obj->prop = 1;
			} else {
				$obj->prop++;
			}
			_d('get_static_noref->prop', $obj->prop);
			return $obj;
		}
		get_static_ref();
		get_static_ref();
		echo '<br/>-----<br/>';
		get_static_noref();
		get_static_noref();
		echo '<br/>-----<br/>';
		// для static property работает, но тем не менее является unexpected behavior
		// в будущих версиях могут пофиксить
		Obj::get_static_ref();
		Obj::get_static_ref();
		echo '<br/>-----<br/>';
		Obj::get_static_noref();
		Obj::get_static_noref();
		
		

		echo '===== Передача по ссылке =====<br />';
		function increase(&$x) {
			$x++;
		};
		$y = 1;
		increase($y);
		_d($y);
		
		echo '===== Передача по ссылке (не перепривязывается в других областях видимости) =====<br />';
		$ref_to = null;
		_d(array_key_exists('globalVar2', $GLOBALS) ? $GLOBALS['globalVar2'] : false, $ref_to);
		function get_global_ref($ref_from, &$ref_to) {
			$ref_to =& $GLOBALS[$ref_from];
		};
		get_global_ref('globalVar2', $ref_to);
		_d(array_key_exists('globalVar2', $GLOBALS) ? $GLOBALS['globalVar2'] : false, $ref_to);
		

		
		echo '===== Возврат по ссылке (collector[array]) =====<br />';
		function &collector() {
			static $collection = [];
			return $collection;
		};
		$collector =& collector();
		$collector []= 1;
		array_push($collector, 2);
		_d($collector, collector());

		echo '===== Возврат по ссылке (counter[integer]) =====<br />';
		function &counter($i = 1) {
			static $count = 0;
			$count = $count + $i;
			return $count;
		};
		$a =& counter();
		_d($a);
		counter();counter();
		_d($a);
		counter(3);
		_d($a);
		$a = 1;
		_d($a, counter(0));
		
		echo '===== Возврат по ссылке (class method) =====<br />';
		$val1 =& $obj->getVal1();
		_d($val1, $obj->val1, $obj->getVal1());
		$val1 = 2;
		_d($val1, $obj->val1, $obj->getVal1());
		$obj->val1 = 3;
		_d($val1, $obj->val1, $obj->getVal1());
		$obj->setVal1(4);
		_d($val1, $obj->val1, $obj->getVal1());

		
		
		echo '===== Сброс ссылки на переменную (ссылка удаляется, значение остаётся) =====<br />';
		$a = 1;
		$b =& $a;
		_d(array_key_exists('a', get_defined_vars())?$a:false, array_key_exists('b', get_defined_vars())?$b:false);
		unset($a);
		_d(array_key_exists('a', get_defined_vars())?$a:false, array_key_exists('b', get_defined_vars())?$b:false);
		
		echo '===== Сброс ссылки на глобальную переменную (ссылка удаляется, значение остаётся) =====<br />';
		_d(array_key_exists('globalVar3', $GLOBALS) ? $GLOBALS['globalVar3'] : false);
		global $globalVar3;
		_d(array_key_exists('globalVar3', $GLOBALS) ? $GLOBALS['globalVar3'] : false);
		$globalVar3 = 33;
		_d(array_key_exists('globalVar3', $GLOBALS) ? $GLOBALS['globalVar3'] : false);
		unset($globalVar3);
		_d(array_key_exists('globalVar3', $GLOBALS) ? $GLOBALS['globalVar3'] : false);

		echo '===== Сброс ссылки на глобальную переменную (ссылка удаляется, значение остаётся) =====<br />';
		_d(array_key_exists('globalVar4', $GLOBALS) ? $GLOBALS['globalVar4'] : false);
		$globalVar4 = 44;
		$GLOBALS['globalVar4'] =& $globalVar4;
		_d(array_key_exists('globalVar4', $GLOBALS) ? $GLOBALS['globalVar4'] : false);
		unset($globalVar4);
		_d(array_key_exists('globalVar4', $GLOBALS) ? $GLOBALS['globalVar4'] : false);
		
		echo '===== Сброс ссылки на глобальную переменную (ссылка удаляется, значение остаётся) =====<br />';
		_d(array_key_exists('globalVar5', get_defined_vars()) ? $globalVar5 : false, array_key_exists('globalVar5', $GLOBALS) ? $GLOBALS['globalVar5'] : false);
		global $globalVar5;
		_d(array_key_exists('globalVar5', get_defined_vars()) ? $globalVar5 : false, array_key_exists('globalVar5', $GLOBALS) ? $GLOBALS['globalVar5'] : false);
		$globalVar5 = 55;
		_d(array_key_exists('globalVar5', get_defined_vars()) ? $globalVar5 : false, array_key_exists('globalVar5', $GLOBALS) ? $GLOBALS['globalVar5'] : false);
		unset($GLOBALS['globalVar5']);
		_d(array_key_exists('globalVar5', get_defined_vars()) ? $globalVar5 : false, array_key_exists('globalVar5', $GLOBALS) ? $GLOBALS['globalVar5'] : false);
		
		
		
		// Побитовые операторы
		// https://www.php.net/manual/ru/language.operators.bitwise.php

	}
}

class Obj {
	public $val1 = 1;
	public static $obj_ref;
	public static $obj_noref;
	
	public function setVal1($val) {
		$this->val1 = $val;
	}
	
	public function &getVal1() {
		return $this->val1;
	}
	
	public static function get_static_ref() {
		_d('Obj::get_static_ref', self::$obj_ref);
		if (!isset(self::$obj_ref)) {
			$std = new \stdClass();
			self::$obj_ref =& $std;
		}
		if (!isset(self::$obj_ref->prop)) {
			self::$obj_ref->prop = 1;
		} else {
			self::$obj_ref->prop++;
		}
		_d('Obj::get_static_ref->prop', self::$obj_ref->prop);
	}
	public static function get_static_noref() {
		_d('Obj::get_static_noref', self::$obj_noref);
		if (!isset(self::$obj_noref)) {
			$std = new \stdClass();
			self::$obj_noref = $std;
		}
		if (!isset(self::$obj_noref->prop)) {
			self::$obj_noref->prop = 1;
		} else {
			self::$obj_noref->prop++;
		}
		_d('Obj::get_static_noref->prop', self::$obj_noref->prop);
	}
}
