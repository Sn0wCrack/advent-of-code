<?php

const DIRECTION_INCREASING = 'increasing';
const DIRECTION_DECREASING = 'decreasing';

$part01_example = file('part_01_example.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

echo "Part 01 Example - " . part01($part01_example) . "\n";

echo "Part 02 Example - " . part02($part01_example) . "\n";

assert(part01($part01_example) === 2, 'Part 01 - Example output does not equal 2');

$part01_input = file('part_01_input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

echo "Part 01 - " . part01($part01_input) . "\n";

assert(part02($part01_example) === 4, 'Part 02 - Example output does not equal 0');

$part02_input = file('part_02_input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

echo "Part 02 - " . part02($part02_input) . "\n";

/**
 * @param string[] $input
 */
function part01(array $input): int
{
    $total = 0;

    foreach ($input as $line) {
        $levels = array_map(fn (string $level) => (int) $level, explode(' ', $line));

        if (check_levels_are_safe($levels)) {
            $total++;
        }
    }

    return $total;
}

/**
 * @param string[] $input
 */
function part02(array $input): int
{
    $total = 0;

    foreach ($input as $line) {
        $levels = array_map(fn (string $level) => (int) $level, explode(' ', $line));

        $mutations = generate_level_mutations($levels);

        $safe = false;

        foreach ($mutations as $check) {
            if (check_levels_are_safe($check)) {
                $safe = true;
                break;
            }
        }

        if ($safe) {
            $total++;
        }
    }

    return $total;
}

/**
 * @param int[] $levels
 */
function check_levels_are_safe(array $levels): bool
{
    $safe = true;

    $originalDirection = $levels[0] > $levels[1]
        ? DIRECTION_DECREASING
        : DIRECTION_INCREASING;

    for ($i = 0; $i < count($levels); ++$i) {
        if ($i == count($levels) - 1) {
            continue;
        }

        $first = $levels[$i];
        $second = $levels[$i + 1];

        if ($first == $second) {
            $safe = false;
            break;
        }

        $checkDirection = $first > $second
            ? DIRECTION_DECREASING
            : DIRECTION_INCREASING;

        if ($checkDirection != $originalDirection) {
            $safe = false;
            break;
        }

        $difference = abs($first - $second);

        if ($difference == 0 || $difference > 3) {
            $safe = false;
            break;
        }
    }

    return $safe;
}

/**
 * @param int[] $levels
 * @return array<array-key, int[]>
 */
function generate_level_mutations(array $levels): array
{
    $mutations = [$levels];

    for ($i = 0; $i < count($levels); ++$i) {
        $copy = $levels;
        unset($copy[$i]);
        $mutations[] = array_values($copy);
    }

    return $mutations;
}