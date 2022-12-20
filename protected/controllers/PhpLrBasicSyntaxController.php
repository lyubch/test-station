<?php
/**
 * @link http://test-station.local/
 */

namespace app\controllers;

/**
 * PhpLrBasicSyntaxController class.
 * @link https://www.php.net/manual/ru/language.basic-syntax.php
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class PhpLrBasicSyntaxController extends \app\components\Controller
{
    public function actionIndex()
    {
		echo <<<HTML
			Руководство по PHP - Справочник языка - Основы синтаксиса
HTML;
		exit(0);
    }

	public function actionBasicSyntax()
	{
		\app\helpers\php\BasicSyntax::run();
		exit(0);
	}
}
