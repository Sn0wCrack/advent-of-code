<?php

const LETTERS = ['M', 'A', 'S'];

// Search in all directions up to 3 units from the current character
const PART_01_SEARCH_OFFSETS = [
    [[0, 1], [0, 2], [0, 3]], // right
    [[1, 1], [2, 2], [3, 3]], // diagonal-down-right
    [[1, 0], [2, 0], [3, 0]], // down
    [[1, -1], [2, -2], [3, -3]], // diagonal-down-left
    [[0, -1], [0, -2], [0, -3]], // left
    [[-1, -1], [-2, -2], [-3, -3]], // diagonal-up-left
    [[-1, 0], [-2, 0], [-3, 0]], // up
    [[-1, 1], [-2, 2], [-3, 3]], // diagional-up-right
];

// Only search the diagonals for characters since that's all we care about in Part 02
const PART_02_SEARCH_OFFSETS = [
    [1, 1], // diagonal-down-right
    [1, -1], // diagonal-down-left
    [-1, -1], // diagional-up-left
    [-1, 1], // diagional-up-right
];

// Basically if all sorrounding characters aren't in one of the below combos, it's not an X-MAS
// First character is down-right, second is down-left, etc since the order we search and build our string is known
const PART_02_VALID_COMBINATIONS = [
    'MSSM', 
    'MMSS',
    'SSMM',
    'SMMS',
];

function part01(string $input): ?int
{
    $wordSearch = array_map('str_split', explode("\n", $input));

    $total = 0;

    foreach ($wordSearch as $currentY => $characters) {
        foreach ($characters as $currentX => $character) {
            // Only start searching from the start of the word.
            if ($character !== 'X') {
                continue;
            }

            foreach (PART_01_SEARCH_OFFSETS as $offsets) {
                foreach ($offsets as $index => $offset) {
                    $scanY = $currentY + $offset[0];
                    $scanX = $currentX + $offset[1];
    
                    if (! isset($wordSearch[$scanY][$scanX])) {
                        continue 2;
                    }
    
                    if ($wordSearch[$scanY][$scanX] !== LETTERS[$index]) {
                        continue 2;
                    }
                }

                $total++;
            }
        }
    }

    return $total;
}

function part02(string $input): ?int
{
    $wordSearch = array_map('str_split', explode("\n", $input));

    $total = 0;

    foreach ($wordSearch as $currentY => $characters) {
        foreach ($characters as $currentX => $character) {
            // Since "MAS" always has an "A" at the cetner, it makes more sense to start the search there
            if ($character !== 'A') {
                continue;
            }

            $characters = '';

            foreach (PART_02_SEARCH_OFFSETS as $offset) {
                $scanY = $currentY + $offset[0];
                $scanX = $currentX + $offset[1];

                if (! isset($wordSearch[$scanY][$scanX])) {
                    continue 2;
                }

                $characters .= $wordSearch[$scanY][$scanX];
            }

            if (in_array($characters, PART_02_VALID_COMBINATIONS)) {
                $total++;
            }
        }
    }

    return $total;
}

function main(): void
{
    $part01_example_input = file_get_contents('part_01_example.txt');

    $part01_example_output = part01($part01_example_input);
    $part02_example_output = part02($part01_example_input);

    echo "Part 01 Example - {$part01_example_output}\n";
    echo "Part 02 Example - {$part02_example_output}\n";

    assert($part01_example_output === 18, 'Part 01 - Example output does not equal 18');
    assert($part02_example_output === 9, 'Part 02 - Example output does not equal 9');

    $part01_input = file_get_contents('part_01_input.txt');

    $part01_output = part01($part01_input);

    echo "Part 01 - {$part01_output}\n";

    $part02_input = file_get_contents('part_02_input.txt');

    $part02_output = part02($part02_input);

    echo "Part 02 - {$part02_output}\n";
}

main();
