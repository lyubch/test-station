<?php
/**
 * @link http://test-station.local/
 */

namespace app\controllers;

/**
 * PhpLrTypesController class.
 * @link https://www.php.net/manual/ru/language.types.php
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class PhpLrTypesController extends \app\components\Controller
{
	/**
	 * ===Специальный тип===
	 * null - регистронезависимая константа `null`
	 * 
	 * ===Скалярные типы===
	 * bool - буквенный тип из 2 регистронезависимых констант `true`/`false`
	 * int
	 * float
	 * string
	 * 
	 * ===Комбинированные типы===
	 * array
	 * object
	 * 
	 * ===Псевдо типы===
	 * callable
	 * resource
	 * 
	 * ===Псевдонимы типов===
	 * mixed    - object|resource|array|string|float|int|bool|null
	 * iterable - Traversable|array
	 * nullable - ?*
	 * integer  - int
	 * double   - float
	 * boolean  - bool
	 * 
	 * ===`Return-only` типы===
	 * void
	 * never
	 * 
	 * ===`Relative-class` типы===
	 * self
	 * parent
	 * static
	 * 
	 * ===Пользовательские типы===
	 * Interfaces
	 * Classes
	 * Enumerations
	 * 
	 * ===Intersection Types===
	 * A&B&C
	 * 
	 * ===Union Types===
	 * A|B|C
	 * A|(B&C)
	 * 
	 * ???
	 * http://php.adamharvey.name/manual/ru/language.pseudo-types.php
	 * 
	 */
    public function actionIndex()
    {
		echo <<<HTML
			Руководство по PHP - Справочник языка - Типы
HTML;
		exit(0);
    }

	public function actionTypeNull()
	{
		\app\helpers\php\type\TypeNull::run();
		exit(0);
	}
	
	public function actionTypeBool()
	{
		if (PHP_VERSION >= 7.4) {
			\app\helpers\php\type\TypeBool_v7::run();
		} else {
			\app\helpers\php\type\TypeBool_v5::run();
		}
		exit(0);
	}
}
