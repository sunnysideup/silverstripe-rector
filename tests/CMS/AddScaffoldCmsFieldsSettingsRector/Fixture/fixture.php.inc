<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    class MixedPage extends SiteTree {
        private static $db = ['Intro' => 'Text'];
        private static $many_many = ['Tags' => 'Tag'];
        private static $belongs_many_many = ['Categories' => 'Category'];
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    class MixedPage extends SiteTree
    {
        private static $db = ['Intro' => 'Text'];
        private static $many_many = ['Tags' => 'Tag'];
        private static $belongs_many_many = ['Categories' => 'Category'];
        /**
         * This property is used by the Rector upgrader to manage CMS field scaffolding.
         * Manual modifications to the array values are permitted, but the property should remain defined.
         * @see https://github.com/wernerkrauss/silverstripe-rector
         */
        private static array $scaffold_cms_fields_settings = ['ignoreFields' => ['Intro'], 'includeRelations' => [], 'restrictRelations' => [], 'ignoreRelations' => ['Tags', 'Categories'], 'restrictFields' => []];
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    // Edge Case 1: Empty arrays
    class EdgeEmptyArrays extends SiteTree { 
        private static $db = []; 
        private static $has_one = []; 
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    // Edge Case 1: Empty arrays
    class EdgeEmptyArrays extends SiteTree
    {
        private static $db = [];
        private static $has_one = [];
        /**
         * This property is used by the Rector upgrader to manage CMS field scaffolding.
         * Manual modifications to the array values are permitted, but the property should remain defined.
         * @see https://github.com/wernerkrauss/silverstripe-rector
         */
        private static array $scaffold_cms_fields_settings = ['ignoreFields' => [], 'includeRelations' => [], 'restrictRelations' => [], 'ignoreRelations' => [], 'restrictFields' => []];
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }
namespace App {
    class BasePage extends \SilverStripe\CMS\Model\SiteTree {}
}

namespace App {
    // Edge Case 2: Deep inheritance
    class EdgeDeepInheritance extends BasePage { 
        private static $db = ['Deep' => 'Varchar']; 
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }
namespace App {
    class BasePage extends \SilverStripe\CMS\Model\SiteTree {}
}

namespace App {
    // Edge Case 2: Deep inheritance
    class EdgeDeepInheritance extends BasePage
    {
        private static $db = ['Deep' => 'Varchar'];
        /**
         * This property is used by the Rector upgrader to manage CMS field scaffolding.
         * Manual modifications to the array values are permitted, but the property should remain defined.
         * @see https://github.com/wernerkrauss/silverstripe-rector
         */
        private static array $scaffold_cms_fields_settings = ['ignoreFields' => ['Deep'], 'includeRelations' => [], 'restrictRelations' => [], 'ignoreRelations' => [], 'restrictFields' => []];
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    // Edge Case 3: Public / Protected properties
    class EdgeVisibility extends SiteTree { 
        protected static $db = ['Prot' => 'Varchar']; 
        public static $has_one = ['Pub' => 'Image']; 
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    // Edge Case 3: Public / Protected properties
    class EdgeVisibility extends SiteTree
    {
        protected static $db = ['Prot' => 'Varchar'];
        public static $has_one = ['Pub' => 'Image'];
        /**
         * This property is used by the Rector upgrader to manage CMS field scaffolding.
         * Manual modifications to the array values are permitted, but the property should remain defined.
         * @see https://github.com/wernerkrauss/silverstripe-rector
         */
        private static array $scaffold_cms_fields_settings = ['ignoreFields' => ['Prot'], 'includeRelations' => [], 'restrictRelations' => [], 'ignoreRelations' => ['Pub'], 'restrictFields' => []];
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    // Edge Case 4: Non-string keys (should skip numeric keys)
    class EdgeNonStringKeys extends SiteTree { 
        private static $db = ['Valid' => 'Varchar', 'InvalidBecauseNumeric']; 
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    // Edge Case 4: Non-string keys (should skip numeric keys)
    class EdgeNonStringKeys extends SiteTree
    {
        private static $db = ['Valid' => 'Varchar', 'InvalidBecauseNumeric'];
        /**
         * This property is used by the Rector upgrader to manage CMS field scaffolding.
         * Manual modifications to the array values are permitted, but the property should remain defined.
         * @see https://github.com/wernerkrauss/silverstripe-rector
         */
        private static array $scaffold_cms_fields_settings = ['ignoreFields' => ['Valid'], 'includeRelations' => [], 'restrictRelations' => [], 'ignoreRelations' => [], 'restrictFields' => []];
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    // Edge Case 5: Grouped property declaration
    class EdgeGrouped extends SiteTree { 
        private static $db = ['A' => 'Varchar'], $has_one = ['B' => 'Image']; 
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    // Edge Case 5: Grouped property declaration
    class EdgeGrouped extends SiteTree
    {
        private static $db = ['A' => 'Varchar'], $has_one = ['B' => 'Image'];
        /**
         * This property is used by the Rector upgrader to manage CMS field scaffolding.
         * Manual modifications to the array values are permitted, but the property should remain defined.
         * @see https://github.com/wernerkrauss/silverstripe-rector
         */
        private static array $scaffold_cms_fields_settings = ['ignoreFields' => ['A'], 'includeRelations' => [], 'restrictRelations' => [], 'ignoreRelations' => ['B'], 'restrictFields' => []];
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    // Edge Case 6: No default value defined
    class EdgeNoDefault extends SiteTree { 
        private static $db; 
        private static $has_one; 
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    // Edge Case 6: No default value defined
    class EdgeNoDefault extends SiteTree
    {
        private static $db;
        private static $has_one;
        /**
         * This property is used by the Rector upgrader to manage CMS field scaffolding.
         * Manual modifications to the array values are permitted, but the property should remain defined.
         * @see https://github.com/wernerkrauss/silverstripe-rector
         */
        private static array $scaffold_cms_fields_settings = ['ignoreFields' => [], 'includeRelations' => [], 'restrictRelations' => [], 'ignoreRelations' => [], 'restrictFields' => []];
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    // Edge Case 7: Abstract class
    abstract class EdgeAbstract extends SiteTree { 
        private static $db = ['A' => 'Varchar']; 
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    // Edge Case 7: Abstract class
    abstract class EdgeAbstract extends SiteTree { 
        private static $db = ['A' => 'Varchar']; 
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    // Edge Case 8: Anonymous class
    $page = new class extends SiteTree { 
        private static $db = ['A' => 'Varchar']; 
    };
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    // Edge Case 8: Anonymous class
    $page = new class extends SiteTree { 
        private static $db = ['A' => 'Varchar']; 
    };
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    // Edge Case 9: DataObject with 'Page' in name (false positive check)
    class PageCategory extends \SilverStripe\ORM\DataObject { 
        private static $db = ['A' => 'Varchar']; 
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    // Edge Case 9: DataObject with 'Page' in name (false positive check)
    class PageCategory extends \SilverStripe\ORM\DataObject { 
        private static $db = ['A' => 'Varchar']; 
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    // Edge Case 10: Extension explicitly named for SiteTree
    class EdgeSiteTreeExtension extends \SilverStripe\Core\Extension { 
        private static $has_one = ['Owner' => 'Member']; 
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    // Edge Case 10: Extension explicitly named for SiteTree
    class EdgeSiteTreeExtension extends \SilverStripe\Core\Extension
    {
        private static $has_one = ['Owner' => 'Member'];
        /**
         * This property is used by the Rector upgrader to manage CMS field scaffolding.
         * Manual modifications to the array values are permitted, but the property should remain defined.
         * @see https://github.com/wernerkrauss/silverstripe-rector
         */
        private static array $scaffold_cms_fields_settings = ['ignoreFields' => [], 'includeRelations' => [], 'restrictRelations' => [], 'ignoreRelations' => ['Owner'], 'restrictFields' => []];
    }
}
