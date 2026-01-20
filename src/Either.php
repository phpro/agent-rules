<?php

declare(strict_types=1);


namespace Phpro\AgentRules;



/**
 * @template T
 *
 * @implements RuleInterface<T>
 */
final readonly class Either implements RuleInterface
{
    /**
     * @param string $name
     * @param RuleInterface<T> $left,
     * @param RuleInterface<T> $right,
     * @param list<string> $dependencies
     */
    public function __construct(
        private string $name,
        private RuleInterface $left,
        private RuleInterface $right,
        private array $dependencies,
    ) {
    }

    #[\Override]
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return list<string>
     */
    #[\Override]
    public function dependencies(): array
    {
        return $this->dependencies;
    }

    #[\Override]
    public function check(mixed $subject): RuleEvaluation
    {
        $leftEvaluation = $this->left->check($subject);
        if ($leftEvaluation->isPass()) {
            return $leftEvaluation;
        }

        $rightEvaluation = $this->right->check($subject);
        if ($rightEvaluation->isPass()) {
            return $rightEvaluation;
        }

        // If both left and right evaluations failed, return the left evaluation by default so that we might be able to recover.
        return $leftEvaluation;
    }
}
