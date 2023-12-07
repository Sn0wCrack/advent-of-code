<?php

const CARDS = [
    'A' => 14,
    'K' => 13,
    'Q' => 12,
    'J' => 11,
    'T' => 10,
    '9' => 9,
    '8' => 8,
    '7' => 7,
    '6' => 6,
    '5' => 5,
    '4' => 4,
    '3' => 3,
    '2' => 2,
];

const HAND_TYPE_FIVE_OF_A_KIND = 'five-of-a-kind';
const HAND_TYPE_FOUR_OF_A_KIND = 'four-of-a-kind';
const HAND_TYPE_FULL_HOUSE = 'full-house';
const HAND_TYPE_THREE_OF_A_KIND = 'three-of-a-kind';
const HAND_TYPE_TWO_PAIR = 'two-pair';
const HAND_TYPE_ONE_PAIR = 'one-pair';
const HAND_TYPE_HIGH_PAIR = 'high-pair';

const HAND_TYPES = [
    HAND_TYPE_FIVE_OF_A_KIND => 7,
    HAND_TYPE_FOUR_OF_A_KIND => 6,
    HAND_TYPE_FULL_HOUSE => 5,
    HAND_TYPE_THREE_OF_A_KIND => 4,
    HAND_TYPE_TWO_PAIR => 3,
    HAND_TYPE_ONE_PAIR => 2,
    HAND_TYPE_HIGH_PAIR => 1,
];

const HAND_SIZE = 5;

$lines = file('part_01_input.txt', FILE_IGNORE_NEW_LINES);

$hands = [];

/**
 *
 * @param array<key-of<CARDS>, int> $types
 * @return key-of<HAND_TYPES>|null
 */
function getHandType(array $counts): ?string
{
    // We can determine some hand types based on the number of unique cards in the hand
    // 1 => 5 of a kind
    // 5 => High pair

    if (count($counts) === 1) {
        return HAND_TYPE_FIVE_OF_A_KIND;
    }

    if (count($counts) === HAND_SIZE) {
        return HAND_TYPE_HIGH_PAIR;
    }

    $fourOfAKind = array_filter($counts, fn (int $count) => $count === 4);

    if (!empty($fourOfAKind)) {
        return HAND_TYPE_FOUR_OF_A_KIND;
    }

    // Everything going forward is where there are 3 or 4 unique card labels
    $threeOfAKind = array_filter($counts, fn (int $count) => $count === 3);

    if (!empty($threeOfAKind)) {
        if (count($counts) === 2) {
            return HAND_TYPE_FULL_HOUSE;
        }

        if (count($counts) >= 3) {
            return HAND_TYPE_THREE_OF_A_KIND;
        }
    }

    $pairs = array_filter($counts, fn (int $count) => $count === 2);

    if (count($pairs) === 2) {
        return HAND_TYPE_TWO_PAIR;
    }

    if (count($pairs) === 1) {
        return HAND_TYPE_ONE_PAIR;
    }

    return null;
}

foreach ($lines as $line) {
    [$cards, $bid] = explode(' ', $line);

    $counts = [];

    foreach (array_keys(CARDS) as $card) {
        $count = substr_count($cards, $card);

        if ($count > 0) {
            $counts[$card] = $count;
        }
    }

    $type = getHandType($counts);

    $hands[] = [
        'cards' => $cards,
        'type' => $type,
        'bid' => (int) $bid,
    ];
}

usort($hands, function (array $a, array $b): int {
    $aRank = HAND_TYPES[$a['type']];
    $bRank = HAND_TYPES[$b['type']];

    if ($aRank === $bRank) {
        for ($i = 0; $i < HAND_SIZE; ++$i) {
            $aCardRank = CARDS[$a['cards'][$i]];
            $bCardRank = CARDS[$b['cards'][$i]];

            if ($aCardRank === $bCardRank) {
                continue;
            }

            if ($aCardRank > $bCardRank) {
                return 1;
            }

            if ($aCardRank < $bCardRank) {
                return -1;
            }
        }
    }

    if ($aRank > $bRank) {
        return 1;
    }

    if ($aRank < $bRank) {
        return -1;
    }
});

$total = 0;

foreach ($hands as $rank => $hand) {
    $total += $hand['bid'] * ($rank + 1);
}

echo "{$total}\n";
