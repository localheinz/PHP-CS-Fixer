<?php

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

use PhpCsFixer\Test\AbstractFixerTestCase;

/**
 * @author Andreas Möller <am@localheinz.com>
 *
 * @internal
 */
final class NativeFunctionInvocationFixerTest extends AbstractFixerTestCase
{
    public function testConfigureRejectsInvalidConfiguration()
    {
        $this->setExpectedException(
            'PhpCsFixer\ConfigurationException\InvalidConfigurationException',
            'Configuration must define "exclude" as an array.'
        );

        $this->fixer->configure(array(
            'foo' => 'bar',
        ));
    }

    /**
     * @dataProvider providerInvalidConfigurationElement
     *
     * @param mixed $element
     */
    public function testConfigureRejectsInvalidConfigurationElement($element)
    {
        $this->setExpectedException('PhpCsFixer\ConfigurationException\InvalidConfigurationException', sprintf(
            'Each element must be a non-empty string, got "%s" instead.',
            \is_object($element) ? \get_class($element) : \gettype($element)
        ));

        $this->fixer->configure(array(
            'exclude' => array(
                $element,
            ),
        ));
    }

    /**
     * @return array
     */
    public function providerInvalidConfigurationElement()
    {
        return array(
            'null' => array(null),
            'false' => array(false),
            'true' => array(false),
            'int' => array(1),
            'array' => array(array()),
            'float' => array(0.1),
            'object' => array(new \stdClass()),
        );
    }

    public function testIsRisky()
    {
        $fixer = $this->createFixer();

        $this->assertTrue($fixer->isRisky());
    }

    /**
     * @dataProvider provideCasesWithDefaultConfiguration
     *
     * @param string      $expected
     * @param null|string $input
     */
    public function testFixWithDefaultConfiguration($expected, $input = null)
    {
        $this->doTest($expected, $input);
    }

    /**
     * @return array
     */
    public function provideCasesWithDefaultConfiguration()
    {
        return array(
            array(
'<?php

json_encode($foo);
',
            ),
            array(
'<?php

class WithoutNamespace
{
    public function bar($foo)
    {
        return json_encode($foo);
    }
}
',
            ),
            array(
'<?php

namespace OneNamespaceWithBraces {}

json_encode($foo);

namespace AnotherNamespaceWithBraces {}
',
            ),
            array(
'<?php

namespace WithoutClassPrefixed;

if (isset($foo)) {
    \json_encode($foo);
}
',
            ),
            array(
'<?php

namespace WithoutClassNotPrefixed;

if (isset($foo)) {
    \json_encode($foo);
}
',
'<?php

namespace WithoutClassNotPrefixed;

if (isset($foo)) {
    json_encode($foo);
}
',
            ),
            array(
'<?php

namespace Foo;

class WithClassPrefixed
{
    public function baz($foo)
    {
        if (isset($foo)) {
            \json_encode($foo);
        }
    }
}',
            ),
            array(
'<?php

namespace WithClassNotPrefixed;

class Bar
{
    public function baz($foo)
    {
        if (isset($foo)) {
            \json_encode($foo);
        }
    }
}',
'<?php

namespace WithClassNotPrefixed;

class Bar
{
    public function baz($foo)
    {
        if (isset($foo)) {
            json_encode($foo);
        }
    }
}',
            ),
            array(
'<?php

namespace OneNamespaceWithBraces {}

namespace WithoutClassInNamespaceWithBracesPrefixed
{
    if (isset($foo)) {
        \json_encode($foo);
    }
}',
            ),
            array(
'<?php

namespace OneNamespaceWithBraces {}

namespace WithoutClassInNamespaceWithBracesNotPrefixed
{
    if (isset($foo)) {
        \json_encode($foo);
    }
}
',
'<?php

namespace OneNamespaceWithBraces {}

namespace WithoutClassInNamespaceWithBracesNotPrefixed
{
    if (isset($foo)) {
        json_encode($foo);
    }
}
',
            ),
        );
    }

    /**
     * @dataProvider provideCasesWithConfiguredExclude
     *
     * @param string      $expected
     * @param null|string $input
     */
    public function testFixWithConfiguredExclude($expected, $input = null)
    {
        $this->fixer->configure(array(
            'exclude' => array(
                'json_encode',
            ),
        ));

        $this->doTest($expected, $input);
    }

    /**
     * @return array
     */
    public function provideCasesWithConfiguredExclude()
    {
        return array(
            array(
'<?php

json_encode($foo);
',
            ),
            array(
'<?php

class WithoutNamespace
{
    public function bar($foo)
    {
        return json_encode($foo);
    }
}
',
            ),
            array(
'<?php

namespace OneNamespaceWithBraces {}

json_encode($foo);

namespace AnotherNamespaceWithBraces {}
',
            ),
            array(
'<?php

namespace WithoutClassPrefixed;

if (isset($foo)) {
    \json_encode($foo);
}
',
            ),
            array(
'<?php

namespace WithoutClassNotPrefixed;

if (isset($foo)) {
    json_encode($foo);
}
',
            ),
            array(
'<?php

namespace Foo;

class WithClassPrefixed
{
    public function baz($foo)
    {
        if (isset($foo)) {
            \json_encode($foo);
        }
    }
}',
            ),
            array(
'<?php

namespace WithClassNotPrefixed;

class Bar
{
    public function baz($foo)
    {
        if (isset($foo)) {
            json_encode($foo);
        }
    }
}',
            ),
            array(
'<?php

namespace OneNamespaceWithBraces {}

namespace WithoutClassInNamespaceWithBracesPrefixed
{
    if (isset($foo)) {
        \json_encode($foo);
    }
}',
            ),
            array(
'<?php

namespace OneNamespaceWithBraces {}

namespace WithoutClassInNamespaceWithBracesNotPrefixed
{
    if (isset($foo)) {
        json_encode($foo);
    }
}
',
            ),
        );
    }
}
