<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Result;

use Symfony\AI\Agent\Toolbox\Source\Source;
use Symfony\AI\Agent\Toolbox\Source\SourceMap;

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

    public function addSources(Source ... $sources): self
    {
        foreach ($sources as $source) {
            $this->sources->addSource($source);
        }

        return $this;
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return [
            'status' => $this->getStatus(),
        ];
    }
}
