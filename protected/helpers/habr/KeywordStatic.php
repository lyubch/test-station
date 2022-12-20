<?php
/**
 * @link http://test-station.local/
 */

namespace app\helpers\habr;

/**
 * KeywordStatic class.
 * $link https://habr.com/ru/post/259627/
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 * 
 * Ошибка компиляции - даже без вызова функции.
 * Ошибка рантайма   - при непосредственном вызове функции.
 */
class KeywordStatic
{
	public static function run()
	{
		echo '===== Статическая Локальная Переменная (присваивать можно только константы или константные выражения) =====<br />';
		$_fn = function() {};
		function _fn() {};
		static $str = 'ab';
		static $int = 11;
		static $float = 1.11;
		static $bool = true;
		static $array = [11, 'ab'];
		static $null = null;
		//static $obj = new Obj;       //php>=8.1
		static $obj;
		$obj = new Obj;
		//static $fn1 = $_fn;          //Ошибка компиляции
		static $fn1;
		$fn1 = $_fn;
		//static $fn2 = _fn();         //Ошибка компиляции
		static $fn2;
		$fn2 = _fn();
		//static $fn3 = function() {}; //Ошибка компиляции
		static $fn3;
		$fn3 = function() {};
		//static $fn4 = fn() => null;  //Ошибка компиляции
		static $fn4;
		//$fn4 = fn() => null;         //php >= 7.4 [Стрелочная Функция]
		_d($str, $int, $float, $bool, $array, $null, $obj, $fn1, $fn2, $fn3, $fn4);
		
		echo '===== Статическая Локальная Переменная (существует только в одном экземпляре, не клонируется) =====<br />';
		$obj1 = new Obj;
		$obj2 = new Obj;
		_d($obj1->counter(), $obj2->counter(), $obj1->counter(), $obj2->counter());
		
		echo '===== [php<=8.1] Статическая Локальная Переменная (клонируется при наследовании) =====<br />';
		$obj3 = new ChildObj;
		_d($obj1->counter(), $obj3->counter(), $obj1->counter(), $obj3->counter());
		
		echo '===== Статические свойства и методы классов (вызов из статического контекста) =====<br />';
		_d(Obj::$val1, Obj::val1(), Obj::val2());
		_d($obj1::$val1, $obj1::val1(), $obj1::val2());
		
		echo '===== Статические свойства и методы классов (вызов из динамического контекста) =====<br />';
		//_d($obj1->val1); //Ошибка рантайма
		_d($obj1->val1(), $obj1->val2());
		//вызов не статического метода не использующего $this в статическом контексте
		//_d(Obj::counter(), $obj1::counter()); //php<=8.0
		
		echo '===== Позднее статическое связывание (LSB - late static binding) =====<br />';
		_d(Obj::getCalledClass(), Obj::earlySb(), Obj::earlySbConst(), Obj::lateSb(), Obj::lateSbConst());
		_d(ChildObj::getCalledClass(), ChildObj::earlySb(), ChildObj::earlySbConst(), ChildObj::lateSb(), ChildObj::lateSbConst(), ChildObj::parentSb());
		_d(ChildChildObj::getCalledClass(), ChildChildObj::earlySb(), ChildChildObj::earlySbConst(), ChildChildObj::lateSb(), ChildChildObj::lateSbConst(), ChildChildObj::parentSb(), ChildChildObj::grandParentSb());
		
		echo '===== Позднее статическое связывание (forward_static_call, call_user_func) =====<br />';
		_d(ChildChildObj::fscCuf());
	}
}

class Obj {
	const _SB = 'Obj';
	const _FSC_CUF = 'Obj';
	public static $val1 = 7;
	public static $_sb  = 'Obj';
	public static function val1()
	{
		//_d($this); //Ошибка рантайма
		return 8;
	}
	static public function val2()
	{
		//_d($this); //Ошибка рантайма
		return 9;
	}
	public function counter()
	{
		static $counter = 0;
		return ++$counter;
	}
	public static function earlySb()
	{
		return self::$_sb;
	}
	public static function lateSb()
	{
		return static::$_sb;
	}
	public static function earlySbConst()
	{
		return self::_SB;
	}
	public static function lateSbConst()
	{
		return static::_SB;
	}
	public static function getCalledClass()
	{
		return [__CLASS__, get_called_class()];
	}
	public static function fscCuf()
	{
        return static::_FSC_CUF;
    }
}

class ChildObj extends Obj
{
	const _SB = 'ChildObj';
	const _FSC_CUF = 'ChildObj';
	public static $_sb  = 'ChildObj';
	public static function parentSb()
	{
		return parent::$_sb;
	}
	public static function getCalledClass()
	{
		return [__CLASS__, get_called_class()];
	}
	public static function fscCuf()
	{
        return static::_FSC_CUF;
    }
}

class ChildChildObj extends ChildObj
{
	const _SB = 'ChildChildObj';
	const _FSC_CUF = 'ChildChildObj';
	public static $_sb  = 'ChildChildObj';
	public static function parentSb()
	{
		return parent::$_sb;
	}
	public static function grandParentSb()
	{
		return parent::parentSb();
	}
	public static function getCalledClass()
	{
		return [__CLASS__, get_called_class()];
	}
	public static function fscCuf()
	{
		$fscCuf   = [];
		$fscCuf []= forward_static_call(['parent', 'fscCuf']);
		$fscCuf []= call_user_func(['parent', 'fscCuf']);

		$fscCuf []= forward_static_call([ChildObj::class, 'fscCuf']);
		$fscCuf []= call_user_func([ChildObj::class, 'fscCuf']);
		
		$fscCuf []= forward_static_call([Obj::class, 'fscCuf']);
		$fscCuf []= call_user_func([Obj::class, 'fscCuf']);
		return $fscCuf;
    }
}
