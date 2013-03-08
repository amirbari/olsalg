<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="no" xml:lang="no">
<head>
<title>Ølsalget</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
</head>

<body>
<?php

$days = array(
	'søndag',
	'mandag',
	'tirsdag',
	'onsdag',
	'torsdag',
	'fredag',
	'lørdag');

$year = date('Y');
$easter = easter_date($year);

$time = time();

if (isset($_GET['days']) && is_numeric($_GET['days'])) {
	$time += 86400*$_GET['days'];
}

$dayofweek = date('w', $time);
$dayofyear = date('z', $time);

$specialday = array(
	date('z', $easter)-4 => array("påske onsdag", "18:00"), // 
	date('z', $easter)-1 => array("påskeaften", "15:00"), // påskeaften
	date('z', $easter)+48 => array("pinseaften", "15:00"), // pinseaften
	date('z', mktime(0,0,0,12,24,$year)) => array("juleaften", "15:00"), // julaften
	date('z', mktime(0,0,0,12,31,$year)) => array("nyttårsaften", "15:00"), // nyttårsaften
	date('z', $easter)-3 => array("skjærtorsdag", 0), // skjærtorsdag
	date('z', $easter)-2 => array("langfredag", 0), // langfredag
	date('z', $easter) => array("1. påskedag", 0), // 1. påskedag
	date('z', $easter)+1 => array("2. påskedag", 0), // 1. påskedag
	date('z', mktime(0,0,0,5,1,$year)) => array("arbeidernes dag", 0), // arbeidernesdag
	date('z', mktime(0,0,0,5,17,$year)) => array("17. mai! jippi!", 0), // 17. mai!!!
	date('z', $easter)+39 => array("himmelsprettern'", 0), // kristihimmelfartsdag
	date('z', $easter)+49 => array("1. pinsedag", 0), // 1. pinsedag
	date('z', $easter)+50 => array("2. pinsedag", 0), // 2. pinsedag
	date('z', mktime(0,0,0,12,25,$year)) => array("1. juledag", 0), // 1. juledag
	date('z', mktime(0,0,0,12,26,$year)) => array("2. juledag", 0) // 2. juledag
);

if (isset($_GET['debug'])) {
	echo "<pre>";
	var_dump($specialday);
	echo "</pre>";
}

echo $days[$dayofweek];

if (array_key_exists($dayofyear, $specialday)) {
	if ($specialday[$dayofyear][1] == 0) {
		echo "({$specialday[$dayofyear][0]}): ølsalget er stengt";
	} else {
		echo "({$specialday[$dayofyear][0]}): ølsalget stenger kl. {$specialday[$dayofyear][1]}";
	}
} else {
	switch ($dayofweek) {
	case 0:
		echo ": ølsalget er stengt";
		break;
	case 6:
		echo ": ølsalget stenger kl. 18:00";
		break;
	case 1:
		echo ": ølsalget stenger kl. 20:00";
		if (date('n', $time) == 9) {
			echo " (obs. mulig valgdag[stengt])";
		}
		break;
	default:
		echo ": ølsalget stenger kl. 20:00";
		break;
	}
}

for ($k = 1;$k <= 4; $k++) {

	if (date('w', $time+86400*$k) == 1 && date('n', $time+86400*$k) == 9) {
		echo " (obs. mulig valgdag[stengt] på mandag)";
	}

	for ($i = $dayofyear + $k, $j = $k;(array_key_exists($i, $specialday) && $specialday[$i][1] == 0) 
		|| (date('w', $time + 86400*$j) == 0 && $j != $k);$i++,$j++ );

	if (isset($specialday[$i]) && $specialday[$i][1] != 0) {
		$date = date('w', $time + 86400*$j);
		echo " (obs. stenger {$specialday[$i][1]} på {$days[$date]})";
	}


	if ($j != $k) {
		$date1 = date("w", $time + 86400*$k);
		$date2 = date("w", $time + 86400*($j-1));

		if ($j == ($k + 1)) {
			echo " (obs. stengt {$days[$date1]})";
		} else {
			echo " (obs. stengt fra {$days[$date1]} til og med {$days[$date2]})";
		}
		break;
	}
}

?>
</body>
</html>
