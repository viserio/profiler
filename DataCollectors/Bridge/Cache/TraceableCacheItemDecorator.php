<?php
declare(strict_types=1);
namespace Viserio\Component\Profiler\DataCollectors\Bridge\Cache;

use Psr\Cache\CacheItemPoolInterface;
use Viserio\Component\Profiler\DataCollectors\Bridge\Cache\Traits\TraceableCacheItemDecoratorTrait;

/**
 * Ported from.
 *
 * @link Symfony\Component\Cache\Adapter\TraceableAdapter
 */
final class TraceableCacheItemDecorator implements CacheItemPoolInterface
{
    use TraceableCacheItemDecoratorTrait;

    /**
     * List of event calls.
     *
     * @var array
     */
    private $calls = [];

    /**
     * A instance of the item pool.
     *
     * @var \Psr\Cache\CacheItemPoolInterface
     */
    private $pool;

    /**
     * Original class name.
     *
     * @var string
     */
    private $name;

    /**
     * Create new Traceable Cache Item Decorator instance.
     *
     * @param \Psr\Cache\CacheItemPoolInterface $pool
     */
    public function __construct(CacheItemPoolInterface $pool)
    {
        $this->name = get_class($pool);
        $this->pool = $pool;
    }

    /**
     * Get the original class name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $event = $this->start(__FUNCTION__);

        try {
            return $event->result = $this->pool->clear();
        } finally {
            $event->end = microtime(true);
        }
    }

    /**
     * Get a list of calls.
     *
     * @return array
     */
    public function getCalls(): array
    {
        try {
            return $this->calls;
        } finally {
            $this->calls = [];
        }
    }

    /**
     * Start new event.
     *
     * @param string $name
     *
     * @return object
     */
    private function start(string $name)
    {
        $this->calls[] = $event = new class() {
            public $name;
            public $start;
            public $end;
            public $result;
            public $hits   = 0;
            public $misses = 0;
        };

        $event->name  = $name;
        $event->start = microtime(true);

        return $event;
    }
}
