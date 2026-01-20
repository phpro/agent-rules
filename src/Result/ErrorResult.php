<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Result;

final readonly class ErrorResult extends AbstractResult
{
    public function __construct(
        public string $message,
        public string $resolution,
    )  {
        parent::__construct();
    }

    #[\Override]
    public function getStatus(): string
    {
        return 'error';
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return [
            ...parent::jsonSerialize(),
            'message' => $this->message,
            'resolution' => $this->resolution,
        ];
    }
}
