<?php
/**
 * @link http://test-station.local/
 */

namespace app\controllers;

/**
 * SiteController class.
 * @link https://www.php.net/manual/ru/index.php
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class SiteController extends \app\components\Controller
{
    public function actionIndex()
    {
		echo <<<HTML
			TODO
HTML;
		exit(0);
    }

	public function actionOther()
	{
		\app\helpers\Other::run();
		exit(0);
	}

	public function actionPopulationGrowthForecast()
	{
		\app\helpers\PopulationGrowthForecast::run();
		exit(0);
	}
}
