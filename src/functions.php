<?php

namespace eLife\Patterns;

use ArrayObject;
use Traversable;

/**
 * Flattens, orders and deduplicates.
 *
 * @internal
 */
function sanitise_traversable(Traversable $traversable) : Traversable
{
    return new ArrayObject(sanitise_array(iterator_to_array(flatten($traversable))));
}

/**
 * Flattens, orders and deduplicates.
 *
 * @internal
 */
function sanitise_array(array $array) : array
{
    $array = array_keys(array_flip(iterator_to_array(flatten($array))));

    sort($array);

    return $array;
}

/**
 * @internal
 */
function flatten($item) : Traversable
{
    if (is_traversable($item)) {
        foreach ($item as $value) {
            foreach (flatten($value) as $flattenedValue) {
                yield $flattenedValue;
            }
        }
    } else {
        yield $item;
    }
}

/**
 * @internal
 */
function is_traversable($item) : bool
{
    return is_array($item) || $item instanceof Traversable;
}