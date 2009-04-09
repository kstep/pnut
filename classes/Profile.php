<?php
final class Profile
{
	static private $_stat = array();
	static private $_maxstat = array();
	static private $_totstat = array();
	static private $_runstat = array();

	private function __construct() {}
	private function __clone() {}

	static public function start($func)
	{
		self::$_stat[$func] = microtime(true);
	}
	static public function stop($func)
	{
		self::$_stat[$func] = microtime(true) - self::$_stat[$func];
		self::$_totstat[$func] += self::$_stat[$func];
		if (self::$_stat[$func] > self::$_maxstat[$func])
			self::$_maxstat[$func] = self::$_stat[$func];
		self::$_runstat[$func]++;
	}

	static public function getStat()
	{
		$result = array();
		foreach (self::$_stat as $func => $time)
		{
			$result[$func] = array(
				'ntimes' => self::$_runstat[$func],
				'max'    => self::$_maxstat[$func],
				'total'  => self::$_totstat[$func],
				'avg'    => self::$_totstat[$func] / self::$_runstat[$func],
				'last'   => $time,
			);
		}	
		uasort($result, create_function('$a,$b', 'return $a["total"] < $b["total"]? 1: ($a["total"] == $b["total"]? 0: -1);'));
		return $result;
	}
}
?>
