<?php
/**
 * @link http://test-station.local/
 */

namespace app\controllers;

/**
 * HabrPhpInterviewController class.
 * @link https://habr.com/ru/post/655399/
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class HabrPhpInterviewController extends \app\components\Controller
{
    public function actionIndex()
    {
		echo <<<HTML
			Готовимся к собеседованию по PHP
HTML;
		exit(0);
    }

	public function actionDependencyInjection()
	{
		\app\helpers\habr\DependencyInjection::run();
		exit(0);
	}
	
	public function actionKeywordStatic()
	{
		\app\helpers\habr\KeywordStatic::run();
		exit(0);
	}

	public function actionPseudotypeCallable()
	{
		\app\helpers\habr\PseudotypeCallable::run();
		exit(0);
	}

	public function actionPseudotypeIterable()
	{
		if (PHP_VERSION >= 8) {
			\app\helpers\habr\PseudotypeIterable::run();
		} else {
			_d('REQUIRED: php>=8');
		}
		exit(0);
	}
	
	public function actionAboutInterfaces()
	{
		if (PHP_VERSION >= 8) {
			\app\helpers\habr\AboutInterfaces::run();
		} else {
			_d('REQUIRED: php>=8');
		}
		exit(0);
	}
}
