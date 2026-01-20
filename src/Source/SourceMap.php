<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Source;

use Psl\Iter;

/**
 * @implements \IteratorAggregate<int, Source>
 */
final class SourceMap implements \IteratorAggregate, \Countable
{
    /**
     * @var list<Source>
     */
    private array $sources;

    /**
     * @no-named-arguments
     */
    public function __construct(
        Source ... $sources,
    ) {
        $this->sources = $sources;
    }

    /**
     * @return list<Source>
     */
    public function sources(): array
    {
        return $this->sources;
    }

    public function add(Source ... $sources): self
    {
        $this->sources = [...$this->sources, ...$sources];

        return $this;
    }

    #[\Override]
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->sources);
    }

    #[\Override]
    public function count(): int
    {
        return Iter\count($this->sources);
    }
}
