<?php

/**
 * @author      Brian Moon <brianm@dealnews.com>
 * @copyright   1997-Present DealNews.com, Inc
 * @package     PageMill
 */

namespace PageMill\Pattern;

use \PageMill\Pattern\Exception\InvalidType;
use \PageMill\Pattern\Exception\InvalidPattern;

/**
 * Pattern matching for PageMill
 */
class Pattern {

    /**
     * Determines if the a route matches the request path
     *
     * @param  string $type      The matching plan to use for matching the
     *                           target. Each plan must have a type and a
     *                           pattern. Valid types are `exact`, `regex`,
     *                           and `starts_with`. For types exact and
     *                           starts_with, the pattern must be a string.
     *                           For type regex, the pattern should be a
     *                           valid regular expresssion.
     * @param  array  $patterns  The target we are checking for a match.
     * @param  string $target    The target we are checking for a match.
     * @param  array  $tokens    An array that will be filled with pattern
     *                           matching tokens when $type is regex
     *
     * @return boolean
     *
     * @throws \PageMill\Pattern\Exception\InvalidType
     * @throws \PageMill\Pattern\Exception\InvalidPattern
     */
    public function match(string $type, array $patterns, string $target, ?array &$tokens = []): bool {

        $match = false;

        $tokens = [];

        if (empty($type)) {
            throw new InvalidType("Type can not be empty");
        } elseif (empty($patterns)) {
            throw new InvalidPattern("Patterns can not be empty");
        } else {
            foreach ($patterns as $pattern) {

                if (empty($pattern)) {
                    throw new InvalidPattern("Pattern can not be empty");
                } elseif (!is_scalar($pattern)) {
                    throw new InvalidPattern("Patterns must be scalar values");
                }

                switch ($type) {
                    case "exact":
                        if ($pattern == $target) {
                            $match = true;
                        }
                        break;
                    case "regex":
                        $result = preg_match($pattern, $target, $matches);
                        if ($result === false) {
                            throw new InvalidPattern("Invalid regex pattern {$pattern}");
                        } elseif ($result === 1) {
                            $match = true;
                            if (!empty($matches[1])) {
                                unset($matches[0]);
                                $tokens = array_values($matches);
                            }
                        }
                        break;
                    case "starts_with":
                        if (strpos($target, $pattern) === 0) {
                            $match = true;
                        }
                        break;
                    default:
                        throw new InvalidType("Invalid type {$type}");
                }
                if ($match === true) {
                    break;
                }
            }
        }

        return $match;
    }
}
