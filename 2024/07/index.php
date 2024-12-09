<?php

const MULTIPLICATION_OPERATOR = '*';
const PLUS_OPERATOR = '+';
const CONCAT_OPERATOR = '|';

const PART01_OPERATORS = [MULTIPLICATION_OPERATOR, PLUS_OPERATOR];
const PART02_OPERATORS = [MULTIPLICATION_OPERATOR, PLUS_OPERATOR, CONCAT_OPERATOR];

function part01(array $input): ?int
{
    $total = 0;

    foreach ($input as $line) {
        [$target, $values] = explode(': ', $line);

        $target = (int) $target;

        $values = array_map(fn (string $value) => (int) $value, explode(' ', $values));

        $totalOperatorPositions = count($values) - 1;

        $possibileSolutions = generate_combinations(PART01_OPERATORS, $totalOperatorPositions);

        foreach ($possibileSolutions as $solution) {
            $operators = str_split($solution);

            $result = $values[0];

            for ($i = 1; $i < count($values); ++$i) {
                $operator = $operators[$i - 1];
                
                switch ($operator) {
                    case MULTIPLICATION_OPERATOR:
                        $result *= $values[$i];
                        break;
                    case PLUS_OPERATOR:
                        $result += $values[$i];
                        break;
                }
            }

            if ($result === $target) {
                $total += $target;
                break;
            }
        }
    }

    return $total;
}

function part02(array $input): ?int
{
    $total = 0;

    foreach ($input as $line) {
        [$target, $values] = explode(': ', $line);

        $target = (int) $target;

        $values = array_map(fn (string $value) => (int) $value, explode(' ', $values));

        $totalOperatorPositions = count($values) - 1;

        $possibileSolutions = generate_combinations(PART02_OPERATORS, $totalOperatorPositions);
        
        echo "solutions: " . count($possibileSolutions) . "\n";

        foreach ($possibileSolutions as $solution) {
            $operators = str_split($solution);

            $result = $values[0];

            for ($i = 1; $i < count($values); ++$i) {
                $operator = $operators[$i - 1];
                
                switch ($operator) {
                    case MULTIPLICATION_OPERATOR:
                        $result *= $values[$i];
                        break;
                    case PLUS_OPERATOR:
                        $result += $values[$i];
                        break;
                    case CONCAT_OPERATOR:
                        $result .= $values[$i];
                }
            }

            $result = (int) $result;

            if ($result === $target) {
                $total += $target;
                break;
            }
        }
    }

    return $total;
}

function generate_combinations(array $characters, int $length, string $prefix = '', array $combinations = []): array
{
    if (strlen($prefix) == $length) {
        $combinations[] = $prefix;
        return $combinations;
    }

    foreach ($characters as $character) {
        $combinations = generate_combinations($characters, $length, $prefix . $character, $combinations);
    }


    return $combinations;
}

function main(): void
{
    $part01_example_input = file('part_01_example.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $part01_example_output = part01($part01_example_input);
    $part02_example_output = part02($part01_example_input);

    echo "Part 01 Example - {$part01_example_output}\n";
    echo "Part 02 Example - {$part02_example_output}\n";

    assert($part01_example_output === 3749, 'Part 01 - Example output does not equal 3749');
    assert($part02_example_output === 11387, 'Part 02 - Example output does not equal 11387');

    $part01_input = file('part_01_input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $part01_output = part01($part01_input);

    echo "Part 01 - {$part01_output}\n";

    $part02_input = file('part_02_input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $part02_output = part02($part02_input);

    echo "Part 02 - {$part02_output}\n";
}

main();