<?php

$lines = file('part_01_input.txt', FILE_IGNORE_NEW_LINES);

$total = 1;

$time = 0;
$distance = 0;

foreach ($lines as $line) {
    if (str_starts_with($line, 'Time:')) {
        $numbers = preg_split('/\s+/', $line);

        $fullNumber = "";

        foreach ($numbers as $number) {
            if (!is_numeric($number)) {
                continue;
            }

            $fullNumber .= $number;
        }

        $time = (int) $fullNumber;
    }

    if (str_starts_with($line, 'Distance:')) {
        $numbers = preg_split('/\s+/', $line);

        $fullNumber = "";

        foreach ($numbers as $number) {
            if (!is_numeric($number)) {
                continue;
            }

            $fullNumber .= $number;
        }

        $distance = (int) $fullNumber;
    }
}

$totalTime = $time;
$bestDistance = $distance;

$totalViableChargeTimes = 0;

for ($chargeTime = 1; $chargeTime < $totalTime; ++$chargeTime) {
    $timeLeft = $totalTime - $chargeTime;

    $totalDistance = $chargeTime * $timeLeft;

    if ($totalDistance > $bestDistance) {
        $totalViableChargeTimes++;
    }
}


echo "{$totalViableChargeTimes}\n";
