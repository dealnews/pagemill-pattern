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
     *                           and `starts_with`. For tyeps exact and
     *                           starts_with, the pattern must be a string.
     *                           For type regex, the pattern should be a
     *                           valid regular expresssion.
     * @param  array  $patterns  The target we are checking for a match.
     * @param  string $target    The target we are checking for a match.
     *
     * @return mixed  False when there is no match. If type is `exact`, and
     *                there is a match, true will be returned. For type
     *                `regex`, the return value will be an array containing
     *                any captured tokens from the regular expression or true
     *                if the regular expression did not contain any groupings.
     *                When type is `starts_with, the return value will either
     *                be true or a string containing any additional value
     *                after the $pattern string.
     * @throws \PageMill\Pattern\Exception\InvalidType
     * @throws \PageMill\Pattern\Exception\InvalidPattern
     */
    public function match($type, $patterns, $target) {

        $tokens = false;

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
                            $tokens = true;
                        }
                        break;
                    case "regex":
                        $result = preg_match($pattern, $target, $matches);
                        if ($result === false) {
                            throw new InvalidPattern("Invalid regex pattern {$pattern}");
                        } elseif ($result) {
                            if (!empty($matches[1])) {
                                unset($matches[0]);
                                $tokens = array_values($matches);
                            } else{
                                $tokens = true;
                            }
                        }
                        break;
                    case "starts_with":
                        if (strpos($target, $pattern) === 0) {
                            if ($pattern !== $target) {
                                $tokens = substr($target, strlen($pattern));
                            } else {
                                $tokens = true;
                            }
                        }
                        break;
                    default:
                        throw new InvalidType("Invalid type {$type}");
                }
                if ($tokens !== false) {
                    break;
                }
            }
        }

        return $tokens;
    }
}
