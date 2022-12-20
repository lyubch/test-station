<?php
/**
 * @link http://test-station.local/
 */

namespace app\controllers;

/**
 * PhpLrOperatorsController class.
 * @link https://www.php.net/manual/ru/language.operators.php
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class PhpLrOperatorsController extends \app\components\Controller
{
    public function actionIndex()
    {
		echo <<<HTML
			Руководство по PHP - Справочник языка - Операторы
HTML;
		exit(0);
    }

	public function actionExecutionStatements()
	{
		\app\helpers\php\ExecutionStatements::run();
		exit(0);
	}
	
	public function actionOperatorsString()
	{
		\app\helpers\php\OperatorsString::run();
		exit(0);
	}
}
