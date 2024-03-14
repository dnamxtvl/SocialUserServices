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

final class ConvertMessageFlashToSession extends AbstractRector
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
        $arrayTypeMessageFlase = ['info', 'error'];
        if (!$node instanceof Node\Expr\StaticCall) {
            return null;
        }

        if (!$this->isNames($node->name, $arrayTypeMessageFlase)) {
            return null;
        }

        if (!$this->isName($node->class, 'Messages')) {
            return null;
        }

        $nodeName = $node->name->name;

        $node->name = new Node\Identifier('flash');

        $newNode = new StaticCall(
            new FullyQualified('Session'),
            new Identifier('flash'),
            $node->args
        );

        $newNode->args = [
            new Node\Arg(new Node\Scalar\String_($nodeName)),
            ...$node->args
        ];

        return $newNode;
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