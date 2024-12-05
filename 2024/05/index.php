<?php


function part01(string $input): ?int
{
    $orderingRules = [];
    $printOrders = [];

    $lines = explode("\n", $input);

    foreach ($lines as $line) {
        if (str_contains($line, '|')) {
            [$first, $second] = explode('|', $line);
            $orderingRules[] = [(int) $first, (int) $second];
        }

        if (str_contains($line, ',')) {
            $printOrders[] = array_map(fn (string $number) => (int) $number, explode(',', $line));
        }
    }

    $validPrintOrders = [];

    foreach ($printOrders as $printOrder) {
        $valid = true;

        foreach ($orderingRules as $orderingRule) {
            if (! in_array($orderingRule[0], $printOrder) || ! in_array($orderingRule[1], $printOrder)) {
                continue;
            }

            $firstIndex = array_search($orderingRule[0], $printOrder);
            $secondIndex = array_search($orderingRule[1], $printOrder);

            if ($firstIndex > $secondIndex) {
                $valid = false;
                break;
            }
        }

        if ($valid) {
            $validPrintOrders[] = $printOrder;
        }
    }

    $total = 0;

    foreach ($validPrintOrders as $validPrintOrder) {
        $index = (int) round(count($validPrintOrder) / 2, mode: PHP_ROUND_HALF_DOWN);

        $middleNumber = $validPrintOrder[$index];

        $total += $middleNumber;
    }
    
    return $total;
}

function part02(string $input): ?int
{
    $orderingRules = [];
    $printOrders = [];

    $lines = explode("\n", $input);

    foreach ($lines as $line) {
        if (str_contains($line, '|')) {
            [$first, $second] = explode('|', $line);
            $orderingRules[] = [(int) $first, (int) $second];
        }

        if (str_contains($line, ',')) {
            $printOrders[] = array_map(fn (string $number) => (int) $number, explode(',', $line));
        }
    }

    $invalidPrintOrders = [];

    foreach ($printOrders as $printOrder) {
        $valid = true;

        foreach ($orderingRules as $orderingRule) {
            if (! in_array($orderingRule[0], $printOrder) || ! in_array($orderingRule[1], $printOrder)) {
                continue;
            }

            $firstIndex = array_search($orderingRule[0], $printOrder);
            $secondIndex = array_search($orderingRule[1], $printOrder);

            if ($firstIndex > $secondIndex) {
                $valid = false;
                break;
            }
        }

        if (! $valid) {
            $invalidPrintOrders[] = $printOrder;
        }
    }

    foreach ($invalidPrintOrders as &$invalidPrintOrder) {
        $changed = true;

        do {
            $changed = false;

            foreach ($orderingRules as $orderingRule) {
                if (! in_array($orderingRule[0], $invalidPrintOrder) || ! in_array($orderingRule[1], $invalidPrintOrder)) {
                    continue;
                }
    
                $firstIndex = array_search($orderingRule[0], $invalidPrintOrder);
                $secondIndex = array_search($orderingRule[1], $invalidPrintOrder);
    
                if ($firstIndex > $secondIndex) {
                    $firstValue = $invalidPrintOrder[$firstIndex];
                    $secondValue = $invalidPrintOrder[$secondIndex];
    
                    $invalidPrintOrder[$firstIndex] = $secondValue;
                    $invalidPrintOrder[$secondIndex] = $firstValue;

                    $changed = true;
                }
            }
        } while ($changed);
    }

    $total = 0;

    foreach ($invalidPrintOrders as $fixedInvalidPrintOrder) {
        $index = (int) round(count($fixedInvalidPrintOrder) / 2, mode: PHP_ROUND_HALF_DOWN);

        $middleNumber = $fixedInvalidPrintOrder[$index];

        $total += $middleNumber;
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

    assert($part01_example_output === 143, 'Part 01 - Example output does not equal 143');
    assert($part02_example_output === 123, 'Part 02 - Example output does not equal 123');

    $part01_input = file_get_contents('part_01_input.txt');

    $part01_output = part01($part01_input);

    echo "Part 01 - {$part01_output}\n";

    $part02_input = file_get_contents('part_02_input.txt');

    $part02_output = part02($part02_input);

    echo "Part 02 - {$part02_output}\n";
}

main();
