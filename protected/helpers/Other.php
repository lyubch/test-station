<?php
/**
 * @link http://test-station.local/
 */

namespace app\helpers;

/**
 * Other class.
 *
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class Other
{
	public static function run()
	{
		echo '===== Функция array_walk (проходит по массиву меняя его значения (не ключи)) =====<br />';
		$list1 = [1, 2, 3];
		_d($list1);
		array_walk($list1, function(&$value, $key) {
			$key   += 1;
			$value .= 'AW';
		});
		_d($list1);
		
		echo '===== [php>=7.1] Функция list (краткий синтаксис [...]) =====<br />';
		$list2 = ['A', 'B', 'C'];
		list($a1, $b1, $c1) = $list2;
		_d($a1, $b1, $c1);
		if (PHP_VERSION >= 7.1) {
			eval('[$a1_1, $b1_1, $c1_1] = $list2;');
			_d($a1_1, $b1_1, $c1_1);
		}
		
		list($a2,, $c2) = $list2;
		_d($a2, $c2);
		if (PHP_VERSION >= 7.1) {
			eval('[$a2_1,, $c2_1] = $list2;');
			_d($a2_1, $c2_1);
		}
		
		list(,, $c3) = $list2;
		_d($c3);
		if (PHP_VERSION >= 7.1) {
			eval('[,, $c3_1] = $list2;');
			_d($c3_1);
		}
		
		list($a4) = $list2;
		_d($a4);
		if (PHP_VERSION >= 7.1) {
			eval('[$a4_1] = $list2;');
			_d($a4_1);
		}
		
		$list3 = ['A', ['B', 'C']];
		list(, list(, $c5)) = $list3;
		_d($c5);
		if (PHP_VERSION >= 7.1) {
			eval('[, [, $c5_1]] = $list3;');
			_d($c5_1);
		}
		
		$list4    = [2=>'A', 'xyz'=>'B', 0=>'C'];
		$list4[1] = 'D';
		list($a6, $b6, $c6) = $list4;
		_d($a6, $b6, $c6);
		if (PHP_VERSION >= 7.1) {
			eval('[$a6_1, $b6_1, $c6_1] = $list4;');
			_d($a6_1, $b6_1, $c6_1);
		}
		
		if (PHP_VERSION >= 7.1) {
			$list5 = [
				['a'=>100, 'b'=>200],
				['a'=>150, 'b'=>250],
			];
			eval('
				foreach ($list5 as ["a"=>$a7, "b"=>$b7]) {
					_d($a7, $b7);
				}
			');
			$list6 = ['000', '111', '222', '333'];
			eval('list(1=>$b8, 3=>$d8) = $list6;');
			_d($b8, $d8);
			eval('[1=>$b8, 3=>$d8] = $list6;');
			_d($b8, $d8);
		}
		

		
	}
}

class Obj {}
