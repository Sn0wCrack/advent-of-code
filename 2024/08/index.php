<?php

function part01(string $input): ?int
{
    $map = array_map('str_split', explode("\n", $input));

    $height = count($map) - 1;
    $width = count($map[0]) - 1;

    $antennas = [];

    foreach ($map as $y => $column) {
        foreach ($column as $x => $item) {
            if (! is_antenna($item)) {
                continue;
            }

            $antennas[] = [
                'frequency' => $item,
                'x' => $x,
                'y' => $y,
            ];
        }
    }

    $antinodes = [];

    foreach ($antennas as $a) {
        foreach ($antennas as $b) {
            if ($a['frequency'] !== $b['frequency'] || $a === $b) {
                continue;
            }

            // Find the delta between A and B
            $distanceX = $b['x'] - $a['x'];
            $distanceY = $b['y'] - $a['y'];

            // Using A as our starting point essentially, get the point that is the same distance away
            // as Point A is from Point B and check if that's in the map, if it is, that's an antinode
            $adjustedX = $b['x'] + $distanceX;
            $adjustedY = $b['y'] + $distanceY;

            if ($adjustedX < 0 || $adjustedY < 0 || $adjustedX > $width || $adjustedY > $height) {
                continue;
            }

            $antinodes["{$adjustedX},{$adjustedY}"] = true;
        }
    }

    return count($antinodes);
}

function part02(string $input): ?int
{
    $map = array_map('str_split', explode("\n", $input));

    $height = count($map) - 1;
    $width = count($map[0]) - 1;

    $antennas = [];

    foreach ($map as $y => $column) {
        foreach ($column as $x => $item) {
            if (! is_antenna($item)) {
                continue;
            }

            $antennas[] = [
                'frequency' => $item,
                'x' => $x,
                'y' => $y,
            ];
        }
    }

    $antinodes = [];

    foreach ($antennas as $a) {
        foreach ($antennas as $b) {
            if ($a['frequency'] !== $b['frequency'] || $a === $b) {
                continue;
            }

            $distanceX = $b['x'] - $a['x'];
            $distanceY = $b['y'] - $a['y'];

            // Essentially like Part 1, but in a marching fashion
            // This time we start from Point A and march forward the distance until we hit the edge of our map
            $adjustedX = $a['x'];
            $adjustedY = $a['y'];

            while (true) {
                $adjustedX += $distanceX;
                $adjustedY += $distanceY;

                if ($adjustedX < 0 || $adjustedY < 0 || $adjustedX > $width || $adjustedY > $height) {
                    break;
                }
    
                $antinodes["{$adjustedX},{$adjustedY}"] = true;
            }
        }
    }

    return count($antinodes);
}

function is_antenna(string $character): bool
{
    return preg_match('/[A-Za-z0-9]/', $character) === 1;
}

function main(): void
{
    $part01_example_input = file_get_contents('part_01_example.txt');

    $part01_example_output = part01($part01_example_input);
    $part02_example_output = part02($part01_example_input);

    echo "Part 01 Example - {$part01_example_output}\n";
    echo "Part 02 Example - {$part02_example_output}\n";

    assert($part01_example_output === 14, 'Part 01 - Example output does not equal 14');
    assert($part02_example_output === 34, 'Part 02 - Example output does not equal 34');

    $part01_input = file_get_contents('part_01_input.txt');

    $part01_output = part01($part01_input);

    echo "Part 01 - {$part01_output}\n";

    $part02_input = file_get_contents('part_02_input.txt');

    $part02_output = part02($part02_input);

    echo "Part 02 - {$part02_output}\n";
}

main();