<?php
/**
 * @link http://test-station.local/
 */

namespace app\helpers\php;

/**
 * ExecutionStatements class.
 * $link https://www.php.net/manual/ru/language.operators.execution.php
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class ExecutionStatements
{
	public static function run()
	{
		echo '<h4><<<Операторы исполнения>>></h4>';
		$obj1 = new Obj1;
		
		echo '===== Оператор обратные кавычки `` (аналогично использованию функции `shell_exec`, недоступны, в случае, если отключена функция `shell_exec`) =====<br />';
		$var1 = `ls -al`;
		_d($var1, `ls`);

		echo '===== Оператор обратные кавычки `` (не работают внутри строк) =====<br />';
		echo "<pre>`ls`</pre>";
		echo '<pre>`ls`</pre>';
		echo <<<HTML
			<pre>`ls`</pre>
HTML;
		echo "<pre>$var1</pre>";
		
		
	}
}

class Obj1 {}
