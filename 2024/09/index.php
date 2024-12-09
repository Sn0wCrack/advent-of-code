<?php

enum BlockType
{
    case File;
    case Empty;
}

function part01(string $input): ?int
{
    $blocks = str_split($input);

    $type = BlockType::File;

    $parsed = [];

    $id = 0;

    foreach ($blocks as $block) {
        $length = (int) $block;

        for ($i = 0; $i < $length; ++$i) {
            $entry = [
                'id' => $type == BlockType::File ? $id : null,
                'type' => $type,
            ];
    
            $parsed[] = $entry;
        }

        if ($type == BlockType::File) {
            $id++;
        }

        $type = match ($type) {
            BlockType::File => BlockType::Empty,
            BlockType::Empty => BlockType::File,
        };
    }

    $totalBlocks = count($parsed) - 1;

    for ($i = $totalBlocks; $i > 0; --$i) {
        if ($parsed[$i]['type'] == BlockType::Empty) {
            continue;
        }

        $emptyBlockIndex = array_find_key($parsed, fn (array $entry) => $entry['type'] == BlockType::Empty);

        if (is_null($emptyBlockIndex)) {
            continue;
        }

        // If the first available empty block is further back than the current loop, then skip it
        if ($emptyBlockIndex > $i) {
            continue;
        }

        $parsed[$emptyBlockIndex] = $parsed[$i];

        $parsed[$i] = [
            'id' => null,
            'type' => BlockType::Empty,
        ];
    }
    
    $checksum = 0;

    foreach ($parsed as $index => $entry) {
        if ($entry['type'] == BlockType::Empty) {
            continue;
        }

        $checksum += $index * $entry['id'];
    }

    return $checksum;
}

function part02(string $input): ?int
{
    $blocks = str_split($input);

    $type = BlockType::File;

    $parsed = [];

    $id = 0;

    foreach ($blocks as $block) {
        $length = (int) $block;

        $entry = [
            'id' => $type == BlockType::File ? $id : null,
            'type' => $type,
            'length' => $length,
        ];

        $parsed[] = $entry;

        if ($type == BlockType::File) {
            $id++;
        }

        $type = match ($type) {
            BlockType::File => BlockType::Empty,
            BlockType::Empty => BlockType::File,
        };
    }

    $totalBlocks = count($parsed) - 1;

    for ($i = $totalBlocks; $i > 0; --$i) {
        echo "{$i}\n";

        $current = $parsed[$i];

        if ($current['type'] == BlockType::Empty) {
            continue;
        }

        $emptyBlockIndex = array_find_key($parsed, fn (array $entry) => $entry['type'] == BlockType::Empty && $entry['length'] >= $current['length']);

        if (is_null($emptyBlockIndex)) {
            continue;
        }

        // If the first available empty block is further back than the current loop, then skip it
        if ($emptyBlockIndex > $i) {
            continue;
        }

        $found = $parsed[$emptyBlockIndex];

        // If they're the same length we can do a simple switch like before
        // Otherwise we need to splice the array a little bit and modify the empty space block length
        if ($current['length'] === $found['length']) {
            $parsed[$i] = [
                'id' => null,
                'type' => BlockType::Empty,
                'length' => $current['length'],
            ];

            $parsed[$emptyBlockIndex] = $current;
        } else {
            $newLength = $found['length'] - $current['length'];

            $found['length'] = $newLength;

            $parsed[$i] = [
                'id' => null,
                'type' => BlockType::Empty,
                'length' => $current['length'],
            ];

            array_splice($parsed, $emptyBlockIndex, 1, [$current, $found]);
        }
    }
    
    $checksum = 0;

    $index = 0;

    foreach ($parsed as $entry) {
        if ($entry['type'] == BlockType::Empty) {
            $index += $entry['length'];
            continue;
        }

        for ($i = 0; $i < $entry['length']; ++$i) {
            $checksum += $index * $entry['id'];
            $index++;
        }
    }

   return $checksum;
}

function visualise_blocks(array $blocks): void
{
    $output = '';

    foreach ($blocks as $entry) {
        $stringified = $entry['type'] == BlockType::File
            ? $entry['id']
            : '.';

        if (array_key_exists('length', $entry)) {
            $stringified = str_repeat($stringified, $entry['length']);
        }

        $output .= $stringified;
    }

    echo $output . "\n";
}

function main(): void
{
    $part01_example_input = file_get_contents('./part_01_example.txt');

    $part01_example_output = part01($part01_example_input);
    $part02_example_output = part02($part01_example_input);

    echo "Part 01 Example - {$part01_example_output}\n";
    echo "Part 02 Example - {$part02_example_output}\n";

    assert($part01_example_output === 1928, 'Part 01 - Example output does not equal 1928');
    assert($part02_example_output === 2858, 'Part 02 - Example output does not equal 2858');

    // $part01_input = file_get_contents('./part_01_input.txt');

    // $part01_output = part01($part01_input);

    // echo "Part 01 - {$part01_output}\n";

    $part02_input = file_get_contents('./part_02_input.txt');

    $part02_output = part02($part02_input);

    echo "Part 02 - {$part02_output}\n";
}

main();