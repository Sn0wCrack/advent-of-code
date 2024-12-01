<?php

$part01_example = file('part_01_example.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

assert(part01($part01_example) === 11, 'Part 01 - Example output does not equal 11');

$part01_input = file('part_01_input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

echo "Part 01 - " . part01($part01_input) . "\n";

assert(part02($part01_example) === 31, 'Part 02 - Example output does not equal 31');

$part02_input = file('part_02_input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

echo "Part 02 - " . part02($part02_input) . "\n";

function part01(array $input): int {
    $total = 0;

    $leftList = [];
    $rightList = [];
    
    foreach ($input as $line) {
        [$left, $right] = explode('   ', $line);
    
        $leftList[] = (int) $left;
        $rightList[] = (int) $right;
    }
    
    sort($leftList);
    sort($rightList);
    
    assert(count($leftList) > 0 && count($rightList) > 0, 'Lists are empty');
    assert(count($leftList) === count($rightList), 'Lists are not of equal length');

    for ($i = 0; $i < count($leftList); ++$i) {
        $distance = $leftList[$i] > $rightList[$i]
            ? $leftList[$i] - $rightList[$i]
            : $rightList[$i] - $leftList[$i];

        $total += $distance;
    }

    return $total;
}

function part02(array $input): int {
    $total = 0;

    $leftList = [];
    $rightList = [];
    
    foreach ($input as $line) {
        [$left, $right] = explode('   ', $line);
    
        $leftList[] = (int) $left;
        $rightList[] = (int) $right;
    }
    
    assert(count($leftList) > 0 && count($rightList) > 0, 'Lists are empty');
    assert(count($leftList) === count($rightList), 'Lists are not of equal length');

    foreach ($leftList as $item) {
        $similarity = array_filter($rightList, fn (int $id) => $id === $item);

        $total += $item * count($similarity);
    }

    return $total;
}