<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Result;

use Phpro\AgentRules\Source\Source;
use Phpro\AgentRules\Source\SourceMap;

abstract readonly class AbstractResult implements ResultInterface
{
    private SourceMap $sources;

    public function __construct()
    {
        $this->sources = new SourceMap();
    }

    #[\Override]
    public function sources(): SourceMap
    {
        return $this->sources;
    }

    public function addSources(Source ...$sources): static
    {
        $this->sources->add(...$sources);

        return $this;
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return [
            'status' => $this->getStatus(),
            'sources' => $this->sources->sources(),
        ];
    }
}
