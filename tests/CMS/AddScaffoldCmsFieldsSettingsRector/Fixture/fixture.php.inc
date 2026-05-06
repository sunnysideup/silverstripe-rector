<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\CMS\Model\SiteTree;
    class HomePage extends SiteTree {}
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

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
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    use SilverStripe\Core\Extension;
    class PageExtension extends Extension {}
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

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
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    class ContactPage extends \SilverStripe\CMS\Model\SiteTree {}
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

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
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    class BlogPage extends \SilverStripe\CMS\Model\SiteTree {
        private static $db = ['Author' => 'Varchar'];
    }
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

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
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    class NonPageData extends \SilverStripe\ORM\DataObject {}
}
-----
<?php
namespace SilverStripe\CMS\Model { class SiteTree {} }
namespace SilverStripe\Core { class Extension {} }
namespace SilverStripe\ORM { class DataObject {} }

namespace App {
    class NonPageData extends \SilverStripe\ORM\DataObject {}
}
