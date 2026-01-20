<?php

declare(strict_types=1);


namespace Phpro\AgentRules;

use Psl\Graph;
use function Psl\Vec\map;

final readonly class RulesAnalyzer
{

    /**
     * @template T
     *
     * @param iterable<RuleInterface<T>> $rules
     *
     * @return list<RuleInterface<T>>
     *
     * @throws \InvalidArgumentException When a rule depends on an unknown rule.
     */
    public static function resolve(iterable $rules): array
    {
        $graph = Graph\directed();
        $rulesByName = [];
        foreach ($rules as $rule) {
            $graph = Graph\add_node($graph, $rule->name());
            $rulesByName[$rule->name()] = $rule;
        }

        foreach ($rules as $rule) {
            foreach ($rule->dependencies() as $dependency) {
                if (!array_key_exists($dependency, $rulesByName)) {
                    throw new \InvalidArgumentException(
                        "Rule '{$rule->name()}' depends on unknown rule '{$dependency}'"
                    );
                }

                $graph = Graph\add_edge($graph, $dependency, $rule->name());
            }
        }

        $sortedNames = Graph\topological_sort($graph);
        if ($sortedNames === null) {
            throw new \InvalidArgumentException('Cyclic dependency detected among rules.');
        }

        return map(
            $sortedNames,
            static fn (string $name): RuleInterface => $rulesByName[$name]
        );
    }
}

