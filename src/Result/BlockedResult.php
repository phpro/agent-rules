<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Result;

final readonly class BlockedResult extends AbstractResult
{
    public function __construct(
        public string $reason,
        public string $message
    ) {
        parent::__construct();
    }

    #[\Override]
    public function getStatus(): string
    {
        return 'blocked';
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return [
            ...parent::jsonSerialize(),
            'reason' => $this->reason,
            'message' => $this->message,
        ];
    }
}
