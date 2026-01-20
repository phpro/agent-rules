<?php

declare(strict_types=1);


namespace Phpro\AgentRules\Source;

final class SourceMap
{
    /**
     * @var list<Source>
     */
    public array $sources;

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
}
