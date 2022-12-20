<?php
/**
 * @link http://test-station.local/
 */

namespace app\helpers;

/**
 * PopulationGrowthForecast class.
 *
 * @author Anton Lyubchenko <anton.lyubch@gmail.com>
 */
class PopulationGrowthForecast
{
	public static function run()
	{
		echo '<h4><<<Прогноз роста населения>>></h4>';
		$peoples = new Peoples([
			'defaultCount'   => 150_000_000,
			'avgBegetsYears' => [20, 23, 26],
		]);
		$peoples->live(100);
		d(number_format($peoples->getTotalCount(), 0, '.', '_'), $peoples->getIterator(), DD10);
	}
}

class Peoples implements \IteratorAggregate {
	public int $defaultCount     = 100_000;
	public int $avgOld           = 70;
	public array $avgBegetsYears = [20, 23];
	public int $avgBegetsCount   = 1;

	protected iterable $_stack = [];

	public function __construct(array $options = []) {
		if ($options !==[]) {
			foreach ($options as $key => $value) {
				$this->{$key} = $value;
			}
		}
		$peoplesStackCount = $this->defaultCount / ($this->avgOld + 1);
		for ($age=0; $age<=$this->avgOld; $age++) {
			$count = $peoplesStackCount;
			if (is_float($peoplesStackCount)) {
				if ($age === $this->avgOld) {
					$count = $this->defaultCount - $this->getTotalCount();
				} else {
					$count = ceil($peoplesStackCount);
				}
			}
			$this->_stack []= new PeoplesStack([
				'age'   => $age,
				'count' => $count,
			]);;
		}
	}

	public function getTotalCount(int|null $age = null): int|false {
		if ($age !== null) {
			if (($foundKey=$this->findKey('age', $age)) !== false) {
				return $this->_stack[$foundKey];
			}
			return false;
		}

		return array_sum(array_column($this->_stack, 'count'));
	}

	public function findKey(string $property, int $value): int|false {
		$properties = array_column($this->_stack, $property);
		if (($foundKey=array_search($value, $properties)) !== false) {
			return $foundKey;
		}
		return false;
	}

	public function live(int $years): void {
		for ($y=1; $y<=$years; $y++) {
			foreach ($this as $peoplesStack) {
				$peoplesStack->age += 1;
				if (in_array($peoplesStack->age, $this->avgBegetsYears)) {
					$count = ($peoplesStack->count / 2) * $this->avgBegetsCount;
					if (($foundKey=$this->findKey('age', 0)) !== false) {
						$this->_stack[$foundKey]->count += $count;
					} else {
						$_peoplesStack = new PeoplesStack([
							'age'   => 0,
							'count' => $count,
						]);
						array_unshift($this->_stack, $_peoplesStack);
					}
				}
			}
			if (($foundKey=$this->findKey('age', $this->avgOld + 1)) !== false) {
				unset($this->_stack[$foundKey]);
			}
		}
	}

	public function getIterator(): \Traversable {
		return new \ArrayIterator($this->_stack);
	}
}

class PeoplesStack {
	public int $age;
	public int $count;

	public function __construct(array $options = []) {
		if ($options !==[]) {
			foreach ($options as $key => $value) {
				$this->{$key} = $value;
			}
		}
	}
}
