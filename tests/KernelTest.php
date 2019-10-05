<?php

declare(strict_types=1);

namespace App;

use PHPUnit\Framework\TestCase;

class KernelTest extends TestCase
{
    public function testIsRegisteringBundles(): void
    {
        $kernel = new Kernel('prod', false);
        $bundle = $kernel->registerBundles();
        $this->assertNotNull($bundle);
    }
}
