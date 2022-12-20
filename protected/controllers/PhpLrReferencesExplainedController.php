<?php
/**
 * @link http://test-station.local/
 */

namespace app\controllers;

/**
 * PhpLrReferencesExplainedController class.
 * @link https://www.php.net/manual/ru/language.references.php
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class PhpLrReferencesExplainedController extends \app\components\Controller
{
    public function actionIndex()
    {
		echo <<<HTML
			Руководство по PHP - Справочник языка - Объяснение ссылок
HTML;
		exit(0);
    }

	public function actionReferencesExplained()
	{
		\app\helpers\php\ReferencesExplained::run();
		exit(0);
	}
}
