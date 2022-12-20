<?php
/**
 * @link http://test-station.local/
 */

namespace app\helpers\php;

/**
 * BasicSyntax class.
 * $link https://www.php.net/manual/ru/language.basic-syntax.php
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class BasicSyntax
{
	public static function run()
	{
		echo '<h4><<<Основы синтаксиса>>></h4>';
		$obj1 = new Obj1;
		
		echo '===== Теги PHP (Закрывающий тэг PHP `?>` в конце файла является не желательным из-за риска добавления нежелательных пробелов) =====<br />';
		_d(0);
		echo '===== Теги PHP (При выводе больших блоков текста выходить из режима `' . htmlspecialchars('<?= ... ?>') . '`/`' . htmlspecialchars('<?php echo ... ?>') . '` более эффективно) =====<br />';
		?>Эта строка не обрабатывается php парсером</br></br><?php
		
		echo '===== Теги PHP (Перед закрывающим тегом `' . htmlspecialchars('?>') . ' разделитель `;` ставить не обязательно, PHP его добавляет автоматически) =====<br />';
		_d(1) ?><?php //_d(1) //Ошибка
		
		echo '===== Теги PHP (Комментарии) =====<br />';
		/* _d(1); Это комментарий */
		// _d(1); Это комментарий
		# _d(1); Это комментарий
		_d(2);
	}
}

class Obj1 {}
