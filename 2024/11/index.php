<?php

const PART01_ITERATIONS = 25;
const PART02_ITERATIONS = 75;

$cache = [];

function part01(string $input): ?int
{
    $stones = explode(' ', $input);

    $current = $stones;

    for ($iteration = 0; $iteration < PART01_ITERATIONS; ++$iteration) {
        // Since the array is iterated in order, we can basically just build a new array and swap it out
        // This leaves the messy array re-writing and index stuff out of mind
        $new = [];

        foreach ($current as $stone) {
            if ($stone == 0) {
                $new[] = '1';
                continue;
            }

            if (strlen($stone) % 2 == 0) {
                [$left, $right] = str_split($stone, strlen($stone) / 2);

                // Remove leading zeros in a hacky way
                $left = (string) (int) $left;
                $right = (string) (int) $right;

                $new[] = $left;
                $new[] = $right;

                continue;
            }

            $new[] = (string) ($stone * 2024);
        }

        $current = $new;
    }

    return count($current);
}

function part02(string $input): ?int
{
    global $cache;

    $stones = explode(' ', $input);

    $total = 0;

    // We don't really have to process all stones at once, we can actually just look at one stone at a time and follow its path.
    // When a stone splits into two we continue to follow both paths until we reach our maximum iterations and add one to our accumulating counter.
    foreach ($stones as $value) {
        $total += blink((int) $value, PART02_ITERATIONS);

        var_dump($cache);

        die;
    }

    return $total;
}

function blink(int $stone, int $iteration): int
{
    // Given: The changes that occur to a stone are deterministic, one value is always going to turn into another, etc. and we're only looking at one stone at a time.
    // Therefore: We are able to tell given a specific number at a specific iteration, what the total number of stones found currently is.
    global $cache;
    
    $key = "{$stone}-{$iteration}";

    if (isset($cache[$key])) {
        return $cache[$key];
    }

    // This is basically the worlds most complicated foreach loop and counter.
    if ($iteration == 0) {
        $computed = 1;
    } elseif ($stone == 0) {
        $computed = blink(1, $iteration - 1);
    } elseif (strlen($stone) % 2 == 0) {
        // This is essentially spawning two new paths to traverse and go down the iterations and then sum the results.
        [$left, $right] = str_split($stone, strlen($stone) / 2);
        $computed = blink((int) $left, $iteration - 1) + blink((int) $right, $iteration - 1);
    } else {
        $computed = blink($stone * 2024, $iteration - 1);
    }

    $cache[$key] = $computed;

    return $computed;
}

function main(): void
{
    $part01_example_input = file_get_contents('./part_01_example.txt');

    $part01_example_output = part01($part01_example_input);
    $part02_example_output = part02($part01_example_input);

    echo "Part 01 Example - {$part01_example_output}\n";
    echo "Part 02 Example - {$part02_example_output}\n";

    assert($part01_example_output === 55312, 'Part 01 - Example output does not equal 55312');
    // assert($part02_example_output === 0, 'Part 02 - Example output does not equal 0');

    $part01_input = file_get_contents('./part_01_input.txt');

    $part01_output = part01($part01_input);

    echo "Part 01 - {$part01_output}\n";

    $part02_input = file_get_contents('./part_02_input.txt');

    $part02_output = part02($part02_input);

    echo "Part 02 - {$part02_output}\n";
}

main();