<?php

namespace Utils\Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Expr\MethodCall;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use PhpParser\Node\Expr\StaticCall;

final class ConvertCurrentToFirstRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        // what node types are we looking for?
        // pick from
        // https://github.com/rectorphp/php-parser-nodes-docs/
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        $methodCallName = $this->getName($node->name);
        if ($methodCallName === null) {
            return null;
        }

        if (!$this->isName($node->name, 'current')) {
            return null;
        }

        if (!$node->var || !$node->var instanceof MethodCall) {
            return null;
        }

        if ($this->isName($node->var->name, 'execute')) {
            $node = $node->var;

            return new MethodCall(
                $node->var,
                'first'
            );
        }

        return $node;
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
                    'DB::select()->current();',
                    // code after
                    'DB::select()->first();'
                ),
            ]
        );
    }
    
}