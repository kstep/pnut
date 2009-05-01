<?php
class Filter_Text
{
	const STRIP_LEFT = 0;
	const STRIP_RIGHT = 1;
	const STRIP_CENTER = 2;
	const STRIP_BOTH = 3;

	static public function strip($text, $length, $side = self::STRIP_RIGHT, $hellip = "&hellip;")
	{
		if (strlen($text) > $length)
		{
			switch ($side)
			{
				case self::STRIP_LEFT:
					$text = $hellip . substr($text, -$length);
					break;
				case self::STRIP_RIGHT:
					$text = substr($text, 0, $length) . $hellip;
					break;
				case self::STRIP_CENTER:
					$half = floor($length / 2);
					$text = substr($text, 0, $half) . $hellip . substr($text, -$half);
					break;
				case self::STRIP_BOTH:
					$half = floor((strlen($text) - $length) / 2);
					$text = $hellip . substr($text, $half, $length) . $hellip;
					break;
			}
		}
		return $text;
	}
}
?>
