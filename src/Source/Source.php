<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Source;

final readonly class Source implements \JsonSerializable
{
    public function __construct(
        public string $name,
        public string $reference,
        public string $content,
    ) {
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'reference' => $this->reference,
            'content' => $this->content,
        ];
    }
}
