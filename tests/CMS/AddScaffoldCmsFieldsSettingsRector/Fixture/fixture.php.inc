<?php
namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    class HomePage extends SiteTree {}
}
-----
<?php
namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    class HomePage extends SiteTree {
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
namespace App {
    use SilverStripe\Core\Extension;
    class PageExtension extends Extension {}
}
-----
<?php
namespace App {
    use SilverStripe\Core\Extension;
    class PageExtension extends Extension {
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
namespace App {
    class ContactPage extends \SilverStripe\CMS\Model\SiteTree {}
}
-----
<?php
namespace App {
    class ContactPage extends \SilverStripe\CMS\Model\SiteTree {
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
namespace App {
    class BlogPage extends \SilverStripe\CMS\Model\SiteTree {
        private static $db = ['Author' => 'Varchar'];
    }
}
-----
<?php
namespace App {
    class BlogPage extends \SilverStripe\CMS\Model\SiteTree {
        private static $db = ['Author' => 'Varchar'];
        /**
 * This property is used by the Rector upgrader to manage CMS field scaffolding.
 * Manual modifications to the array values are permitted, but the property should remain defined.
 * @see https://github.com/wernerkrauss/silverstripe-rector
 */
        private static array $scaffold_cms_fields_settings = ['ignoreFields' => ['Author'], 'includeRelations' => [], 'restrictRelations' => [], 'ignoreRelations' => [], 'restrictFields' => []];
    }
}
-----
<?php
namespace App {
    class NonPageData extends \SilverStripe\ORM\DataObject {}
}
-----
<?php
namespace App {
    class NonPageData extends \SilverStripe\ORM\DataObject {}
}
-----
<?php
namespace App {
    class PageSiteTreeExtension extends \SilverStripe\Core\Extension {}
}
-----
<?php
namespace App {
    class PageSiteTreeExtension extends \SilverStripe\Core\Extension {
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
namespace App {
    class GenericExtension extends \SilverStripe\Core\Extension {}
}
-----
<?php
namespace App {
    class GenericExtension extends \SilverStripe\Core\Extension {}
}
-----
<?php
namespace App {
    class RedirectorPage extends \SilverStripe\CMS\Model\SiteTree {
        private static $has_one = ['Link' => 'SiteTree'];
    }
}
-----
<?php
namespace App {
    class RedirectorPage extends \SilverStripe\CMS\Model\SiteTree {
        private static $has_one = ['Link' => 'SiteTree'];
        /**
 * This property is used by the Rector upgrader to manage CMS field scaffolding.
 * Manual modifications to the array values are permitted, but the property should remain defined.
 * @see https://github.com/wernerkrauss/silverstripe-rector
 */
        private static array $scaffold_cms_fields_settings = ['ignoreFields' => ['Link'], 'includeRelations' => [], 'restrictRelations' => [], 'ignoreRelations' => [], 'restrictFields' => []];
    }
}
-----
<?php
namespace App {
    class MultiFieldPage extends \SilverStripe\CMS\Model\SiteTree {
        private static $db = ['Content' => 'HTMLText'];
        private static $has_many = ['Images' => 'Image'];
    }
}
-----
<?php
namespace App {
    class MultiFieldPage extends \SilverStripe\CMS\Model\SiteTree {
        private static $db = ['Content' => 'HTMLText'];
        private static $has_many = ['Images' => 'Image'];
        /**
 * This property is used by the Rector upgrader to manage CMS field scaffolding.
 * Manual modifications to the array values are permitted, but the property should remain defined.
 * @see https://github.com/wernerkrauss/silverstripe-rector
 */
        private static array $scaffold_cms_fields_settings = ['ignoreFields' => ['Content', 'Images'], 'includeRelations' => [], 'restrictRelations' => [], 'ignoreRelations' => [], 'restrictFields' => []];
    }
}
