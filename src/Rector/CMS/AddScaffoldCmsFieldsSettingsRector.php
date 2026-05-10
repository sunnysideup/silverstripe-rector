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
        return new RuleDefinition('Adds $scaffold_cms_fields_settings to SiteTree subclasses and Extensions, auto-populating ignoreFields and ignoreRelations.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class BlogPage extends \SilverStripe\CMS\Model\SiteTree {
    private static $db = ['Author' => 'Varchar'];
    private static $has_one = ['HeroImage' => 'Image'];
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class BlogPage extends \SilverStripe\CMS\Model\SiteTree {
    private static $db = ['Author' => 'Varchar'];
    private static $has_one = ['HeroImage' => 'Image'];
    /**
     * This property is used by the Rector upgrader to manage CMS field scaffolding.
     * Manual modifications to the array values are permitted, but the property should remain defined.
     * @see https://github.com/wernerkrauss/silverstripe-rector
     */
    private static array $scaffold_cms_fields_settings = [
        'ignoreFields' => ['Author'],
        'includeRelations' => [],
        'restrictRelations' => [],
        'ignoreRelations' => ['HeroImage'],
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
        
        // Skip modifying the core framework classes themselves
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

        $ignoreFields = $this->extractArrayKeys($node, ['db']);
        $ignoreRelations = $this->extractArrayKeys($node, ['has_one', 'has_many', 'many_many', 'belongs_to', 'belongs_many_many']);
        
        $node->stmts[] = $this->createSettingsProperty($ignoreFields, $ignoreRelations);

        return $node;
    }

    private function extractArrayKeys(Class_ $class, array $propertiesToCheck): array
    {
        $keys = [];

        foreach ($propertiesToCheck as $propName) {
            $property = $class->getProperty($propName);
            if (!$property) {
                continue;
            }

            foreach ($property->props as $prop) {
                // Skip other grouped properties in the same statement
                if ($prop->name->toString() !== $propName) {
                    continue;
                }

                if ($prop->default instanceof Array_) {
                    foreach ($prop->default->items as $item) {
                        if ($item !== null && $item->key instanceof String_) {
                            $keys[] = $item->key->value;
                        }
                    }
                }
            }
        }

        return array_unique($keys);
    }

    private function createSettingsProperty(array $ignoreFields, array $ignoreRelations): Property
    {
        $ignoreFieldArrayItems = [];
        foreach ($ignoreFields as $field) {
            $ignoreFieldArrayItems[] = new ArrayItem(new String_($field));
        }

        $ignoreRelationArrayItems = [];
        foreach ($ignoreRelations as $relation) {
            $ignoreRelationArrayItems[] = new ArrayItem(new String_($relation));
        }

        $items = [
            new ArrayItem(new Array_($ignoreFieldArrayItems, ['kind' => Array_::KIND_SHORT]), new String_('ignoreFields')),
            new ArrayItem(new Array_([], ['kind' => Array_::KIND_SHORT]), new String_('includeRelations')),
            new ArrayItem(new Array_([], ['kind' => Array_::KIND_SHORT]), new String_('restrictRelations')),
            new ArrayItem(new Array_($ignoreRelationArrayItems, ['kind' => Array_::KIND_SHORT]), new String_('ignoreRelations')),
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
