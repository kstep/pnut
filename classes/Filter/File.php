<?php
class Filter_File
{
	private static $_suffixes = array( "б", "Кб", "Мб", "Гб", "Тб" );

	public static function humanizeSize($size)
	{
		$maxpower = count(self::$_suffixes);
		for ($i = 0; $size > 1024 && $i < $maxpower; $i++) $size /= 1024;
		return sprintf("%0.3f %s", $size, self::$_suffixes[$i]);
	}
}
?>
