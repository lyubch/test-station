<?php
/**
 * @link http://test-station.local/
 */

namespace app\controllers;

/**
 * PhpLrFunctionsController class.
 * @link https://www.php.net/manual/ru/language.functions.php
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class PhpLrFunctionsController extends \app\components\Controller
{
    public function actionIndex()
    {
		echo <<<HTML
			Руководство по PHP - Справочник языка - Функции
HTML;
		exit(0);
    }

	public function actionFunctionArguments()
	{
		\app\helpers\php\FunctionArguments::run();
		exit(0);
	}
	
	public function actionFunctionsAnonymous()
	{
		\app\helpers\php\FunctionsAnonymous::run();
		exit(0);
	}
}
