<?php
class Filter_Date
{
    static protected $monthes = array("нулября", "января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");
    static protected $dows = array("в воскресенье", "в понедельник", "во вторник", "в среду", "в четверг", "в пятницу", "в субботу", "в воскресенье");

    static protected $revmonth = array(
        "янв" => 1,
        "фев" => 2,
        "мар" => 3,
        "апр" => 4,
        "мая" => 5,
        "июн" => 6,
        "июл" => 7,
        "авг" => 8,
        "сен" => 9,
        "окт" => 10,
        "ноя" => 11,
        "дек" => 12,
    );
    static protected $revdow = array(
        "пон" => 1,
        "вто" => 2,
        "сре" => 3,
        "чет" => 4,
        "пят" => 5,
        "суб" => 6,
        "вос" => 0, 
    );

    static protected function daybydow($dow, $month, $year, $week = 0)
    {
        $lastday = localtime(mktime(0, 0, 0, $month + 1, 0, $year));
        $freedays = $lastday[3] % 7;
        $firstdow = (8 + $lastday[6] - $freedays) % 7;
        $day = 1 + (7 + $dow - $firstdow) % 7;

        if ($week)
        {
            if ($week < 0)
                $week += ($day <= $freedays? 5: 4);
            else
                $week--;
            $day += 7 * $week;
        }

        return $day;
    }

    static protected function nextdow($fromdow, $todow)
    {

    }

    /**
     * choose correct word form to complement given number.
     * @param integer number
     * @param string wordform for numbers ending in 1's
     * @param string wordform for numbers ending in 2-4's inclusive
     * @param string wordform for all other numbers (5-9's & 0's)
     * @return string
     */
    static public function wordform($num, $one, $two, $five)
    {
        $digit1 = $num % 10;
        if ($digit1 > 0 && $digit1 < 5)
        {
            $digit2 = $num % 100;
            return ($digit2 > 10 && $digit2 < 15)? $five: ($digit1 == 1? $one: $two);
        }
        else
        {
            return $five;
        }
    }

    /**
     * convert unix timestamp into nice delta time description in natural language.
     * @todo it's in russian only for now, subject for localization.
     * @param integer unix timestamp
     * @return string
     */
    static public function timeAgo($time)
    {
        $now = time();
        $delta = $now - $time;
        $result = "";

        if ($delta < 0) // время в будущем нас не интересует
        {
            $result = "в будущем";
        }
        elseif ($delta < 60) // менее минуты назад, показать секунды
        {
            $result = "$delta ".self::wordform($delta, "секунду", "секунды", "секунд")." назад";
        }
        elseif ($delta < 3600) // от минуты до часа, показать минуты
        {
            $delta = intval($delta / 60);
            $result = "$delta ".self::wordform($delta, "минуту", "минуты", "минут")." назад";
        }
        elseif ($delta < 86400) // от одного часа до суток, показать число часов и минут
        {
            $hours = intval($delta / 3600);
            $delta = intval($delta % 3600 / 60);
            if ($delta > 26 && $delta < 34)
                $result = $hours == 1? "полтора часа назад": "$hours с половиной ".self::wordform($hours, "час", "часа", "часов")." назад";
            else
            {
                $result = "$hours ".self::wordform($hours, "час", "часа", "часов");
                if ($delta > 0)
                    $result .= " $delta ".self::wordform($delta, "минута", "минуты", "минут")." назад";
            }
        }
        elseif ($delta < 2592000) // в районе месяца...
        {
            $days  = intval($delta / 84600);
            $atime = date("H:i", $time);
            if ($days == 1)
                $result = "вчера в $atime";
            elseif ($days == 2)
                $result = "позавчера в $atime";
            elseif ($days < 7 && date("W", $time) == date("W", $now)) // в районе недели...
                $result = self::$dows[date("w", $time)]." в $atime";
            else // более недели назад но в пределах месяца, показать число дней (число недель, если их прошло целое количество) и число месяца
            {
                $weeks = intval($days / 7);
                $weekno = date("n", $time);
                $month = ($weekno != date("n", $now))? "числа": self::$monthes[$weekno];
                $result = ($days % 7 == 0? "$weeks ".self::wordform($weeks, "неделю", "недели", "недель"): "$days ".self::wordform($days, "день", "дня", "дней"))." назад, ".date("j", $time)." числа в $atime";
            }
        }
        else // во всех остальных случаях...
        {
            $month = self::$monthes[date("n", $time)];
            if (date("Y", $now) == date("Y", $time)) // либо в пределах текущего года, показать число, месяц и время
                $result = date("j $month в H:i", $time);
            else // либо в каком либо другом прошедлем году, показать полную дату
                $result = date("j $month Y в H:i", $time);
        }
        return $result;
    }

    public function toString($time, $sec = false)
    {
        if (!$time) return "неизвестно";
        $month = self::$monthes[date("n", $time)];
        return date("j $month Y в H:i".($sec? ':s': ''), $time);
    }

    protected function parseDate(&$time)
    {
        $day = $dow = $week = $month = $year = null;
    
        if (preg_match("/([0-2]?[0-9]|3[01]|посл\S{0,12})\s+(?:(ден|пон|вто|сре|чет|пят|суб|вос)\S{0,18}\s+)?(янв|фев|мар|мар|апр|мая|июн|июл|авг|сен|окт|ноя|дек)\S{0,10}(?:\s+([0-9]{4}|[0-9]{2}))?/", $time, $matches))
        {
            $day   = (int)$matches[1];
            $dow   = self::$revdow[$matches[2]];
            $month = self::$revmonth[$matches[3]];
            $year  = (int)$matches[4];
            $islast = strpos($matches[1], "посл") !== false;
            if ($islast) $day = -1;

            if (!$year) $year = (int)date("Y");
            elseif ($year < 100) $year += $year < 30? 2000: 1900;

            if ($dow !== null)
            {
                $week = $day;
                $day  = 0;
            }
            $time = str_replace($matches[0], "", $time);
        }
        else // время относительное текущего (вчера/сегодня/завтра)
        {
            $now = time();
            if (strpos($time, "завтра") !== false)
            {
                $now += 86400;
                if (strpos($time, "послезавтра") !== false)
                    $now += 86400;
            }
            elseif (strpos($time, "вчера") !== false)
            {
                $now -= 86400;
                if (strpos($time, "позавчера") !== false)
                    $now -= 86400;
            }
            $now = localtime($now);
            //   0,   1,    2,   3,     4,    5,   6,   7,     8
            // sec, min, hour, day, month, year, dow, doy, isdst
            list($day, $month, $year) = array_splice($now, 3, 3);
            $month += 1;
            $year  += 1900;
        }

        return array($day, $dow, $week, $month, $year);
    }

    protected function parseTime($time)
    {
        $sec = $min = $hour = null;
        if (preg_match("/([01][0-9]|2[0-4]|[0-9]):([0-5]?[0-9])(?::([0-5]?[0-9]))?/", $time, $matches))
        {
            $hour = (int)$matches[1];
            $min  = (int)$matches[2];
            $sec  = (int)$matches[3];
        }
        else
        {
            if (preg_match("/полдень|полудн|полдн/", $time))
            {
                $hour = 12;
                $min = $sec = 0;
            }
            elseif (preg_match("/полноч|полун/", $time))
            {
                $hour = $min = $sec = 0;
            }
            elseif (strpos($time, "утром") !== false)
            {
                $hour = 6;
                $min = $sec = 0;
            }
            elseif (strpos($time, "вечером") !== false)
            {
                $hour = 18;
                $min = $sec = 0;
            }
            elseif (strpos($time, "обед") !== false)
            {
                $hour = 14;
                $min = $sec = 0;
            }
            else
            {
                list($sec, $min, $hour) = localtime();
            }
        }
        return array($sec, $min, $hour);
    }

    protected function parsePeriod($time)
    {
        $dsec = $dmin = $dhour = $dday = $ddow = $dweek = $dmonth = $dyear = null;

        $intime = preg_match("/за |перед |до /", $time)? -1: (preg_match("/через |после /", $time)? 1: 0);

        if (preg_match("/([0-9]*)\s*(?:год|лет)/", $time, $matches))
            $dyear = $matches[1]? $intime*$matches[1]: $intime;

        if (preg_match("/([0-9]*)\s*мес/", $time, $matches))
            $dmonth = $matches[1]? $intime*$matches[1]: $intime;

        if (preg_match("/([0-9]*)\s*(пон|вто|сре|чет|пят|суб|вос)/", $time, $matches))
        {
            $dweek = $matches[1]? $intime*$matches[1]: 0;
            $ddow = self::$revdow[$matches[2]];
        }

        if (preg_match("/([0-9]*)\s*нед/", $time, $matches))
            $dweek += $matches[1]? $intime*$matches[1]: $intime;

        if (preg_match("/([0-9]*)\s*(?:ден|дне)/", $time, $matches))
            $dday = $matches[1]? $intime*$matches[1]: $intime;

        if (preg_match("/([0-9]*)\s*час/", $time, $matches))
            $dhour = $matches[1]? $intime*$matches[1]: $intime;

        if (preg_match("/([0-9]*)\s*мин/", $time, $matches))
            $dmin = $matches[1]? $intime*$matches[1]: $intime;

        if (preg_match("/([0-9]*)\s*сек/", $time, $matches))
            $dsec = $matches[1]? $intime*$matches[1]: $intime;

        $exact = ($dyear || $dmonth || $dweek || $ddow || $dday || $dhour || $dmin || $dsec) && !$intime;
        return array($exact, $dsec, $dmin, $dhour, $dday, $ddow, $dweek, $dmonth, $dyear);
    }

    public function fromString($time)
    {
        if (!$time or strpos($time, "сейчас") !== false)
        {
            return time();
        }

        $time = strtolower($time);

        list($day, $dow, $week, $month, $year) = self::parseDate($time);
        list($sec, $min, $hour) = self::parseTime($time);

        list($exact, $dsec, $dmin, $dhour, $dday, $ddow, $dweek, $dmonth, $dyear) = self::parsePeriod($time);

        if ($exact)
        {
            //$day = $month = $year = 0;
            //$sec = $min = $hour = 0;
            $dow  = $ddow;
            $week = $dweek;
        }

        $month += $dmonth;
        $year  += $dyear;
        $day   += $dday;

        if ($dow !== null)
        {
            $day = self::daybydow($dow, $month, $year, $week);
        }

        if ($ddow !== null)
        {
            if ($dow === null)
            {
                $tmp = localtime(mktime(0, 0, 0, $month, $day, $year));
                $dow = $tmp[6];
            }
            $day += ($ddow - $dow) % 7;
        }
        $day += 7*$dweek;

        $hour += $dhour;
        $min  += $dmin;
        $sec  += $dsec;


        if ($hour < 12)
        {
            if (preg_match("/дня|вечера|по полудни/", $time))
                $hour += 12;
        }
        elseif ($hour == 12)
        {
            if (strpos($time, "ночи") !== false)
                $hour = 0;
        }

        return mktime($hour, $min, $sec, $month, $day, $year);
    }

}
?>
