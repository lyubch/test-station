<?php

namespace app\components;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;
use yii\helpers\FileHelper;

/**
 * @link https://www.php.net/manual/ru/index.php
 */
class Controller extends \yii\web\Controller
{
	public function beforeAction($action)
	{
		$this->printHeader();
		$this->printFirstNavBar();
		$this->printSecondNavBar();

		return parent::beforeAction($action);
	}

	public function printHeader()
	{
		echo 'Current PHP version: ' . substr(phpversion(), 0, 3) . '</br></br>';
	}

	public function printFirstNavBar()
	{
		$path = realpath(__DIR__ . '/../controllers');
		$controllers = FileHelper::findFiles($path);
		$urls = [];
		foreach ($controllers as $controller) {
			$controller = rtrim(str_replace($path . '/', null, $controller), '.php');
			$text = substr($controller, 0, strlen($controller) - 10);
			$url  = Inflector::camel2id($text);
			$urls[$text] = Url::toRoute([$url . '/index']);
		}
		$sequence = ['site', 'habr', 'php'];
		uksort($urls, function($a, $b) use($sequence) {
			$sequence = array_flip(array_combine(
				array_keys(array_fill(1, count($sequence), null)),
				array_reverse($sequence)
			));
			$priority       = function($value) use($sequence) {
				foreach ($sequence as $needle => $_priority) {
					$pos = strpos(strtolower($value), $needle);
					if ($pos === 0) {
						return $_priority;
					}
				}
				return 0;
			};
			return $priority($a) <= $priority($b);
		});
		$i  = 0;
		$prev = $curr = null;
		array_push($sequence, 0);
		foreach ($urls as $text => $url) {
			for ($_i=0; $_i<count($sequence); $_i++) {
				$pos = strpos(strtolower($text), strtolower($sequence[$_i]));
				if ($pos === 0) {
					$curr = $sequence[$_i];
				}
			}
			echo ($i?($prev!==$curr?' | ':' - '):null) . Html::a($text, $url);
			$i++;
			$prev = $curr;
		}
		echo '</br></br>';
	}

	public function printSecondNavBar()
	{
		$actions = array_filter(get_class_methods($this), function($action) {
			if (strpos($action, 'action') !== false) {
				$name = substr($action, 6);
				return $name === ucfirst($name);
			}
			return false;
		});
		$urls = [];
		foreach ($actions as $action) {
			$text = substr($action, 6);
			if ($text === 'Index') continue;
			$url  = Inflector::camel2id($text);
			$urls[$text] = Url::toRoute([$this->getUniqueId() . '/' . $url]);
		}
		$i = 0;
		$c = count($urls);
		foreach ($urls as $text => $url) {
			$i++;
			echo Html::a($text, $url) . ($i<$c?' - ':null);
		}
		echo '</br></br>';
	}
}
