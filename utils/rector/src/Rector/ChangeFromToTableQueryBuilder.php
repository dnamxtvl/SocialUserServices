<?php

namespace Utils\Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Expr\MethodCall;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use PhpParser\Node\Expr\StaticCall;

final class ChangeFromToTableQueryBuilder extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    public function refactor(Node $fromNode): ?Node
    {
        if (!$this->isNames($fromNode->name, ['from', 'from_array'])) {
            return null;
        }
    
        $selectNode = $fromNode->var;
        // if (!$selectNode instanceof StaticCall ||
        //     $this->isNames($selectNode->name, ['select', 'select_array'])) {
        //     return null;
        // }
    
        $query = new MethodCall(
            new StaticCall(
                new Node\Name\FullyQualified('DB'),
                new Node\Identifier('table'),
                $fromNode->args
            ),
            $selectNode->name,
            $selectNode->args
        );

        while ($selectNode->var !== null) {
            $selectNode = $selectNode->var;
        
            $query = new Node\Expr\MethodCall(
                $query,
                $selectNode->name,
                $selectNode->args
            );
        }

        return $query;
    }
    /**
     * This method helps other to understand the rule
     * and to generate documentation.
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change method calls from set* to change*.', [
                new CodeSample(
                    // code before
                    '$user->setPassword("123456");',
                    // code after
                    '$user->changePassword("123456");'
                ),
            ]
        );
    }
}