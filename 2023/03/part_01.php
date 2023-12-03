<?php

$lines = file('part_01_input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$schematic = [];

foreach ($lines as $line) {
    $schematic[] = str_split($line);
}

$height = count($schematic) - 1;
$width = count($schematic[0]) - 1;

$notSpecialCharacters = [
    '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.'
];

$total = 0;

$foundNumberPositions = [];

foreach ($schematic as $currentY => $characters) {
    foreach ($characters as $currentX => $character) {
        // Only proceed if the character we're looking at is a symbol
        if (in_array($character, $notSpecialCharacters)) {
            continue;
        }

        // Scan all neighbouring cells
        for ($scanY = $currentY - 1; $scanY <= $currentY + 1; $scanY++) {
            for ($scanX = $currentX - 1; $scanX <= $currentX + 1; $scanX++) {
                // Ignore the current cell
                if ($currentY === $scanY && $currentX === $scanX) {
                    continue;
                }

                // Check if we're inbounds of the schematic
                if (!isset($schematic[$scanY][$scanX])) {
                    continue;
                }

                // If we're not a number then go to the next cell
                if (!is_numeric($schematic[$scanY][$scanX])) {
                    continue;
                }

                // Go all the way to the left of the number until no number is found
                // Then start building the number by going all the way to the right until no number is found
                // If the number is already one we've found in this scanning attempt, throw it out since it's a duplicate
                $firstNumberX = $scanX;

                for ($backwardsScanningX = $scanX; $backwardsScanningX >= 0; $backwardsScanningX--) {
                    // Bail out when we find the first non-numeric character
                    if (!is_numeric($schematic[$scanY][$backwardsScanningX])) {
                        break;
                    }

                    $firstNumberX = $backwardsScanningX;
                }

                $foundNumber = '';

                for ($forwardsScanningX = $firstNumberX; $forwardsScanningX <= $width; $forwardsScanningX++) {
                    // Bail out when  we find the first non-numeric character
                    if (!is_numeric($schematic[$scanY][$forwardsScanningX])) {
                        break;
                    }

                    $foundNumber .= $schematic[$scanY][$forwardsScanningX];
                }

                $numberPosition = ['x' => $firstNumberX, 'y' => $scanY];

                $foundNumber = (int) $foundNumber;

                if (!in_array($numberPosition, $foundNumberPositions)) {
                    $foundNumberPositions[] = $numberPosition;

                    $total += $foundNumber;
                }
            }
        }
    }
}

echo "{$total}\n";
