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
        return new RuleDefinition('Adds $scaffold_cms_fields_settings to SiteTree subclasses and Extensions, auto-populating ignoreFields.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class BlogPage extends \SilverStripe\CMS\Model\SiteTree {
    private static $db = ['Author' => 'Varchar'];
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class BlogPage extends \SilverStripe\CMS\Model\SiteTree {
    private static $db = ['Author' => 'Varchar'];
    /**
     * This property is used by the Rector upgrader to manage CMS field scaffolding.
     * Manual modifications to the array values are permitted, but the property should remain defined.
     * @see https://github.com/wernerkrauss/silverstripe-rector
     */
    private static array $scaffold_cms_fields_settings = [
        'ignoreFields' => ['Author'],
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

        $className = $this->getName($node);
        
        // Skip modifying the core framework classes themselves if they appear in tests or scans
        if ($className === 'SilverStripe\CMS\Model\SiteTree' || $className === 'SilverStripe\Core\Extension') {
            return null;
        }

        $isSiteTree = $this->isObjectType($node, new ObjectType('SilverStripe\CMS\Model\SiteTree'));
        $isExtension = $this->isObjectType($node, new ObjectType('SilverStripe\Core\Extension'));
        
        $isTarget = $isSiteTree;
        if ($isExtension) {
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

        $ignoreFields = $this->extractFieldsToIgnore($node);
        $node->stmts[] = $this->createSettingsProperty($ignoreFields);

        return $node;
    }

    private function extractFieldsToIgnore(Class_ $class): array
    {
        $fieldsToIgnore = [];
        $propertiesToCheck = ['db', 'has_one', 'has_many', 'many_many'];

        foreach ($propertiesToCheck as $propName) {
            $property = $class->getProperty($propName);
            if (!$property) {
                continue;
            }

            foreach ($property->props as $prop) {
                if ($prop->default instanceof Array_) {
                    foreach ($prop->default->items as $item) {
                        if ($item !== null && $item->key instanceof String_) {
                            $fieldsToIgnore[] = $item->key->value;
                        }
                    }
                }
            }
        }

        return array_unique($fieldsToIgnore);
    }

    private function createSettingsProperty(array $ignoreFields): Property
    {
        $ignoreFieldArrayItems = [];
        foreach ($ignoreFields as $field) {
            $ignoreFieldArrayItems[] = new ArrayItem(new String_($field));
        }

        $items = [
            new ArrayItem(new Array_($ignoreFieldArrayItems, ['kind' => Array_::KIND_SHORT]), new String_('ignoreFields')),
            new ArrayItem(new Array_([], ['kind' => Array_::KIND_SHORT]), new String_('includeRelations')),
            new ArrayItem(new Array_([], ['kind' => Array_::KIND_SHORT]), new String_('restrictRelations')),
            new ArrayItem(new Array_([], ['kind' => Array_::KIND_SHORT]), new String_('ignoreRelations')),
            new ArrayItem(new Array_([], ['kind' => Array_::KIND_SHORT]), new String_('restrictFields')),
        ];

        $arrayExpr = new Array_($items, ['kind' => Array_::KIND_SHORT]);

        $property = new Property(
            Modifiers::PRIVATE | Modifiers::STATIC,
            [new PropertyProperty('scaffold_cms_fields_settings', $arrayExpr)],
            [],
            new Identifier('array')
        );

        $docText = "/**\n" .
                   "         * This property is used by the Rector upgrader to manage CMS field scaffolding.\n" .
                   "         * Manual modifications to the array values are permitted, but the property should remain defined.\n" .
                   "         * @see https://github.com/wernerkrauss/silverstripe-rector\n" .
                   "         */";
        $property->setDocComment(new \PhpParser\Comment\Doc($docText));

        return $property;
    }
}
