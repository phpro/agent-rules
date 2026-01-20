<?php

declare(strict_types=1);


namespace Phpro\AgentRules\Result;


final readonly class CompleteResult extends AbstractResult
{
    public function __construct(
        public string $message
    ) {
        parent::__construct();
    }

    #[\Override]
    public function getStatus(): string
    {
        return 'complete';
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return [
            ...parent::jsonSerialize(),
            'message' => $this->message,
        ];
    }
}
