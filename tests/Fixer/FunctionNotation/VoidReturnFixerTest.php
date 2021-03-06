<?php

declare(strict_types=1);

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpCsFixer\Tests\Fixer\FunctionNotation;

use PhpCsFixer\Tests\Test\AbstractFixerTestCase;

/**
 * @author Mark Nielsen
 *
 * @internal
 *
 * @requires PHP 7.1
 * @covers \PhpCsFixer\Fixer\FunctionNotation\VoidReturnFixer
 */
final class VoidReturnFixerTest extends AbstractFixerTestCase
{
    /**
     * @dataProvider provideFixCases
     */
    public function testFix(string $expected, ?string $input = null): void
    {
        $this->doTest($expected, $input);
    }

    public function provideFixCases()
    {
        return [
            ['<?php class Test { public function __construct() {} }'],
            ['<?php class Test { public function __destruct() {} }'],
            ['<?php class Test { public function __clone() {} }'],
            ['<?php function foo($param) { return $param; }'],
            ['<?php function foo($param) { return null; }'],
            ['<?php function foo($param) { yield; }'],
            ['<?php function foo($param) { yield $param; }'],
            ['<?php function foo($param) { yield from test(); }'],
            ['<?php function foo($param): Void {}'],
            ['<?php interface Test { public function foo($param); }'],
            ['<?php function foo($param) { return function($a) use ($param): string {}; }'],
            ['<?php abstract class Test { abstract public function foo($param); }'],
            ['<?php
                /**
                 * @return array
                 */
                function foo($param) {}
            '],
            ['<?php
                interface Test {
                    /**
                     * @return array
                     */
                    public function foo($param);
                }
            '],
            [
                '<?php function foo($param): void { return; }',
                '<?php function foo($param) { return; }',
            ],
            [
                '<?php function foo($param): void {}',
                '<?php function foo($param) {}',
            ],
            [
                '<?php class Test { public function foo($param): void { return; } }',
                '<?php class Test { public function foo($param) { return; } }',
            ],
            [
                '<?php class Test { public function foo($param): void {} }',
                '<?php class Test { public function foo($param) {} }',
            ],
            [
                '<?php trait Test { public function foo($param): void { return; } }',
                '<?php trait Test { public function foo($param) { return; } }',
            ],
            [
                '<?php trait Test { public function foo($param): void {} }',
                '<?php trait Test { public function foo($param) {} }',
            ],
            [
                '<?php $arr = []; usort($arr, function ($a, $b): void {});',
                '<?php $arr = []; usort($arr, function ($a, $b) {});',
            ],
            [
                '<?php $arr = []; $param = 1; usort($arr, function ($a, $b) use ($param): void {});',
                '<?php $arr = []; $param = 1; usort($arr, function ($a, $b) use ($param) {});',
            ],
            [
                '<?php function foo($param) { return function($a) use ($param): void {}; }',
                '<?php function foo($param) { return function($a) use ($param) {}; }',
            ],
            [
                '<?php function foo($param): void { $arr = []; usort($arr, function ($a, $b) use ($param): void {}); }',
                '<?php function foo($param) { $arr = []; usort($arr, function ($a, $b) use ($param) {}); }',
            ],
            [
                '<?php function foo() { $arr = []; return usort($arr, new class { public function __invoke($a, $b): void {} }); }',
                '<?php function foo() { $arr = []; return usort($arr, new class { public function __invoke($a, $b) {} }); }',
            ],
            [
                '<?php function foo(): void { $arr = []; usort($arr, new class { public function __invoke($a, $b): void {} }); }',
                '<?php function foo() { $arr = []; usort($arr, new class { public function __invoke($a, $b) {} }); }',
            ],
            [
                '<?php
                function foo(): void {
                    $a = function (): void {};
                }',
                '<?php
                function foo() {
                    $a = function () {};
                }',
            ],
            [
                '<?php
                function foo(): void {
                    (function (): void {
                        return;
                    })();
                }',
                '<?php
                function foo() {
                    (function () {
                        return;
                    })();
                }',
            ],
            [
                '<?php
                function foo(): void {
                    (function () {
                        return 1;
                    })();
                }',
                '<?php
                function foo() {
                    (function () {
                        return 1;
                    })();
                }',
            ],
            [
                '<?php
                function foo(): void {
                    $b = new class {
                        public function b1(): void {}
                        public function b2() { return 2; }
                    };
                }',
                '<?php
                function foo() {
                    $b = new class {
                        public function b1() {}
                        public function b2() { return 2; }
                    };
                }',
            ],
            [
                '<?php
                /**
                 * @return void
                 */
                function foo($param): void {}',

                '<?php
                /**
                 * @return void
                 */
                function foo($param) {}',
            ],
            [
                '<?php
                interface Test {
                    /**
                     * @return void
                     */
                    public function foo($param): void;
                }',

                '<?php
                interface Test {
                    /**
                     * @return void
                     */
                    public function foo($param);
                }',
            ],
            [
                '<?php
                abstract class Test {
                    /**
                     * @return void
                     */
                    abstract protected function foo($param): void;
                }',

                '<?php
                abstract class Test {
                    /**
                     * @return void
                     */
                    abstract protected function foo($param);
                }',
            ],
        ];
    }

    /**
     * @dataProvider provideFixPhp74Cases
     * @requires PHP 7.4
     */
    public function testFixPhp74(string $expected, ?string $input = null): void
    {
        $this->doTest($expected, $input);
    }

    public function provideFixPhp74Cases()
    {
        return [
            [
                '<?php fn($a) => null;',
            ],
            [
                '<?php fn($a) => 1;',
            ],
            [
                '<?php fn($a) => var_dump($a);',
            ],
        ];
    }
}
