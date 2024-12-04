<?php

const DO_TOKEN = 'do()';
const DO_NOT_TOKEN = "don't()";

function part01(string $input): ?int
{
    $result = preg_match_all('/mul\((?P<first>\d{1,3}),(?P<second>\d{1,3})\)/im', $input, $matches, PREG_SET_ORDER);

    if ($result === false) {
        return null;
    }

    $total = 0;

    foreach ($matches as $match) {
        $first = (int) $match['first'];
        $second = (int) $match['second'];

        $total += $first * $second;
    }

    return $total;
}


function part02(string $input): ?int
{
    $result = preg_match_all('/(do\(\)|don\'t\(\)|mul\((?P<first>\d{1,3}),(?P<second>\d{1,3})\))/im', $input, $matches, PREG_SET_ORDER);

    if ($result === false) {
        return null;
    }

    $total = 0;

    $do = true;

    foreach ($matches as $match) {
        if ($match[0] === DO_TOKEN) {
            $do = true;
            continue;
        } 

        if ($match[0] == DO_NOT_TOKEN) {
            $do = false;
            continue;
        }

        if (! $do) {
            continue;
        }

        $first = (int) $match['first'];
        $second = (int) $match['second'];

        $total += $first * $second;
    }

    return $total;
}

function main()
{
    $part01_example_input = file_get_contents('part_01_example.txt');
    $part02_example_input = file_get_contents('part_02_example.txt');

    $part01_example_output = part01($part01_example_input);
    $part02_example_output = part02($part02_example_input);

    echo "Part 01 Example - {$part01_example_output}\n";
    echo "Part 02 Example - {$part02_example_output}\n";
    
    assert($part01_example_output === 161, 'Part 01 - Example output does not equal 161');
    assert($part02_example_output === 48, 'Part 02 - Example output does not equal 48');

    $part01_input = file_get_contents('part_01_input.txt');
    
    $part01_output = part01($part01_input);

    echo "Part 01 - {$part01_output}\n";
    
    $part02_input = file_get_contents('part_02_input.txt');
    
    $part02_output = part02($part02_input);

    echo "Part 02 - {$part02_output}\n";
}

main();