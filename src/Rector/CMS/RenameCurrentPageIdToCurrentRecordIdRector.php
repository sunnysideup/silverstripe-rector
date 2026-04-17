<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\CMS;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RenameCurrentPageIdToCurrentRecordIdRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename CMSPageEditController::currentPageID() to currentRecordID(), 
            See https://docs.silverstripe.org/en/6/changelogs/6.0.0/#silverstripeadmin',
            [
                new CodeSample(
                    '$controller->currentPageID();',
                    '$controller->currentRecordID();'
                )
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node->name, 'currentPageID')) {
            return null;
        }

        // Use ObjectType without leading backslash to leverage PHPStan's recursive reflection
        if (! $this->isObjectType($node->var, new ObjectType('SilverStripe\CMS\Controllers\CMSPageEditController'))) {
            return null;
        }

        // Mutate the AST node directly
        $node->name = new Identifier('currentRecordID');

        return $node;
    }
}
