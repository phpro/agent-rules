<?php

declare(strict_types=1);

namespace Phpro\AgentRules;

use Phpro\AgentRules\Result\ResultInterface;

final readonly class RuleEvaluation
{
    private function __construct(
        public ?ResultInterface $result
    ) {
    }

    public static function pass(): self
    {
        return new self(null);
    }

    public static function respond(ResultInterface $result): self
    {
        return new self($result);
    }

    public function isPass(): bool
    {
        return $this->result === null;
    }
}
