<?php
/**
 * @link http://test-station.local/
 */

namespace app\helpers\habr;

/**
 * DependencyInjection class.
 * $link https://habr.com/ru/post/655399/
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class DependencyInjection
{
	/**
	 * DI - Внедрение зависимости (Dependency Injection)
	 */
	public static function run()
	{
		echo '<h4><<< DI - механизм разрешения зависимостей >>></h4>';

		echo '===== DI - подготовка базовых классов (проблемы - в каждом месте где нам нужен обьект мы его создаем новый `new Class`) =====<br />';
		_d(di\p1\EntryPoint::run());
		
		echo '===== DI - внедрение зависимостей с помощью сеттеров (объекты-зависимости передаются извне, а не создаются на месте) =====<br />';
		_d(di\p2\EntryPoint::run());
		
		
		
		
	}
}
