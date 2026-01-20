<?php

declare(strict_types=1);


namespace Phpro\AgentRules;

/**
 * @template T
 */
interface RuleInterface
{
    public function name(): string;

    /**
     * @return list<string>
     */
    public function dependencies(): array;

    /**
     * @param T $subject
     */
    public function check(mixed $subject): RuleEvaluation;
}
