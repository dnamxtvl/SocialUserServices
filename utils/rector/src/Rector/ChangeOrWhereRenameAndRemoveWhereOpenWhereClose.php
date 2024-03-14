<?php

namespace Utils\Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Expr\MethodCall;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use PhpParser\Node\Expr\StaticCall;

final class ChangeOrWhereRenameAndRemoveWhereOpenWhereClose extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
{
    return [MethodCall::class];
}

public function refactor(Node $selectArrayNode): ?Node
{
    if ($this->isNames($selectArrayNode->name, ['where_close', 'where_open'])) {
        return $selectArrayNode->var;
    }

    if (!$this->isNames($selectArrayNode->name, ['or_where', 'and_where'])) {
        return null;
    }

    $selectArrayNode->name = $this->isName($selectArrayNode->name, 'or_where') ?
        new Node\Identifier('orWhere') : new Node\Identifier('andWhere');

    return $selectArrayNode;
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