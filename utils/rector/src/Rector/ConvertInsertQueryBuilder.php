<?php

namespace Utils\Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Expr\MethodCall;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\BuilderHelpers;
use PhpParser\Node\Expr;

final class ConvertInsertQueryBuilder extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    
     public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node->name, 'execute')) {
            return null;
        }

        if (!$node->var) {
            return null;
        }

        if (! $this->isName($node->var->name, 'set')) {
            return null;
        }

        if (!$node->var->var) {
            return null;
        }

        if (! $this->isName($node->var->var->name, 'insert')) {
            return null;
        }

        $node->var->name = new Node\Identifier('insert');
        $node->var->var->name = new Node\Identifier('table');

        $selectNode = $node->var;

        return $selectNode;
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