<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Rector\CMS;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Identifier;
use PhpParser\Modifiers;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Netwerkstatt\SilverstripeRector\Tests\CMS\AddScaffoldCmsFieldsSettingsRector\AddScaffoldCmsFieldsSettingsRectorTest
 */
final class AddScaffoldCmsFieldsSettingsRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Adds $scaffold_cms_fields_settings to SiteTree subclasses and Extensions applied to SiteTree.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class MyPage extends \SilverStripe\CMS\Model\SiteTree {
    private static $db = ['Feature' => 'Boolean'];
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class MyPage extends \SilverStripe\CMS\Model\SiteTree {
    private static $db = ['Feature' => 'Boolean'];
    /**
     * This property is used by the Rector upgrader to manage CMS field scaffolding.
     * Manual modifications to the array values are permitted, but the property should remain defined.
     * @see https://github.com/wernerkrauss/silverstripe-rector
     */
    private static array $scaffold_cms_fields_settings = [
        'ignoreFields' => [],
        'includeRelations' => [],
        'restrictRelations' => [],
        'ignoreRelations' => [],
        'restrictFields' => [],
    ];
}
CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->isAbstract() || $node->isAnonymous()) {
            return null;
        }

        $isSiteTree = $this->isObjectType($node, new ObjectType('SilverStripe\CMS\Model\SiteTree'));
        
        // We only target SiteTree or Extensions that are likely Page-related
        // In Silverstripe, Extensions applied to SiteTree usually have 'Page' in the name or are explicitly defined
        $isExtension = $this->isObjectType($node, new ObjectType('SilverStripe\Core\Extension'));
        
        $isTarget = $isSiteTree;
        
        if ($isExtension) {
            $className = $this->getName($node);
            // Strict check: Extension must contain 'Page' or 'SiteTree' to be considered a candidate here
            // to avoid polluting DataObject extensions.
            if ($className !== null && (str_contains($className, 'Page') || str_contains($className, 'SiteTree'))) {
                $isTarget = true;
            }
        }

        if (!$isTarget) {
            return null;
        }

        if ($node->getProperty('scaffold_cms_fields_settings') !== null) {
            return null;
        }

        $node->stmts[] = $this->createSettingsProperty();

        return $node;
    }

    private function createSettingsProperty(): Property
    {
        $keys = ['ignoreFields', 'includeRelations', 'restrictRelations', 'ignoreRelations', 'restrictFields'];
        $items = [];
        foreach ($keys as $key) {
            $items[] = new ArrayItem(new Array_([]), new String_($key));
        }

        $property = new Property(
            Modifiers::PRIVATE | Modifiers::STATIC,
            [new PropertyProperty('scaffold_cms_fields_settings', new Array_($items))],
            [],
            new Identifier('array')
        );

        $docText = "/**\n" .
                   " * This property is used by the Rector upgrader to manage CMS field scaffolding.\n" .
                   " * Manual modifications to the array values are permitted, but the property should remain defined.\n" .
                   " * @see https://github.com/wernerkrauss/silverstripe-rector\n" .
                   " */";
        $property->setDocComment(new \PhpParser\Comment\Doc($docText));

        return $property;
    }
}
