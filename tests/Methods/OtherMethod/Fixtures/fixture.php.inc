<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\OtherMethod\Fixture;

use SilverStripe\Core\Manifest\VersionProvider;
use SilverStripe\Control\Middleware\URLSpecialsMiddleware\SessionEnvTypeSwitcher;

class VersionProvider {
    public function getComposerLock() {}
}

class SiteTree {
    public static function getPermissionChecker() {}
}

class MigrationTask {
    public function up() {}
}

trait SessionEnvTypeSwitcher {}

class MyTask extends MigrationTask {
    // 6. trait use for removed trait with m === '' (should annotate)
    use SessionEnvTypeSwitcher;

    // 5. overriding class method in subclass (should annotate)
    public function up() {}
}

class Demo {
    public function execute(VersionProvider $vp, $unknownObject) {
        
        // 1. known typed instance method call (should annotate)
        $vp->getComposerLock();

        // 2. unknown typed instance method call + fallback safe = true (should annotate)
        $unknownObject->getPermissionChecker();

        // 3. unknown typed instance method call + fallback safe = false (should NOT annotate)
        $unknownObject->getComposerLock();

        // 4. static method call for "now static" / "no longer static" case (should annotate)
        SiteTree::getPermissionChecker();

        // 7. already annotated code (should not duplicate)
        /** @TODO SSU RECTOR UPGRADE TASK - SilverStripe\Core\Manifest\VersionProvider::getComposerLock: has been replaced by composer-runtime-api */
        $vp->getComposerLock();

        // 8. class method with existing docblock (should append TODO, not replace)
        /**
         * Existing useful comment.
         */
        $vp->getComposerLock();
    }
}
-----
<?php

namespace Netwerkstatt\SilverstripeRector\Tests\Methods\OtherMethod\Fixture;

use SilverStripe\Core\Manifest\VersionProvider;
use SilverStripe\Control\Middleware\URLSpecialsMiddleware\SessionEnvTypeSwitcher;

class VersionProvider {
    public function getComposerLock() {}
}

class SiteTree {
    public static function getPermissionChecker() {}
}

class MigrationTask {
    public function up() {}
}

trait SessionEnvTypeSwitcher {}

class MyTask extends MigrationTask {
    // 6. trait use for removed trait with m === '' (should annotate)
    /** @TODO SSU RECTOR UPGRADE TASK - SilverStripe\Control\Middleware\URLSpecialsMiddleware\SessionEnvTypeSwitcher: Removed deprecated trait SilverStripe\Control\Middleware\URLSpecialsMiddleware\SessionEnvTypeSwitcher - removed without equivalent functionality to replace it */
    use SessionEnvTypeSwitcher;

    // 5. overriding class method in subclass (should annotate)
    /** @TODO SSU RECTOR UPGRADE TASK - MigrationTask::up: Method MigrationTask::up() is now abstract */
    public function up() {}
}

class Demo {
    public function execute(VersionProvider $vp, $unknownObject) {
        
        // 1. known typed instance method call (should annotate)
        /** @TODO SSU RECTOR UPGRADE TASK - SilverStripe\Core\Manifest\VersionProvider::getComposerLock: has been replaced by composer-runtime-api */
        $vp->getComposerLock();

        // 2. unknown typed instance method call + fallback safe = true (should annotate)
        /** @TODO SSU RECTOR UPGRADE TASK - SiteTree::getPermissionChecker: Method SiteTree::getPermissionChecker() is no longer static */
        $unknownObject->getPermissionChecker();

        // 3. unknown typed instance method call + fallback safe = false (should NOT annotate)
        $unknownObject->getComposerLock();

        // 4. static method call for "now static" / "no longer static" case (should annotate)
        /** @TODO SSU RECTOR UPGRADE TASK - SiteTree::getPermissionChecker: Method SiteTree::getPermissionChecker() is no longer static */
        SiteTree::getPermissionChecker();

        // 7. already annotated code (should not duplicate)
        /** @TODO SSU RECTOR UPGRADE TASK - SilverStripe\Core\Manifest\VersionProvider::getComposerLock: has been replaced by composer-runtime-api */
        $vp->getComposerLock();

        // 8. class method with existing docblock (should append TODO, not replace)
        /**
         * Existing useful comment.
         * @TODO SSU RECTOR UPGRADE TASK - SilverStripe\Core\Manifest\VersionProvider::getComposerLock: has been replaced by composer-runtime-api
         */
        $vp->getComposerLock();
    }
}   