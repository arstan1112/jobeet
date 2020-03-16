<?php

namespace App\Tests\Service;

use App\Service\CategoryService;
use phpDocumentor\Reflection\Types\This;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CategoryServiceTest extends TestCase
{

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $bag;

    public function testSomething()
    {
        $this->bag = $this->createMock(ParameterBagInterface::class);
//        $this->bag->expects($this->once())
        $this->bag
            ->method('get')
            ->willReturn('max_jobs_on_homepage');

//        $category = new CategoryService($this->bag);

        $this->assertTrue(true);
    }
}
