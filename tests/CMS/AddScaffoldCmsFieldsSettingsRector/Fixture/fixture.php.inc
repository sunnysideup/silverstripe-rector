<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    class EmptyPage extends SiteTree {
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    class EmptyPage extends SiteTree
    {
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
    class DbOnlyPage extends SiteTree {
        private static $db = ['Title' => 'Varchar', 'Content' => 'HTMLText'];
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    class DbOnlyPage extends SiteTree
    {
        private static $db = ['Title' => 'Varchar', 'Content' => 'HTMLText'];
        /**
         * This property is used by the Rector upgrader to manage CMS field scaffolding.
         * Manual modifications to the array values are permitted, but the property should remain defined.
         * @see https://github.com/wernerkrauss/silverstripe-rector
         */
        private static array $scaffold_cms_fields_settings = ['ignoreFields' => ['Title', 'Content'], 'includeRelations' => [], 'restrictRelations' => [], 'ignoreRelations' => [], 'restrictFields' => []];
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    class RelationOnlyPage extends SiteTree {
        private static $has_one = ['Image' => 'Image'];
        private static $has_many = ['Slides' => 'Slide'];
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    class RelationOnlyPage extends SiteTree
    {
        private static $has_one = ['Image' => 'Image'];
        private static $has_many = ['Slides' => 'Slide'];
        /**
         * This property is used by the Rector upgrader to manage CMS field scaffolding.
         * Manual modifications to the array values are permitted, but the property should remain defined.
         * @see https://github.com/wernerkrauss/silverstripe-rector
         */
        private static array $scaffold_cms_fields_settings = ['ignoreFields' => [], 'includeRelations' => [], 'restrictRelations' => [], 'ignoreRelations' => ['Image', 'Slides'], 'restrictFields' => []];
    }
}
-----
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
    use SilverStripe\Core\Extension;
    class PageExtension extends Extension {
        private static $db = ['ExtField' => 'Boolean'];
        private static $has_one = ['ExtRel' => 'Object'];
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\Core\Extension;
    class PageExtension extends Extension
    {
        private static $db = ['ExtField' => 'Boolean'];
        private static $has_one = ['ExtRel' => 'Object'];
        /**
         * This property is used by the Rector upgrader to manage CMS field scaffolding.
         * Manual modifications to the array values are permitted, but the property should remain defined.
         * @see https://github.com/wernerkrauss/silverstripe-rector
         */
        private static array $scaffold_cms_fields_settings = ['ignoreFields' => ['ExtField'], 'includeRelations' => [], 'restrictRelations' => [], 'ignoreRelations' => ['ExtRel'], 'restrictFields' => []];
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    class PreExistingPage extends SiteTree {
        private static $db = ['Test' => 'Varchar'];
        private static array $scaffold_cms_fields_settings = ['ignoreFields' => ['Custom']];
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    class PreExistingPage extends SiteTree {
        private static $db = ['Test' => 'Varchar'];
        private static array $scaffold_cms_fields_settings = ['ignoreFields' => ['Custom']];
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    class RegularDataObject extends \SilverStripe\ORM\DataObject {
        private static $db = ['Test' => 'Varchar'];
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    class RegularDataObject extends \SilverStripe\ORM\DataObject {
        private static $db = ['Test' => 'Varchar'];
    }
}
