<?php

/**
 * Tests the Pattern class
 *
 * @author      Brian Moon <brianm@dealnews.com>
 * @copyright   1997-Present DealNews.com, Inc
 * @package     PageMill
 *
 */

namespace PageMill\Pattern\Tests;

use \PageMill\Pattern\Pattern;

class PatternTest extends \PHPUnit\Framework\TestCase {

    public function testMatchExact() {
        $r = new Pattern();
        $result = $r->match(
            "exact",
            ["foo"],
            "foo"
        );
        $this->assertEquals(
            true,
            $result
        );
        $result = $r->match(
            "exact",
            ["foo"],
            "foz"
        );
        $this->assertEquals(
            false,
            $result
        );
    }

    public function testMatchStartsWith() {
        $r = new Pattern();
        $result = $r->match(
            "starts_with",
            ["/foo"],
            "/foo"
        );
        $this->assertEquals(
            true,
            $result
        );

        $result = $r->match(
            "starts_with",
            ["/foo"],
            "/foo/bar"
        );
        $this->assertEquals(
            true,
            $result
        );

        $result = $r->match(
            "starts_with",
            ["/foo"],
            "/foz/bar"
        );
        $this->assertEquals(
            false,
            $result
        );
    }

    public function testMatchRegex() {
        $r = new Pattern();
        $result = $r->match(
            "regex",
            ["!^/foo!"],
            "/foo",
            $tokens
        );
        $this->assertEquals(
            true,
            $result
        );
        $this->assertEquals(
            [],
            $tokens
        );

        $result = $r->match(
            "regex",
            ["!^/foo/(\d+)/!"],
            "/foo/1/",
            $tokens
        );
        $this->assertEquals(
            true,
            $result
        );
        $this->assertEquals(
            [1],
            $tokens
        );

        $result = $r->match(
            "regex",
            ["!^/foo/(\d+)/!"],
            "/foo/0/",
            $tokens
        );
        $this->assertEquals(
            true,
            $result
        );
        $this->assertEquals(
            [0],
            $tokens
        );
    }
}
