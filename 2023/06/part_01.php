<?php

$lines = file('part_01_input.txt', FILE_IGNORE_NEW_LINES);

$total = 1;

$times = [];
$distances = [];

foreach ($lines as $line) {
    if (str_starts_with($line, 'Time:')) {
        $numbers = preg_split('/\s+/', $line);

        foreach ($numbers as $number) {
            if (!is_numeric($number)) {
                continue;
            }

            $times[] = (int) $number;
        }
    }

    if (str_starts_with($line, 'Distance:')) {
        $numbers = preg_split('/\s+/', $line);

        foreach ($numbers as $number) {
            if (!is_numeric($number)) {
                continue;
            }

            $distances[] = (int) $number;
        }
    }
}

for ($race = 0; $race < count($times); ++$race) {

    $totalTime = $times[$race];
    $bestDistance = $distances[$race];

    $totalViableChargeTimes = 0;

    for ($chargeTime = 1; $chargeTime < $totalTime; ++$chargeTime) {
        $timeLeft = $totalTime - $chargeTime;

        $totalDistance = $chargeTime * $timeLeft;

        if ($totalDistance > $bestDistance) {
            $totalViableChargeTimes++;
        }
    }

    $total *= $totalViableChargeTimes;
}

echo "{$total}\n";
