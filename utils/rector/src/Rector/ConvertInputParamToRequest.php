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
use PhpParser\Node\Name\FullyQualified;

final class ConvertInputParamToRequest extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
    */
    
    public function getNodeTypes(): array
    {
        return [Node\Expr\StaticCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        $arrayPropertyInput = ['param', 'method'];
        if (!$node instanceof Node\Expr\StaticCall) {
            return null;
        }

        if (!$this->isNames($node->name, $arrayPropertyInput)) {
            return null;
        }

        if (!$this->isName($node->class, 'Input')) {
            return null;
        }

        $nodeName = $node->name->name;

        return new StaticCall(
            new FullyQualified('Request'),
            $nodeName == 'param' ? new Node\Identifier('input') : new Node\Identifier($nodeName),
            $node->args
        );
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