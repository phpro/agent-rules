<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Result;

final readonly class IncompleteResult extends AbstractResult
{
    public function __construct(
        public string $missingField,
        public string $message
    )  {
        parent::__construct();
    }

    #[\Override]
    public function getStatus(): string
    {
        return 'incomplete';
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return [
            ...parent::jsonSerialize(),
            'missing' => $this->missingField,
            'message' => $this->message,
        ];
    }
}
