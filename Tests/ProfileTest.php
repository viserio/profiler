<?php
declare(strict_types=1);
namespace Viserio\Component\Profiler\Tests;

use PHPUnit\Framework\TestCase;
use Viserio\Component\Profiler\DataCollector\PhpInfoDataCollector;
use Viserio\Component\Profiler\Profile;

/**
 * @internal
 */
final class ProfileTest extends TestCase
{
    public function testSetAndGetToken(): void
    {
        $profile = new Profile('d78a9fa');

        $this->assertSame('d78a9fa', $profile->getToken());

        $profile->setToken('4dasda5sd');

        $this->assertSame('4dasda5sd', $profile->getToken());
    }

    public function testSetAndGetIp(): void
    {
        $profile = new Profile('dd5ad');

        $profile->setIp('127.0.0.1');

        $this->assertSame('127.0.0.1', $profile->getIp());
    }

    public function testSetAndGetMethod(): void
    {
        $profile = new Profile('5da7da');

        $profile->setMethod('GET');

        $this->assertSame('GET', $profile->getMethod());
    }

    public function testSetAndGetUrl(): void
    {
        $profile = new Profile('dsa8da');

        $profile->setUrl('/');

        $this->assertSame('/', $profile->getUrl());
    }

    public function testSetAndGetTime(): void
    {
        $profile = new Profile('5d7asd57as2');

        $this->assertSame('0', $profile->getTime());

        $profile = new Profile('a7das6d');

        $profile->setTime(12115.13);

        $this->assertSame('12115.13', $profile->getTime());
    }

    public function testGetAndSetDate(): void
    {
        $profile = new Profile('dad65');

        $profile->setDate('12/12/2012');

        $this->assertSame('12/12/2012', $profile->getDate());
    }

    public function testSetAndGetStatus(): void
    {
        $profile = new Profile('da56sd6a');

        $profile->setStatusCode(500);

        $this->assertSame('500', $profile->getStatusCode());
    }

    public function testSetGetHasAllCollectors(): void
    {
        $profile = new Profile('d5adas96');

        $collector = new PhpInfoDataCollector();

        $profile->setCollectors([
            $collector->getName() => [
                'collector' => $collector,
            ],
        ]);

        $this->assertInstanceOf(PhpInfoDataCollector::class, $profile->getCollector('php-info-data-collector'));
        $this->assertTrue($profile->hasCollector('php-info-data-collector'));
        $this->assertEquals(
            [
                'php-info-data-collector' => new PhpInfoDataCollector(),
            ],
            $profile->getCollectors()
        );
    }

    public function testGetCollectorTothrowException(): void
    {
        $this->expectException(\Viserio\Component\Contract\Profiler\Exception\CollectorNotFoundException::class);
        $this->expectExceptionMessage('Collector [dont] not found.');

        $profile = new Profile('d5adas96');
        $profile->getCollector('dont');
    }
}
