<?php

declare(strict_types=1);


namespace Phpro\AgentRules\Source;

final readonly class Source
{
    public function __construct(
        public string $name,
        public string $reference,
        public string $content,
    ) {
    }
}
