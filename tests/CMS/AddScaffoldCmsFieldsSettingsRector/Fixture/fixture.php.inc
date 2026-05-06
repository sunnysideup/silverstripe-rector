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
        private static array $scaffold_cms_fields_settings = ['ignoreFields' => [], 'includeRelations' => [], 'restrictRelations' => [], 'ignoreRelations' => [], 'restrictFields' => []];
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
        private static array $scaffold_cms_fields_settings = ['ignoreFields' => [], 'includeRelations' => [], 'restrictRelations' => [], 'ignoreRelations' => [], 'restrictFields' => []];
    }
}
-----
<?php
namespace App {
    class SubPage extends HomePage {}
}
-----
<?php
namespace App {
    class SubPage extends HomePage {
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
    class VirtualPage extends \SilverStripe\CMS\Model\SiteTree {
        private static $has_many = ['Versions' => 'Page'];
    }
}
-----
<?php
namespace App {
    class VirtualPage extends \SilverStripe\CMS\Model\SiteTree {
        private static $has_many = ['Versions' => 'Page'];
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
    class NewsPage extends \SilverStripe\CMS\Model\SiteTree {
        private static $many_many = ['Tags' => 'Tag'];
    }
}
-----
<?php
namespace App {
    class NewsPage extends \SilverStripe\CMS\Model\SiteTree {
        private static $many_many = ['Tags' => 'Tag'];
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
    class PageControllerExtension extends \SilverStripe\Core\Extension {}
}
-----
<?php
namespace App {
    class PageControllerExtension extends \SilverStripe\Core\Extension {
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
    class ErrorPage extends \SilverStripe\CMS\Model\SiteTree {
        private static $db = ['ErrorCode' => 'Int'];
    }
}
-----
<?php
namespace App {
    class ErrorPage extends \SilverStripe\CMS\Model\SiteTree {
        private static $db = ['ErrorCode' => 'Int'];
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
    class UserDefinedForm extends \SilverStripe\CMS\Model\SiteTree {
        private static $has_many = ['Fields' => 'FormField'];
    }
}
-----
<?php
namespace App {
    class UserDefinedForm extends \SilverStripe\CMS\Model\SiteTree {
        private static $has_many = ['Fields' => 'FormField'];
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
    class SiteTree_Something extends \SilverStripe\Core\Extension {}
}
-----
<?php
namespace App {
    class SiteTree_Something extends \SilverStripe\Core\Extension {
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
    class SearchPage extends \SilverStripe\CMS\Model\SiteTree {
        private static $db = ['Query' => 'Text'];
    }
}
-----
<?php
namespace App {
    class SearchPage extends \SilverStripe\CMS\Model\SiteTree {
        private static $db = ['Query' => 'Text'];
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
    class SilverStripeExtension extends \SilverStripe\Core\Extension {}
}
-----
<?php
namespace App {
    class SilverStripeExtension extends \SilverStripe\Core\Extension {}
}
-----
<?php
namespace App {
    class Folder extends \SilverStripe\Assets\Folder {}
}
-----
<?php
namespace App {
    class Folder extends \SilverStripe\Assets\Folder {}
}
-----
<?php
namespace App {
    class SiteConfig extends \SilverStripe\SiteConfig\SiteConfig {}
}
-----
<?php
namespace App {
    class SiteConfig extends \SilverStripe\SiteConfig\SiteConfig {}
}
-----
<?php
namespace App {
    class MemberExtension extends \SilverStripe\Core\Extension {}
}
-----
<?php
namespace App {
    class MemberExtension extends \SilverStripe\Core\Extension {}
}
