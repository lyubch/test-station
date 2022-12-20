<?php
/**
 * @link http://test-station.local/
 */

namespace app\controllers;

/**
 * PhpLrReservedInterfacesController class.
 * @link https://www.php.net/manual/ru/reserved.interfaces.php
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class PhpLrReservedInterfacesController extends \app\components\Controller
{
    public function actionIndex()
    {
		echo <<<HTML
			Руководство по PHP - Справочник языка - Встроенные интерфейсы и классы
HTML;
		exit(0);
    }

	public function actionClassClosure()
	{
		\app\helpers\php\ClassClosure::run();
		exit(0);
	}
	
	public function actionGenerators()
	{
		\app\helpers\php\Generators::run();
		exit(0);
	}
}
