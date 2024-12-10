<?php

const DIRECTIONS = [
    ['x' => 0, 'y' => -1], // up,
    ['x' => -1, 'y' => 0], // left,
    ['x' => 1, 'y' => 0], // right,
    ['x' => 0, 'y' => 1], // down
];

const MINIMUM_HEIGHT = 0;
const MAXIMUM_HEIGHT = 9;
const HEIGHT_INCREASE = 1;

function part01(string $input): ?int
{
    $map = array_map(str_split(...), explode("\n", $input));

    $startingPoints = [];

    foreach ($map as $y => $column) {
        foreach ($column as $x => $item) {
            if ($item != 0) {
                continue;
            }

            $startingPoints[] = [
                'value' => (int) $item,
                'x' => $x,
                'y' => $y
            ];
        }
    }

    $score = 0;

    foreach ($startingPoints as $startingPoint) {
        $queue = [$startingPoint];
        $visited = [];

        while (! empty($queue)) {
            $point = array_shift($queue);

            $id = "{$point['x']},{$point['y']}";

            // Skip over previously visited points on this journey.
            if (isset($visited[$id])) {
                continue;
            }

            $visited[$id] = true;

            if ($map[$point['y']][$point['x']] == MAXIMUM_HEIGHT) {
                $score++;
                continue;
            }

            foreach (DIRECTIONS as $offset) {
                $checkX = $point['x'] + $offset['x'];
                $checkY = $point['y'] + $offset['y'];

                if (! isset($map[$checkY][$checkX])) {
                    continue;
                }
                
                $value = $map[$checkY][$checkX];

                if ($point['value'] != $value - HEIGHT_INCREASE) {
                    continue;
                }

                $queue[] = [
                    'value' => (int) $value,
                    'x' => $checkX,
                    'y' => $checkY,
                ];
            }
        }
    }

    return $score;
}

function part02(string $input): ?int
{
    $map = array_map(str_split(...), explode("\n", $input));
    $startingPoints = [];

    foreach ($map as $y => $column) {
        foreach ($column as $x => $item) {
            if ($item != 0) {
                continue;
            }

            $startingPoints[] = [
                'value' => (int) $item,
                'x' => $x,
                'y' => $y
            ];
        }
    }

    $score = 0;

    foreach ($startingPoints as $startingPoint) {
        $queue = [$startingPoint];
        $visited = [];

        while (! empty($queue)) {
            $point = array_shift($queue);
            
            if ($map[$point['y']][$point['x']] == MAXIMUM_HEIGHT) {
                $score++;
                continue;
            }

            foreach (DIRECTIONS as $offset) {
                $checkX = $point['x'] + $offset['x'];
                $checkY = $point['y'] + $offset['y'];

                if (! isset($map[$checkY][$checkX])) {
                    continue;
                }
                
                $value = $map[$checkY][$checkX];

                if ($point['value'] != $value - HEIGHT_INCREASE) {
                    continue;
                }

                $queue[] = [
                    'value' => (int) $value,
                    'x' => $checkX,
                    'y' => $checkY,
                ];
            }
        }
    }

    return $score;
}

function main(): void
{
    $part01_example_input = file_get_contents('./part_01_example.txt');

    $part01_example_output = part01($part01_example_input);
    $part02_example_output = part02($part01_example_input);

    echo "Part 01 Example - {$part01_example_output}\n";
    echo "Part 02 Example - {$part02_example_output}\n";

    assert($part01_example_output === 36, 'Part 01 - Example output does not equal 36');
    assert($part02_example_output === 81, 'Part 02 - Example output does not equal 81');

    $part01_input = file_get_contents('./part_01_input.txt');

    $part01_output = part01($part01_input);

    echo "Part 01 - {$part01_output}\n";

    $part02_input = file_get_contents('./part_02_input.txt');

    $part02_output = part02($part02_input);

    echo "Part 02 - {$part02_output}\n";
}

main();