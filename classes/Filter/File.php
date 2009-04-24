<?php
class Filter_File
{
	private static $_suffixes = array( "б", "Кб", "Мб", "Гб", "Тб" );

	public static function humanizeSize($size, $round = 3)
	{
		$maxpower = count(self::$_suffixes);
		for ($i = 0; $size > 1024 && $i < $maxpower; $i++) $size /= 1024;
		return sprintf("%0.{$round}f %s", $size, self::$_suffixes[$i]);
	}
}
?>
