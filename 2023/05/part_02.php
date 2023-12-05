<?php

const SEEDS_LINE_IDENTIFIER = 'seeds';

const SEED_TO_SOIL_IDENTIFIER = 'seed-to-soil';

const SOIL_TO_FERTILIZER_IDENTIFIER = 'soil-to-fertilizer';

const FERTILIZER_TO_WATER_IDENTIFIER = 'fertilizer-to-water';

const WATER_TO_LIGHT_IDENTIFIER = 'water-to-light';

const LIGHT_TO_TEMPERATURE_IDENTIFIER = 'light-to-temperature';

const TEMPERATURE_TO_HUMIDITY_IDENTIFIER = 'temperature-to-humidity';

const HUMIDITY_TO_LOCATION_IDENTIFIER = 'humidity-to-location';

const MAP_IDENTIFIERS = [
    SEED_TO_SOIL_IDENTIFIER,
    SOIL_TO_FERTILIZER_IDENTIFIER,
    FERTILIZER_TO_WATER_IDENTIFIER,
    WATER_TO_LIGHT_IDENTIFIER,
    LIGHT_TO_TEMPERATURE_IDENTIFIER,
    TEMPERATURE_TO_HUMIDITY_IDENTIFIER,
    HUMIDITY_TO_LOCATION_IDENTIFIER,
];

$lines = file('part_01_input.txt', FILE_IGNORE_NEW_LINES);

$parsing = null;

$seeds = [];

$maps = [
    SEED_TO_SOIL_IDENTIFIER => [],
    SOIL_TO_FERTILIZER_IDENTIFIER => [],
    FERTILIZER_TO_WATER_IDENTIFIER => [],
    WATER_TO_LIGHT_IDENTIFIER => [],
    LIGHT_TO_TEMPERATURE_IDENTIFIER => [],
    TEMPERATURE_TO_HUMIDITY_IDENTIFIER => [],
    HUMIDITY_TO_LOCATION_IDENTIFIER => [],
];

foreach ($lines as $line) {
    if (empty($line)) {
        $parsing = null;
        continue;
    }

    if (str_starts_with($line, SEEDS_LINE_IDENTIFIER)) {

        if (preg_match_all('/(\d+ \d+)/', $line, $pairs) === false) {
            exit;
        }

        foreach ($pairs[1] as $pair) {
            [$start, $length] = explode(' ', $pair);

            $seeds[] = [
                'start' => (int) $start,
                'end' => $start + ($length - 1)
            ];
        }

        continue;
    }

    if ($parsing === null) {
        foreach (MAP_IDENTIFIERS as $identifier) {
            if (str_starts_with($line, $identifier)) {
                $parsing = $identifier;
                break;
            }
        }

        continue;
    }

    $map = array_map(fn (string $number) => (int) $number, explode(' ', $line));

    $maps[$parsing][] = [
        'destination_range_start' => $map[0],
        'destination_range_end' => $map[0] + ($map[2] - 1),
        'source_range_start' => $map[1],
        'source_range_end' => $map[1] + ($map[2] - 1),
    ];
}

$lowest = null;

foreach ($seeds as $seed) {
    $start = microtime(true);

    var_dump($seed, $seed['end'] - $seed['start']);

    for ($i = $seed['start']; $i <= $seed['end']; ++$i) {
        $identifierToLookup = $i;
        $seedRelationshipIdentifiers = ['seed' => $i];

        foreach (MAP_IDENTIFIERS as $mapIdentifier) {
            $seedRelationshipIdentifiers[$mapIdentifier] = $identifierToLookup;

            foreach ($maps[$mapIdentifier] as $map) {
                if ($identifierToLookup >= $map['source_range_start'] && $identifierToLookup <= $map['source_range_end']) {
                    $difference = $identifierToLookup - $map['source_range_start'];

                    $seedRelationshipIdentifiers[$mapIdentifier] = $map['destination_range_start'] + $difference;
                    $identifierToLookup = $map['destination_range_start'] + $difference;
                    break;
                }
            }
        }

        if ($lowest === null || $lowest > $seedRelationshipIdentifiers[HUMIDITY_TO_LOCATION_IDENTIFIER]) {
            $lowest = $seedRelationshipIdentifiers[HUMIDITY_TO_LOCATION_IDENTIFIER];
        }
    }

    $end = microtime(true);

    var_dump($end - $start);
}

echo "{$lowest}\n";
