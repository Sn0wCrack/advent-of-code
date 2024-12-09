<?php

const GUARD_CHARACTER = '^';
const BARREL_CHARACTER = '#';
const OPEN_SPACE_CHARACTER = '.';

enum Direction
{
    case Up;
    case Left;
    case Right;
    case Down;
}

class Guard
{
    public function __construct(
        public $x = 0,
        public $y = 0,
        public Direction $direction = Direction::Up
    ) {
    }

    /**
     * @return array{x: int, y: int}
     */
    public function getPosition(): array
    {
        return ['x' => $this->x, 'y' => $this->y];
    }

    /**
     * @return array{x: int, y: int, direction: Direction}
     */
    public function getPositionAndDirection(): array
    {
        return ['x' => $this->x, 'y' => $this->y, 'direction' => $this->direction];
    }
}

function part01(string $input): ?int
{
    $map = array_map('str_split', explode("\n", $input));

    $guard = new Guard();

    foreach ($map as $y => $column) {
        foreach ($column as $x => $item) {
            if ($item === GUARD_CHARACTER) {
                $guard->x = $x;
                $guard->y = $y;

                $map[$y][$x] = OPEN_SPACE_CHARACTER;

                break;
            }
        }
    }

    $positions = [
        $guard->getPosition(),
    ];

    while (true) {
        $newPosition = new_position($guard);

        // We have escaped
        if (! isset($map[$newPosition['y']][$newPosition['x']])) {
            break;
        }

        if ($map[$newPosition['y']][$newPosition['x']] === BARREL_CHARACTER) {
            $guard->direction = new_direction($guard);
            continue;
        }

        $guard->x = $newPosition['x'];
        $guard->y = $newPosition['y'];

        $positions[] = $guard->getPosition();
    }

    $positions = array_unique($positions, SORT_REGULAR);

    return count($positions);
}

function part02(string $input): ?int
{
    $map = array_map('str_split', explode("\n", $input));

    $guard = new Guard();

    foreach ($map as $y => $column) {
        foreach ($column as $x => $item) {
            if ($item === GUARD_CHARACTER) {
                $guard->x = $x;
                $guard->y = $y;
                break;
            }
        }
    }

    $invalid = 0;

    foreach ($map as $y => $column) {
        foreach ($column as $x => $item) {
            if ($guard->x == $x && $guard->y == $y) {
                continue;
            }

            if ($map[$y][$x] !== OPEN_SPACE_CHARACTER) {
                continue;
            }


            $clonedGuard = clone $guard;
            $clonedMap = $map;

            $clonedMap[$y][$x] = BARREL_CHARACTER;

            if (test_loop($clonedGuard, $clonedMap)) {
                $invalid++;
            }
        }
    }

    return $invalid;
}

/** 
 * @return array{x: int, y: int}
 */
function new_position(Guard $guard): array
{
    $current = $guard->getPosition();

    switch ($guard->direction)
    {
        case Direction::Up:
            $current['y'] -= 1;
            break;
        case Direction::Left:
            $current['x'] -= 1;
            break;
        case Direction::Right:
            $current['x'] += 1;
            break;
        case Direction::Down:
            $current['y'] += 1;
            break;
    }

    return $current;
}

function new_direction(Guard $guard): Direction
{
    return match($guard->direction)
    {
        Direction::Up => Direction::Right,
        Direction::Right => Direction::Down,
        Direction::Down => Direction::Left,
        Direction::Left => Direction::Up,
    };
}

function test_loop(Guard $guard, array $map): bool
{
    $height = count($map) - 1;

    $positions = [];

    while (true) {
        $newPosition = new_position($guard);

        // We have escaped
        if (! isset($map[$newPosition['y']][$newPosition['x']])) {
            break;
        }

        if ($map[$newPosition['y']][$newPosition['x']] === BARREL_CHARACTER) {
            $guard->direction = new_direction($guard);
            continue;
        }

        $guard->x = $newPosition['x'];
        $guard->y = $newPosition['y'];

        // Basically if we've managed to go more than the entire length of the map, we've probably hit a loop
        if (count($positions) > 2 * pow($height, 2)) {
            return true;
        }

        $positions[] = $guard->getPositionAndDirection();
    }

    return false;
}

function main(): void
{
    $part01_example_input = file_get_contents('part_01_example.txt');

    $part01_example_output = part01($part01_example_input);
    $part02_example_output = part02($part01_example_input);

    echo "Part 01 Example - {$part01_example_output}\n";
    echo "Part 02 Example - {$part02_example_output}\n";

    assert($part01_example_output === 41, 'Part 01 - Example output does not equal 41');
    assert($part02_example_output === 6, 'Part 02 - Example output does not equal 6');

    $part01_input = file_get_contents('part_01_input.txt');

    $part01_output = part01($part01_input);

    echo "Part 01 - {$part01_output}\n";

    $part02_input = file_get_contents('part_02_input.txt');

    $part02_output = part02($part02_input);

    echo "Part 02 - {$part02_output}\n";
}

main();
