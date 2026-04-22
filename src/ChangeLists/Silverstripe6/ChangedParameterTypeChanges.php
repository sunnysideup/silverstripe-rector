<?php


declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6;

use Netwerkstatt\SilverstripeRector\Interfaces\ChangeListInterface;
use Netwerkstatt\SilverstripeRector\Traits\MethodChangeHelper;

class ChangedParameterTypeChanges implements ChangeListInterface
{
    use MethodChangeHelper;

            private const LIST = [
        // AdminRootController
        'c' => 'AdminRootController', 'm' => 'add_rule_for_controller', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $controllerClass from dynamic to string', [cite: 1]

        // AssetFormFactory
        // 'c' => 'AssetFormFactory', 'm' => 'getFormActions', 'p' => 0, 't' => 'RequestHandler|null', 'n' => 'Changed type of parameter $controller from RequestHandler to RequestHandler|null', [cite: 3]
        // 'c' => 'AssetFormFactory', 'm' => 'getFormFields', 'p' => 0, 't' => 'RequestHandler|null', 'n' => 'Changed type of parameter $controller from RequestHandler to RequestHandler|null', [cite: 3]
        // 'c' => 'AssetFormFactory', 'm' => 'getValidator', 'p' => 0, 't' => 'RequestHandler|null', 'n' => 'Changed type of parameter $controller from RequestHandler to RequestHandler|null', [cite: 3]

        // Authenticator
        // 'c' => 'Authenticator', 'm' => 'authenticate', 'p' => 0, 't' => 'ValidationResult|null', 'n' => 'Changed type of parameter $result from SilverStripe\\ORM\\ValidationResult to ValidationResult|null', [cite: 76]
        // 'c' => 'Authenticator', 'm' => 'checkPassword', 'p' => 0, 't' => 'ValidationResult|null', 'n' => 'Changed type of parameter $result from SilverStripe\\ORM\\ValidationResult to ValidationResult|null', [cite: 76]

        // BasicAuth
        // 'c' => 'BasicAuth', 'm' => 'protect_site_if_necessary', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 68]

        // BlogPostFilter
        // 'c' => 'BlogPostFilter', 'm' => 'augmentLoadLazyFields', 'p' => 0, 't' => 'DataQuery|null', 'n' => 'Changed type of parameter $dataQuery from DataQuery to DataQuery|null', [cite: 18]

        // BuildTask
        'c' => 'BuildTask', 'm' => 'run', 'p' => 0, 't' => 'Symfony\\Component\\Console\\Input\\InputInterface', 'n' => 'Changed type of parameter $request from dynamic to Symfony\\Component\\Console\\Input\\InputInterface', [cite: 69]

        // CMSMain
        // 'c' => 'CMSMain', 'm' => 'getTreeNodeClasses', 'p' => 0, 't' => 'DataObject', 'n' => 'Changed type of parameter $node from SiteTree to DataObject', [cite: 20]
        // 'c' => 'CMSMain', 'm' => 'getCMSEditLinkForManagedDataObject', 'p' => 0, 't' => 'DataObject', 'n' => 'Changed type of parameter $obj from SiteTree to DataObject', [cite: 20]
        // 'c' => 'CMSMain', 'm' => 'getArchiveWarningMessage', 'p' => 0, 't' => 'DataObject', 'n' => 'Changed type of parameter $record from dynamic to DataObject', [cite: 21]

        // CanViewPermission
        // 'c' => 'CanViewPermission', 'm' => 'listPermissionCheck', 'p' => 0, 't' => 'SS_List', 'n' => 'Changed type of parameter $obj from SilverStripe\\ORM\\Filterable to SS_List', [cite: 94]

        // CanonicalURLMiddleware
        // 'c' => 'CanonicalURLMiddleware', 'm' => 'getOrValidateRequest', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 69]
        // 'c' => 'CanonicalURLMiddleware', 'm' => 'throwRedirectIfNeeded', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 69]
        // 'c' => 'CanonicalURLMiddleware', 'm' => 'hasBasicAuthPrompt', 'p' => 0, 't' => 'HTTPResponse|null', 'n' => 'Changed type of parameter $response from HTTPResponse to HTTPResponse|null', [cite: 76]

        // ChangePasswordHandler
        'c' => 'ChangePasswordHandler', 'm' => 'setSessionToken', 'p' => 0, 't' => 'Member', 'n' => 'Changed type of parameter $member from dynamic to Member', [cite: 53]
        'c' => 'ChangePasswordHandler', 'm' => 'setSessionToken', 'p' => 1, 't' => 'string', 'n' => 'Changed type of parameter $token from dynamic to string', [cite: 87]

        // ClassManifest
        // 'c' => 'ClassManifest', 'm' => '__construct', 'p' => 0, 't' => 'CacheFactory|null', 'n' => 'Changed type of parameter $cacheFactory from CacheFactory to CacheFactory|null', [cite: 24]

        // ConfirmedPasswordField
        'c' => 'ConfirmedPasswordField', 'm' => 'setMaxLength', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $maxLength from dynamic to int', [cite: 52]
        'c' => 'ConfirmedPasswordField', 'm' => 'setRequireExistingPassword', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $show from dynamic to bool', [cite: 80]
        'c' => 'ConfirmedPasswordField', 'm' => 'setMinLength', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $minLength from dynamic to int', [cite: 59]

        // Convert
        'c' => 'Convert', 'm' => 'linkIfMatch', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $string from dynamic to string', [cite: 80]

        // Cookie
        'c' => 'Cookie', 'm' => 'force_expiry', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $name from dynamic to string', [cite: 59]
        // 'c' => 'Cookie', 'm' => 'force_expiry', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $path from dynamic to string|null', [cite: 65]
        // 'c' => 'Cookie', 'm' => 'force_expiry', 'p' => 2, 't' => 'string|null', 'n' => 'Changed type of parameter $domain from dynamic to string|null', [cite: 34]
        'c' => 'Cookie', 'm' => 'force_expiry', 'p' => 3, 't' => 'bool', 'n' => 'Changed type of parameter $secure from dynamic to bool', [cite: 78]
        'c' => 'Cookie', 'm' => 'force_expiry', 'p' => 4, 't' => 'bool', 'n' => 'Changed type of parameter $httpOnly from dynamic to bool', [cite: 43]
        'c' => 'Cookie', 'm' => 'get', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $name from dynamic to string', [cite: 60]
        'c' => 'Cookie', 'm' => 'get', 'p' => 1, 't' => 'bool', 'n' => 'Changed type of parameter $includeUnsent from dynamic to bool', [cite: 46]
        'c' => 'Cookie', 'm' => 'get_all', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $includeUnsent from dynamic to bool', [cite: 46]
        'c' => 'Cookie', 'm' => 'set', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $name from dynamic to string', [cite: 60]
        // 'c' => 'Cookie', 'm' => 'set', 'p' => 1, 't' => 'string|false', 'n' => 'Changed type of parameter $value from dynamic to string|false', [cite: 89]
        'c' => 'Cookie', 'm' => 'set', 'p' => 2, 't' => 'int|float', 'n' => 'Changed type of parameter $expiry from dynamic to int|float', [cite: 36]
        // 'c' => 'Cookie', 'm' => 'set', 'p' => 3, 't' => 'string|null', 'n' => 'Changed type of parameter $path from dynamic to string|null', [cite: 65]
        // 'c' => 'Cookie', 'm' => 'set', 'p' => 4, 't' => 'string|null', 'n' => 'Changed type of parameter $domain from dynamic to string|null', [cite: 34]
        'c' => 'Cookie', 'm' => 'set', 'p' => 5, 't' => 'bool', 'n' => 'Changed type of parameter $secure from dynamic to bool', [cite: 78]
        'c' => 'Cookie', 'm' => 'set', 'p' => 6, 't' => 'bool', 'n' => 'Changed type of parameter $httpOnly from dynamic to bool', [cite: 43]

        // CookieAuthenticationHandler
        'c' => 'CookieAuthenticationHandler', 'm' => 'setDeviceCookieName', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $deviceCookieName from dynamic to string', [cite: 33]
        'c' => 'CookieAuthenticationHandler', 'm' => 'setTokenCookieName', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $tokenCookieName from dynamic to string', [cite: 87]
        'c' => 'CookieAuthenticationHandler', 'm' => 'setTokenCookieSecure', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $tokenCookieSecure from dynamic to bool', [cite: 87]

        // CookieJar
        'c' => 'CookieJar', 'm' => 'outputCookie', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $name from dynamic to string', [cite: 60]
        // 'c' => 'CookieJar', 'm' => 'outputCookie', 'p' => 1, 't' => 'string|false', 'n' => 'Changed type of parameter $value from dynamic to string|false', [cite: 89]
        'c' => 'CookieJar', 'm' => 'outputCookie', 'p' => 2, 't' => 'int', 'n' => 'Changed type of parameter $expiry from dynamic to int', [cite: 36]
        // 'c' => 'CookieJar', 'm' => 'outputCookie', 'p' => 3, 't' => 'string|null', 'n' => 'Changed type of parameter $path from dynamic to string|null', [cite: 65]
        // 'c' => 'CookieJar', 'm' => 'outputCookie', 'p' => 4, 't' => 'string|null', 'n' => 'Changed type of parameter $domain from dynamic to string|null', [cite: 34]
        'c' => 'CookieJar', 'm' => 'outputCookie', 'p' => 5, 't' => 'bool', 'n' => 'Changed type of parameter $secure from dynamic to bool', [cite: 79]
        'c' => 'CookieJar', 'm' => 'outputCookie', 'p' => 6, 't' => 'bool', 'n' => 'Changed type of parameter $httpOnly from dynamic to bool', [cite: 44]

        // Cookie_Backend
        'c' => 'Cookie_Backend', 'm' => '__construct', 'p' => 0, 't' => 'array', 'n' => 'Changed type of parameter $cookies from dynamic to array', [cite: 29]
        'c' => 'Cookie_Backend', 'm' => 'forceExpiry', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $name from dynamic to string', [cite: 60]
        // 'c' => 'Cookie_Backend', 'm' => 'forceExpiry', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $path from dynamic to string|null', [cite: 66]
        // 'c' => 'Cookie_Backend', 'm' => 'forceExpiry', 'p' => 2, 't' => 'string|null', 'n' => 'Changed type of parameter $domain from dynamic to string|null', [cite: 34]
        'c' => 'Cookie_Backend', 'm' => 'forceExpiry', 'p' => 3, 't' => 'bool', 'n' => 'Changed type of parameter $secure from dynamic to bool', [cite: 79]
        'c' => 'Cookie_Backend', 'm' => 'forceExpiry', 'p' => 4, 't' => 'bool', 'n' => 'Changed type of parameter $httpOnly from dynamic to bool', [cite: 44]
        'c' => 'Cookie_Backend', 'm' => 'get', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $name from dynamic to string', [cite: 61]
        'c' => 'Cookie_Backend', 'm' => 'get', 'p' => 1, 't' => 'bool', 'n' => 'Changed type of parameter $includeUnsent from dynamic to bool', [cite: 46]
        'c' => 'Cookie_Backend', 'm' => 'getAll', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $includeUnsent from dynamic to bool', [cite: 46]
        'c' => 'Cookie_Backend', 'm' => 'set', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $name from dynamic to string', [cite: 61]
        // 'c' => 'Cookie_Backend', 'm' => 'set', 'p' => 1, 't' => 'string|false', 'n' => 'Changed type of parameter $value from dynamic to string|false', [cite: 90]
        'c' => 'Cookie_Backend', 'm' => 'set', 'p' => 2, 't' => 'int|float', 'n' => 'Changed type of parameter $expiry from dynamic to int|float', [cite: 36]
        // 'c' => 'Cookie_Backend', 'm' => 'set', 'p' => 3, 't' => 'string|null', 'n' => 'Changed type of parameter $path from dynamic to string|null', [cite: 66]
        // 'c' => 'Cookie_Backend', 'm' => 'set', 'p' => 4, 't' => 'string|null', 'n' => 'Changed type of parameter $domain from dynamic to string|null', [cite: 35]
        'c' => 'Cookie_Backend', 'm' => 'set', 'p' => 5, 't' => 'bool', 'n' => 'Changed type of parameter $secure from dynamic to bool', [cite: 79]
        'c' => 'Cookie_Backend', 'm' => 'set', 'p' => 6, 't' => 'bool', 'n' => 'Changed type of parameter $httpOnly from dynamic to bool', [cite: 44]

        // CoreConfigFactory
        // 'c' => 'CoreConfigFactory', 'm' => '__construct', 'p' => 0, 't' => 'CacheFactory|null', 'n' => 'Changed type of parameter $cacheFactory from CacheFactory to CacheFactory|null', [cite: 25]

        // CredentialRepositoryProviderTrait
        // 'c' => 'CredentialRepositoryProviderTrait', 'm' => 'getCredentialRepository', 'p' => 0, 't' => 'RegisteredMethod|null', 'n' => 'Changed type of parameter $registeredMethod from RegisteredMethod to RegisteredMethod|null', [cite: 99]

        // DBClassNameTrait
        // 'c' => 'DBClassNameTrait', 'm' => '__construct', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $name from dynamic to string|null', [cite: 61]
        // 'c' => 'DBClassNameTrait', 'm' => '__construct', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $baseClass from dynamic to string|null', [cite: 23]
        'c' => 'DBClassNameTrait', 'm' => '__construct', 'p' => 2, 't' => 'array', 'n' => 'Changed type of parameter $options from dynamic to array', [cite: 64]
        // 'c' => 'DBClassNameTrait', 'm' => 'setBaseClass', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $baseClass from dynamic to string|null', [cite: 24]
        'c' => 'DBClassNameTrait', 'm' => 'setValue', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $value from dynamic to mixed', [cite: 90]
        // 'c' => 'DBClassNameTrait', 'm' => 'setValue', 'p' => 1, 't' => 'null|array|ModelData', 'n' => 'Changed type of parameter $record from dynamic to null|array|ModelData', [cite: 68]
        'c' => 'DBClassNameTrait', 'm' => 'setValue', 'p' => 2, 't' => 'bool', 'n' => 'Changed type of parameter $markChanged from dynamic to bool', [cite: 50]

        // DBComposite
        'c' => 'DBComposite', 'm' => 'bindTo', 'p' => 0, 't' => 'DataObject', 'n' => 'Changed type of parameter $dataObject from dynamic to DataObject', [cite: 30]
        'c' => 'DBComposite', 'm' => 'dbObject', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $field from dynamic to string', [cite: 39]

        // DBDate
        'c' => 'DBDate', 'm' => 'DayOfMonth', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $includeOrdinal from dynamic to bool', [cite: 45]
        'c' => 'DBDate', 'm' => 'Format', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $format from dynamic to string', [cite: 42]
        // 'c' => 'DBDate', 'm' => 'FormatFromSettings', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from dynamic to Member|null', [cite: 53]
        // 'c' => 'DBDate', 'm' => 'getCustomFormatter', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $pattern from dynamic to string|null', [cite: 66]
        // 'c' => 'DBDate', 'm' => 'getCustomFormatter', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $locale from dynamic to string|null', [cite: 49]
        'c' => 'DBDate', 'm' => 'getCustomFormatter', 'p' => 2, 't' => 'int', 'n' => 'Changed type of parameter $dateLength from dynamic to int', [cite: 31]
        'c' => 'DBDate', 'm' => 'getCustomFormatter', 'p' => 3, 't' => 'int', 'n' => 'Changed type of parameter $timeLength from dynamic to int', [cite: 84]
        'c' => 'DBDate', 'm' => 'getFormatter', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $dateLength from dynamic to int', [cite: 31]
        'c' => 'DBDate', 'm' => 'getFormatter', 'p' => 1, 't' => 'int', 'n' => 'Changed type of parameter $timeLength from dynamic to int', [cite: 84]
        'c' => 'DBDate', 'm' => 'parseDate', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $value from dynamic to mixed', [cite: 90]

        // DBDatetime
        'c' => 'DBDatetime', 'm' => 'set_mock_now', 'p' => 0, 't' => 'DBDatetime|string', 'n' => 'Changed type of parameter $datetime from dynamic to DBDatetime|string', [cite: 32]
        'c' => 'DBDatetime', 'm' => 'withFixedNow', 'p' => 0, 't' => 'DBDatetime|string', 'n' => 'Changed type of parameter $time from dynamic to DBDatetime|string', [cite: 85]
        'c' => 'DBDatetime', 'm' => 'withFixedNow', 'p' => 1, 't' => 'callable', 'n' => 'Changed type of parameter $callback from dynamic to callable', [cite: 26]

        // DBEnum
        'c' => 'DBEnum', 'm' => 'enumValues', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $hasEmpty from dynamic to bool', [cite: 43]
        // 'c' => 'DBEnum', 'm' => 'formField', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $title from dynamic to string|null', [cite: 86]
        // 'c' => 'DBEnum', 'm' => 'formField', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $name from dynamic to string|null', [cite: 62]
        // 'c' => 'DBEnum', 'm' => 'formField', 'p' => 2, 't' => 'string|null', 'n' => 'Changed type of parameter $value from dynamic to string|null', [cite: 90]
        // 'c' => 'DBEnum', 'm' => 'formField', 'p' => 3, 't' => 'string|null', 'n' => 'Changed type of parameter $emptyString from dynamic to string|null', [cite: 35]
        'c' => 'DBEnum', 'm' => 'formField', 'p' => 4, 't' => 'bool', 'n' => 'Changed type of parameter $hasEmpty from dynamic to bool', [cite: 43]
        // 'c' => 'DBEnum', 'm' => 'setDefault', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $default from dynamic to string|null', [cite: 33]
        'c' => 'DBEnum', 'm' => 'setEnum', 'p' => 0, 't' => 'string|array', 'n' => 'Changed type of parameter $enum from dynamic to string|array', [cite: 35]

        // DBField
        'c' => 'DBField', 'm' => 'addToQuery', 'p' => 0, 't' => 'SQLSelect', 'n' => 'Changed type of parameter $query from dynamic to SQLSelect', [cite: 68]
        'c' => 'DBField', 'm' => 'create_field', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $spec from dynamic to string', [cite: 80]
        'c' => 'DBField', 'm' => 'create_field', 'p' => 1, 't' => 'mixed', 'n' => 'Changed type of parameter $value from dynamic to mixed', [cite: 91]
        // 'c' => 'DBField', 'm' => 'create_field', 'p' => 2, 't' => 'string|null', 'n' => 'Changed type of parameter $name from dynamic to string|null', [cite: 62]
        'c' => 'DBField', 'm' => 'create_field', 'p' => 3, 't' => 'mixed', 'n' => 'Changed type of parameter $args from dynamic to mixed', [cite: 23]
        // 'c' => 'DBField', 'm' => 'defaultSearchFilter', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $name from dynamic to string|null', [cite: 62]
        'c' => 'DBField', 'm' => 'prepValueForDB', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $value from dynamic to mixed', [cite: 91]
        // 'c' => 'DBField', 'm' => 'saveInto', 'p' => 0, 't' => 'ModelData', 'n' => 'Changed type of parameter $dataObject from dynamic to ModelData', [cite: 30]
        // 'c' => 'DBField', 'm' => 'scaffoldFormField', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $title from dynamic to string|null', [cite: 86]
        'c' => 'DBField', 'm' => 'scaffoldFormField', 'p' => 1, 't' => 'array', 'n' => 'Changed type of parameter $params from dynamic to array', [cite: 64]
        // 'c' => 'DBField', 'm' => 'scaffoldSearchField', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $title from dynamic to string|null', [cite: 86]
        'c' => 'DBField', 'm' => 'setDefaultValue', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $defaultValue from dynamic to mixed', [cite: 33]
        'c' => 'DBField', 'm' => 'setName', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $name from dynamic to string', [cite: 62]
        'c' => 'DBField', 'm' => 'setTable', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $tableName from dynamic to string', [cite: 83]
        'c' => 'DBField', 'm' => 'setValue', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $value from dynamic to mixed', [cite: 91]
        // 'c' => 'DBField', 'm' => 'setValue', 'p' => 1, 't' => 'null|array|ModelData', 'n' => 'Changed type of parameter $record from dynamic to null|array|ModelData', [cite: 68]
        'c' => 'DBField', 'm' => 'setValue', 'p' => 2, 't' => 'bool', 'n' => 'Changed type of parameter $markChanged from dynamic to bool', [cite: 51]
        'c' => 'DBField', 'm' => 'writeToManipulation', 'p' => 0, 't' => 'array', 'n' => 'Changed type of parameter $manipulation from dynamic to array', [cite: 50]

        // DBFile
        'c' => 'DBFile', 'm' => 'assertFilenameValid', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $filename from dynamic to string', [cite: 6]
        'c' => 'DBFile', 'm' => 'getSourceURL', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $grant from dynamic to bool', [cite: 7]
        'c' => 'DBFile', 'm' => 'isValidFilename', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $filename from dynamic to string', [cite: 6]
        'c' => 'DBFile', 'm' => 'setAllowedCategories', 'p' => 0, 't' => 'array|string', 'n' => 'Changed type of parameter $categories from dynamic to array|string', [cite: 5]
        'c' => 'DBFile', 'm' => 'setOriginal', 'p' => 0, 't' => 'AssetContainer', 'n' => 'Changed type of parameter $original from dynamic to AssetContainer', [cite: 11]
        // 'c' => 'DBFile', 'm' => 'validateFilename', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $filename from dynamic to string|null', [cite: 6]

        // DBHTMLText
        'c' => 'DBHTMLText', 'm' => 'setProcessShortcodes', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $process from dynamic to bool', [cite: 67]
        'c' => 'DBHTMLText', 'm' => 'setWhitelist', 'p' => 0, 't' => 'string|array', 'n' => 'Changed type of parameter $whitelist from dynamic to string|array', [cite: 93]
        'c' => 'DBHTMLText', 'm' => 'whitelistContent', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $value from dynamic to mixed', [cite: 91]

        // DBHTMLVarchar
        'c' => 'DBHTMLVarchar', 'm' => 'setProcessShortcodes', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $process from dynamic to bool', [cite: 67]

        // DBLocale
        'c' => 'DBLocale', 'm' => 'Nice', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $showNative from dynamic to bool', [cite: 80]

        // DBMoney
        'c' => 'DBMoney', 'm' => 'setAmount', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $amount from dynamic to mixed', [cite: 22]
        'c' => 'DBMoney', 'm' => 'setAmount', 'p' => 1, 't' => 'bool', 'n' => 'Changed type of parameter $markChanged from dynamic to bool', [cite: 51]
        // 'c' => 'DBMoney', 'm' => 'setCurrency', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $currency from dynamic to string|null', [cite: 29]
        'c' => 'DBMoney', 'm' => 'setCurrency', 'p' => 1, 't' => 'bool', 'n' => 'Changed type of parameter $markChanged from dynamic to bool', [cite: 51]
        'c' => 'DBMoney', 'm' => 'setLocale', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $locale from dynamic to string', [cite: 50]

        // DBPolymorphicForeignKey
        'c' => 'DBPolymorphicForeignKey', 'm' => 'setClassValue', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $value from dynamic to string', [cite: 92]
        'c' => 'DBPolymorphicForeignKey', 'm' => 'setClassValue', 'p' => 1, 't' => 'bool', 'n' => 'Changed type of parameter $markChanged from dynamic to bool', [cite: 51]
        'c' => 'DBPolymorphicForeignKey', 'm' => 'setIDValue', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $value from dynamic to int', [cite: 92]
        'c' => 'DBPolymorphicForeignKey', 'm' => 'setIDValue', 'p' => 1, 't' => 'bool', 'n' => 'Changed type of parameter $markChanged from dynamic to bool', [cite: 52]

        // DBPrimaryKey
        'c' => 'DBPrimaryKey', 'm' => 'setAutoIncrement', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $autoIncrement from dynamic to bool', [cite: 23]

        // DBString
        'c' => 'DBString', 'm' => 'LimitCharacters', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $limit from dynamic to int', [cite: 49]
        // 'c' => 'DBString', 'm' => 'LimitCharacters', 'p' => 1, 't' => 'string|false', 'n' => 'Changed type of parameter $add from dynamic to string|false', [cite: 21]
        'c' => 'DBString', 'm' => 'LimitCharactersToClosestWord', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $limit from dynamic to int', [cite: 49]
        // 'c' => 'DBString', 'm' => 'LimitCharactersToClosestWord', 'p' => 1, 't' => 'string|false', 'n' => 'Changed type of parameter $add from dynamic to string|false', [cite: 21]
        'c' => 'DBString', 'm' => 'LimitWordCount', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $numWords from dynamic to int', [cite: 64]
        // 'c' => 'DBString', 'm' => 'LimitWordCount', 'p' => 1, 't' => 'string|false', 'n' => 'Changed type of parameter $add from dynamic to string|false', [cite: 22]
        'c' => 'DBString', 'm' => 'setNullifyEmpty', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $value from dynamic to bool', [cite: 92]

        // DBText
        'c' => 'DBText', 'm' => 'ContextSummary', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $characters from dynamic to int', [cite: 27]
        // 'c' => 'DBText', 'm' => 'ContextSummary', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $keywords from dynamic to string|null', [cite: 48]
        'c' => 'DBText', 'm' => 'ContextSummary', 'p' => 2, 't' => 'bool', 'n' => 'Changed type of parameter $highlight from dynamic to bool', [cite: 43]
        // 'c' => 'DBText', 'm' => 'ContextSummary', 'p' => 3, 't' => 'string|false', 'n' => 'Changed type of parameter $prefix from dynamic to string|false', [cite: 67]
        // 'c' => 'DBText', 'm' => 'ContextSummary', 'p' => 4, 't' => 'string|false', 'n' => 'Changed type of parameter $suffix from dynamic to string|false', [cite: 81]
        'c' => 'DBText', 'm' => 'LimitSentences', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $maxSentences from dynamic to int', [cite: 52]
        'c' => 'DBText', 'm' => 'Summary', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $maxWords from dynamic to int', [cite: 52]
        // 'c' => 'DBText', 'm' => 'Summary', 'p' => 1, 't' => 'string|false', 'n' => 'Changed type of parameter $add from dynamic to string|false', [cite: 22]

        // DBTime
        'c' => 'DBTime', 'm' => 'Format', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $format from dynamic to string', [cite: 42]
        // 'c' => 'DBTime', 'm' => 'FormatFromSettings', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from dynamic to Member|null', [cite: 53]
        'c' => 'DBTime', 'm' => 'getFormatter', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $timeLength from dynamic to int', [cite: 85]
        'c' => 'DBTime', 'm' => 'parseTime', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $value from dynamic to mixed', [cite: 92]

        // DataList
        'c' => 'DataList', 'm' => 'columnUnique', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $colName from dynamic to string', [cite: 27]
        'c' => 'DataList', 'm' => 'dbObject', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $fieldName from dynamic to string', [cite: 37]

        // DataObject
        'c' => 'DataObject', 'm' => 'dbObject', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $fieldName from dynamic to string', [cite: 37]
        'c' => 'DataObject', 'm' => 'flushCache', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $persistent from dynamic to bool', [cite: 66]

        // DataObjectInterface
        'c' => 'DataObjectInterface', 'm' => '__get', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $fieldName from dynamic to string', [cite: 37]

        // DataObjectVersionFormFactory
        // 'c' => 'DataObjectVersionFormFactory', 'm' => 'getFormActions', 'p' => 0, 't' => 'RequestHandler|null', 'n' => 'Changed type of parameter $controller from RequestHandler to RequestHandler|null', [cite: 98]
        // 'c' => 'DataObjectVersionFormFactory', 'm' => 'getFormFields', 'p' => 0, 't' => 'RequestHandler|null', 'n' => 'Changed type of parameter $controller from RequestHandler to RequestHandler|null', [cite: 98]

        // DateField
        'c' => 'DateField', 'm' => 'internalToFrontend', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $date from dynamic to mixed', [cite: 32]
        'c' => 'DateField', 'm' => 'tidyInternal', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $date from dynamic to mixed', [cite: 32]

        // DatetimeField
        'c' => 'DatetimeField', 'm' => 'internalToFrontend', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $datetime from dynamic to mixed', [cite: 32]
        'c' => 'DatetimeField', 'm' => 'tidyInternal', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $datetime from dynamic to mixed', [cite: 33]

        // Debug
        // 'c' => 'Debug', 'm' => 'create_debug_view', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 69]
        // 'c' => 'Debug', 'm' => 'dump', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 70]
        // 'c' => 'Debug', 'm' => 'endshow', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 70]
        // 'c' => 'Debug', 'm' => 'message', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 70]
        // 'c' => 'Debug', 'm' => 'show', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 70]
        // 'c' => 'Debug', 'm' => 'supportsHTML', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 71]
        // 'c' => 'Debug', 'm' => 'text', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 71]

        // DefaultCacheFactory
        // 'c' => 'DefaultCacheFactory', 'm' => '__construct', 'p' => 0, 't' => 'Psr\\Log\\LoggerInterface|null', 'n' => 'Changed type of parameter $logger from Psr\\Log\\LoggerInterface to Psr\\Log\\LoggerInterface|null', [cite: 50]

        // DefaultPermissionChecker
        // 'c' => 'DefaultPermissionChecker', 'm' => 'canCreate', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null', [cite: 54]
        // 'c' => 'DefaultPermissionChecker', 'm' => 'canDelete', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null', [cite: 54]
        // 'c' => 'DefaultPermissionChecker', 'm' => 'canEdit', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null', [cite: 54]
        // 'c' => 'DefaultPermissionChecker', 'm' => 'canView', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null', [cite: 54]

        // Director
        // 'c' => 'Director', 'm' => 'absoluteBaseURLWithAuth', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 71]
        // 'c' => 'Director', 'm' => 'currentRequest', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 71]
        // 'c' => 'Director', 'm' => 'forceSSL', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 72]
        // 'c' => 'Director', 'm' => 'forceWWW', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 72]
        // 'c' => 'Director', 'm' => 'host', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 72]
        // 'c' => 'Director', 'm' => 'hostName', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 72]
        // 'c' => 'Director', 'm' => 'is_ajax', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 73]
        // 'c' => 'Director', 'm' => 'is_https', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 73]
        // 'c' => 'Director', 'm' => 'port', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 73]
        // 'c' => 'Director', 'm' => 'protocol', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 73]
        // 'c' => 'Director', 'm' => 'protocolAndHost', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 74]

        // EagerLoadedList
        'c' => 'EagerLoadedList', 'm' => 'columnUnique', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $colName from dynamic to string', [cite: 28]

        // EditableFileField
        // 'c' => 'EditableFileField', 'm' => 'getFolderPermissionString', 'p' => 0, 't' => 'Folder|null', 'n' => 'Changed type of parameter $folder from Folder to Folder|null', [cite: 95]

        // Email
        // 'c' => 'Email', 'm' => 'setBody', 'p' => 0, 't' => 'Symfony\\Component\\Mime\\Part\\AbstractPart|string|null', 'n' => 'Changed type of parameter $body from Symfony\\Component\\Mime\\Part\\AbstractPart|string to Symfony\\Component\\Mime\\Part\\AbstractPart|string|null', [cite: 24]
        // 'c' => 'Email', 'm' => 'setData', 'p' => 0, 't' => 'array|ModelData', 'n' => 'Changed type of parameter $data from array|SilverStripe\\View\\ViewableData to array|ModelData', [cite: 30]

        // Factory
        'c' => 'Factory', 'm' => 'create', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $service from dynamic to string', [cite: 79]

        // FieldFilterInterface
        // 'c' => 'FieldFilterInterface', 'm' => 'apply', 'p' => 0, 't' => 'SS_List', 'n' => 'Changed type of parameter $list from SilverStripe\\ORM\\Filterable to SS_List', [cite: 93]

        // FieldList
        'c' => 'FieldList', 'm' => 'addFieldToTab', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $tabName from dynamic to string', [cite: 81]
        'c' => 'FieldList', 'm' => 'addFieldToTab', 'p' => 1, 't' => 'FormField', 'n' => 'Changed type of parameter $field from dynamic to FormField', [cite: 40]
        // 'c' => 'FieldList', 'm' => 'addFieldToTab', 'p' => 2, 't' => 'string|null', 'n' => 'Changed type of parameter $insertBefore from dynamic to string|null', [cite: 47]
        'c' => 'FieldList', 'm' => 'addFieldsToTab', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $tabName from dynamic to string', [cite: 82]
        'c' => 'FieldList', 'm' => 'addFieldsToTab', 'p' => 1, 't' => 'array', 'n' => 'Changed type of parameter $fields from dynamic to array', [cite: 41]
        // 'c' => 'FieldList', 'm' => 'addFieldsToTab', 'p' => 2, 't' => 'string|null', 'n' => 'Changed type of parameter $insertBefore from dynamic to string|null', [cite: 47]
        'c' => 'FieldList', 'm' => 'changeFieldOrder', 'p' => 0, 't' => 'array|string', 'n' => 'Changed type of parameter $fieldNames from dynamic to array|string', [cite: 39]
        'c' => 'FieldList', 'm' => 'dataFieldByName', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $name from dynamic to string', [cite: 63]
        'c' => 'FieldList', 'm' => 'fieldByName', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $name from dynamic to string', [cite: 63]
        'c' => 'FieldList', 'm' => 'fieldNameError', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $functionName from dynamic to string', [cite: 42]
        'c' => 'FieldList', 'm' => 'fieldPosition', 'p' => 0, 't' => 'string|FormField', 'n' => 'Changed type of parameter $field from dynamic to string|FormField', [cite: 40]
        'c' => 'FieldList', 'm' => 'findOrMakeTab', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $tabName from dynamic to string', [cite: 82]
        // 'c' => 'FieldList', 'm' => 'findOrMakeTab', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $title from dynamic to string|null', [cite: 86]
        'c' => 'FieldList', 'm' => 'findTab', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $tabName from dynamic to string', [cite: 82]
        'c' => 'FieldList', 'm' => 'makeFieldReadonly', 'p' => 0, 't' => 'string|array|FormField', 'n' => 'Changed type of parameter $field from dynamic to string|array|FormField', [cite: 40]
        'c' => 'FieldList', 'm' => 'removeByName', 'p' => 0, 't' => 'string|array', 'n' => 'Changed type of parameter $fieldName from dynamic to string|array', [cite: 38]
        'c' => 'FieldList', 'm' => 'removeByName', 'p' => 1, 't' => 'bool', 'n' => 'Changed type of parameter $dataFieldOnly from dynamic to bool', [cite: 29]
        'c' => 'FieldList', 'm' => 'removeFieldFromTab', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $tabName from dynamic to string', [cite: 82]
        'c' => 'FieldList', 'm' => 'removeFieldFromTab', 'p' => 1, 't' => 'string', 'n' => 'Changed type of parameter $fieldName from dynamic to string', [cite: 38]
        'c' => 'FieldList', 'm' => 'removeFieldsFromTab', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $tabName from dynamic to string', [cite: 83]
        'c' => 'FieldList', 'm' => 'removeFieldsFromTab', 'p' => 1, 't' => 'array', 'n' => 'Changed type of parameter $fields from dynamic to array', [cite: 41]
        'c' => 'FieldList', 'm' => 'renameField', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $fieldName from dynamic to string', [cite: 38]
        'c' => 'FieldList', 'm' => 'renameField', 'p' => 1, 't' => 'string', 'n' => 'Changed type of parameter $newFieldTitle from dynamic to string', [cite: 63]
        'c' => 'FieldList', 'm' => 'replaceField', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $fieldName from dynamic to string', [cite: 38]
        'c' => 'FieldList', 'm' => 'replaceField', 'p' => 1, 't' => 'FormField', 'n' => 'Changed type of parameter $newField from dynamic to FormField', [cite: 63]
        'c' => 'FieldList', 'm' => 'replaceField', 'p' => 2, 't' => 'bool', 'n' => 'Changed type of parameter $dataFieldOnly from dynamic to bool', [cite: 30]
        // 'c' => 'FieldList', 'm' => 'setContainerField', 'p' => 0, 't' => 'CompositeField|null', 'n' => 'Changed type of parameter $field from dynamic to CompositeField|null', [cite: 40]
        'c' => 'FieldList', 'm' => 'setForm', 'p' => 0, 't' => 'Form', 'n' => 'Changed type of parameter $form from dynamic to Form', [cite: 41]
        'c' => 'FieldList', 'm' => 'setValues', 'p' => 0, 't' => 'array', 'n' => 'Changed type of parameter $data from dynamic to array', [cite: 31]
        'c' => 'FieldList', 'm' => 'transform', 'p' => 0, 't' => 'FormTransformation', 'n' => 'Changed type of parameter $trans from dynamic to FormTransformation', [cite: 87]

        // FileLinkTracking
        // 'c' => 'FileLinkTracking', 'm' => 'setFileParser', 'p' => 0, 't' => 'FileLinkTrackingParser|null', 'n' => 'Changed type of parameter $parser from FileLinkTrackingParser to FileLinkTrackingParser|null', [cite: 11]

        // FileSearchFormFactory
        // 'c' => 'FileSearchFormFactory', 'm' => 'getFormFields', 'p' => 0, 't' => 'RequestHandler|null', 'n' => 'Changed type of parameter $controller from RequestHandler to RequestHandler|null', [cite: 4]

        // Filesystem
        // 'c' => 'Filesystem', 'm' => '__construct', 'p' => 0, 't' => 'League\\Flysystem\\PathNormalizer|null', 'n' => 'Changed type of parameter $pathNormalizer from League\\Flysystem\\PathNormalizer to League\\Flysystem\\PathNormalizer|null', [cite: 12]

        // FluentExtension
        // 'c' => 'FluentExtension', 'm' => 'getDataQueryLocale', 'p' => 0, 't' => 'DataQuery|null', 'n' => 'Changed type of parameter $dataQuery from DataQuery to DataQuery|null', [cite: 102]

        // FluentFilteredExtension
        // 'c' => 'FluentFilteredExtension', 'm' => 'getDataQueryLocale', 'p' => 0, 't' => 'DataQuery|null', 'n' => 'Changed type of parameter $dataQuery from DataQuery to DataQuery|null', [cite: 102]

        // FluentIsolatedExtension
        // 'c' => 'FluentIsolatedExtension', 'm' => 'getDataQueryLocale', 'p' => 0, 't' => 'DataQuery|null', 'n' => 'Changed type of parameter $dataQuery from DataQuery to DataQuery|null', [cite: 102]

        // FluentSiteTreeExtension
        'c' => 'FluentSiteTreeExtension', 'm' => 'updateStatusFlags', 'p' => 0, 't' => 'array', 'n' => 'Changed type of parameter $flags from dynamic to array', [cite: 103]

        // Form
        'c' => 'Form', 'm' => 'loadDataFrom', 'p' => 0, 't' => 'object|array', 'n' => 'Changed type of parameter $data from dynamic to object|array', [cite: 31]
        'c' => 'Form', 'm' => 'loadDataFrom', 'p' => 1, 't' => 'int', 'n' => 'Changed type of parameter $mergeStrategy from dynamic to int', [cite: 58]
        'c' => 'Form', 'm' => 'loadDataFrom', 'p' => 2, 't' => 'array', 'n' => 'Changed type of parameter $fieldList from dynamic to array', [cite: 37]
        'c' => 'Form', 'm' => 'sessionError', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $message from dynamic to string', [cite: 58]
        'c' => 'Form', 'm' => 'sessionError', 'p' => 1, 't' => 'string', 'n' => 'Changed type of parameter $type from dynamic to string', [cite: 88]
        'c' => 'Form', 'm' => 'sessionError', 'p' => 2, 't' => 'string', 'n' => 'Changed type of parameter $cast from dynamic to string', [cite: 26]
        'c' => 'Form', 'm' => 'sessionFieldError', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $message from dynamic to string', [cite: 58]
        'c' => 'Form', 'm' => 'sessionFieldError', 'p' => 1, 't' => 'string', 'n' => 'Changed type of parameter $fieldName from dynamic to string', [cite: 39]
        'c' => 'Form', 'm' => 'sessionFieldError', 'p' => 2, 't' => 'string', 'n' => 'Changed type of parameter $type from dynamic to string', [cite: 88]
        'c' => 'Form', 'm' => 'sessionFieldError', 'p' => 3, 't' => 'string', 'n' => 'Changed type of parameter $cast from dynamic to string', [cite: 26]
        'c' => 'Form', 'm' => 'sessionMessage', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $message from dynamic to string', [cite: 58]
        'c' => 'Form', 'm' => 'sessionMessage', 'p' => 1, 't' => 'string', 'n' => 'Changed type of parameter $type from dynamic to string', [cite: 88]
        'c' => 'Form', 'm' => 'sessionMessage', 'p' => 2, 't' => 'string', 'n' => 'Changed type of parameter $cast from dynamic to string', [cite: 26]
        // 'c' => 'Form', 'm' => 'setController', 'p' => 0, 't' => 'RequestHandler|null', 'n' => 'Changed type of parameter $controller from RequestHandler to RequestHandler|null', [cite: 28]
        // 'c' => 'Form', 'm' => 'setValidator', 'p' => 0, 't' => 'Validator|null', 'n' => 'Changed type of parameter $validator from SilverStripe\\Forms\\Validator to Validator|null', [cite: 89]

        // FormFactory
        // 'c' => 'FormFactory', 'm' => 'getForm', 'p' => 0, 't' => 'RequestHandler|null', 'n' => 'Changed type of parameter $controller from RequestHandler to RequestHandler|null', [cite: 28]

        // FormSchema
        // 'c' => 'FormSchema', 'm' => 'getMultipartSchema', 'p' => 0, 't' => 'ValidationResult|null', 'n' => 'Changed type of parameter $result from SilverStripe\\ORM\\ValidationResult to ValidationResult|null', [cite: 77]
        // 'c' => 'FormSchema', 'm' => 'getMultipartSchema', 'p' => 1, 't' => 'Form|null', 'n' => 'Changed type of parameter $form from Form to Form|null', [cite: 42]

        // GridFieldNestedForm
        // 'c' => 'GridFieldNestedForm', 'm' => 'handleNestedItem', 'p' => 0, 't' => 'ModelData|null', 'n' => 'Changed type of parameter $record from SilverStripe\\View\\ViewableData|null to ModelData|null', [cite: 101]
        // 'c' => 'GridFieldNestedForm', 'm' => 'toggleNestedItem', 'p' => 0, 't' => 'ModelData|null', 'n' => 'Changed type of parameter $record from SilverStripe\\View\\ViewableData|null to ModelData|null', [cite: 101]

        // GridFieldSiteTreeAddNewButton
        // 'c' => 'GridFieldSiteTreeAddNewButton', 'm' => 'getAllowedChildren', 'p' => 0, 't' => 'SiteTree|null', 'n' => 'Changed type of parameter $parent from SiteTree to SiteTree|null', [cite: 94]

        // HTMLEditorConfig
        'c' => 'HTMLEditorConfig', 'm' => 'getOption', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $key from dynamic to string', [cite: 48]
        'c' => 'HTMLEditorConfig', 'm' => 'setOption', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $key from dynamic to string', [cite: 48]
        'c' => 'HTMLEditorConfig', 'm' => 'setOption', 'p' => 1, 't' => 'mixed', 'n' => 'Changed type of parameter $value from dynamic to mixed', [cite: 93]
        'c' => 'HTMLEditorConfig', 'm' => 'setOptions', 'p' => 0, 't' => 'array', 'n' => 'Changed type of parameter $options from dynamic to array', [cite: 64]
        'c' => 'HTMLEditorConfig', 'm' => 'setThemes', 'p' => 0, 't' => 'array', 'n' => 'Changed type of parameter $themes from dynamic to array', [cite: 83]
        'c' => 'HTMLEditorConfig', 'm' => 'set_active_identifier', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $identifier from dynamic to string', [cite: 44]
        'c' => 'HTMLEditorConfig', 'm' => 'set_config', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $identifier from dynamic to string', [cite: 45]
        // 'c' => 'HTMLEditorConfig', 'm' => 'set_config', 'p' => 1, 't' => 'HTMLEditorConfig|null', 'n' => 'Changed type of parameter $config from HTMLEditorConfig to HTMLEditorConfig|null', [cite: 28]

        // HTTP
        'c' => 'HTTP', 'm' => 'urlRewriter', 'p' => 0, 't' => 'callable', 'n' => 'Changed type of parameter $code from dynamic to callable', [cite: 27]

        // HistoryViewerController
        // 'c' => 'HistoryViewerController', 'm' => 'compareForm', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 99]
        // 'c' => 'HistoryViewerController', 'm' => 'versionForm', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 99]

        // IdentityStore
        // 'c' => 'IdentityStore', 'm' => 'logIn', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 74]
        // 'c' => 'IdentityStore', 'm' => 'logOut', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 74]

        // Image_Backend
        // 'c' => 'Image_Backend', 'm' => '__construct', 'p' => 0, 't' => 'AssetContainer|null', 'n' => 'Changed type of parameter $assetContainer from AssetContainer to AssetContainer|null', [cite: 4]
        'c' => 'Image_Backend', 'm' => 'crop', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $top from dynamic to int', [cite: 14]
        'c' => 'Image_Backend', 'm' => 'crop', 'p' => 1, 't' => 'int', 'n' => 'Changed type of parameter $left from dynamic to int', [cite: 10]
        'c' => 'Image_Backend', 'm' => 'crop', 'p' => 2, 't' => 'int', 'n' => 'Changed type of parameter $width from dynamic to int', [cite: 17]
        'c' => 'Image_Backend', 'm' => 'crop', 'p' => 3, 't' => 'int', 'n' => 'Changed type of parameter $height from dynamic to int', [cite: 9]
        'c' => 'Image_Backend', 'm' => 'croppedResize', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $width from dynamic to int', [cite: 17]
        'c' => 'Image_Backend', 'm' => 'croppedResize', 'p' => 1, 't' => 'int', 'n' => 'Changed type of parameter $height from dynamic to int', [cite: 9]
        'c' => 'Image_Backend', 'm' => 'loadFrom', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $path from dynamic to string', [cite: 12]
        'c' => 'Image_Backend', 'm' => 'paddedResize', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $width from dynamic to int', [cite: 17]
        'c' => 'Image_Backend', 'm' => 'paddedResize', 'p' => 1, 't' => 'int', 'n' => 'Changed type of parameter $height from dynamic to int', [cite: 9]
        'c' => 'Image_Backend', 'm' => 'paddedResize', 'p' => 2, 't' => 'string', 'n' => 'Changed type of parameter $backgroundColor from dynamic to string', [cite: 5]
        'c' => 'Image_Backend', 'm' => 'paddedResize', 'p' => 3, 't' => 'int', 'n' => 'Changed type of parameter $transparencyPercent from dynamic to int', [cite: 14]
        'c' => 'Image_Backend', 'm' => 'resize', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $width from dynamic to int', [cite: 17]
        'c' => 'Image_Backend', 'm' => 'resize', 'p' => 1, 't' => 'int', 'n' => 'Changed type of parameter $height from dynamic to int', [cite: 10]
        'c' => 'Image_Backend', 'm' => 'resizeByHeight', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $height from dynamic to int', [cite: 10]
        'c' => 'Image_Backend', 'm' => 'resizeByWidth', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $width from dynamic to int', [cite: 18]
        'c' => 'Image_Backend', 'm' => 'resizeRatio', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $width from dynamic to int', [cite: 18]
        'c' => 'Image_Backend', 'm' => 'resizeRatio', 'p' => 1, 't' => 'int', 'n' => 'Changed type of parameter $height from dynamic to int', [cite: 10]
        'c' => 'Image_Backend', 'm' => 'resizeRatio', 'p' => 2, 't' => 'bool', 'n' => 'Changed type of parameter $useAsMinimum from dynamic to bool', [cite: 14]
        'c' => 'Image_Backend', 'm' => 'setQuality', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $quality from dynamic to int', [cite: 13]
        'c' => 'Image_Backend', 'm' => 'writeTo', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $path from dynamic to string', [cite: 12]
        'c' => 'Image_Backend', 'm' => 'writeToStore', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $filename from dynamic to string', [cite: 6]
        // 'c' => 'Image_Backend', 'm' => 'writeToStore', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $hash from dynamic to string|null', [cite: 7]
        // 'c' => 'Image_Backend', 'm' => 'writeToStore', 'p' => 2, 't' => 'string|null', 'n' => 'Changed type of parameter $variant from dynamic to string|null', [cite: 14]
        'c' => 'Image_Backend', 'm' => 'writeToStore', 'p' => 3, 't' => 'array', 'n' => 'Changed type of parameter $config from dynamic to array', [cite: 5]

        // InheritedPermissions
        // 'c' => 'InheritedPermissions', 'm' => '__construct', 'p' => 0, 't' => 'Psr\\SimpleCache\\CacheInterface|null', 'n' => 'Changed type of parameter $cache from Psr\\SimpleCache\\CacheInterface to Psr\\SimpleCache\\CacheInterface|null', [cite: 25]
        // 'c' => 'InheritedPermissions', 'm' => 'batchPermissionCheck', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null', [cite: 55]
        // 'c' => 'InheritedPermissions', 'm' => 'batchPermissionCheckForStage', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null', [cite: 55]
        // 'c' => 'InheritedPermissions', 'm' => 'checkDefaultPermissions', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null', [cite: 55]

        // InterventionBackend
        'c' => 'InterventionBackend', 'm' => 'getDimensionCacheKey', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $hash from dynamic to string', [cite: 7]
        // 'c' => 'InterventionBackend', 'm' => 'getDimensionCacheKey', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $variant from dynamic to string|null', [cite: 15]
        'c' => 'InterventionBackend', 'm' => 'getErrorCacheKey', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $hash from dynamic to string', [cite: 7]
        // 'c' => 'InterventionBackend', 'm' => 'getErrorCacheKey', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $variant from dynamic to string|null', [cite: 15]
        'c' => 'InterventionBackend', 'm' => 'getResourceDimensions', 'p' => 0, 't' => 'Intervention\\Image\\Interfaces\\ImageInterface', 'n' => 'Changed type of parameter $resource from Intervention\\Image\\Image to Intervention\\Image\\Interfaces\\ImageInterface', [cite: 13]
        'c' => 'InterventionBackend', 'm' => 'hasFailed', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $hash from dynamic to string', [cite: 8]
        // 'c' => 'InterventionBackend', 'm' => 'hasFailed', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $variant from dynamic to string|null', [cite: 15]
        'c' => 'InterventionBackend', 'm' => 'isStreamReadable', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $stream from dynamic to mixed', [cite: 13]
        'c' => 'InterventionBackend', 'm' => 'markFailed', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $hash from dynamic to string', [cite: 8]
        // 'c' => 'InterventionBackend', 'm' => 'markFailed', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $variant from dynamic to string|null', [cite: 15]
        'c' => 'InterventionBackend', 'm' => 'markFailed', 'p' => 2, 't' => 'string', 'n' => 'Changed type of parameter $reason from dynamic to string', [cite: 13]
        'c' => 'InterventionBackend', 'm' => 'markSuccess', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $hash from dynamic to string', [cite: 8]
        // 'c' => 'InterventionBackend', 'm' => 'markSuccess', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $variant from dynamic to string|null', [cite: 16]
        // 'c' => 'InterventionBackend', 'm' => 'setAssetContainer', 'p' => 0, 't' => 'AssetContainer|null', 'n' => 'Changed type of parameter $assetContainer from dynamic to AssetContainer|null', [cite: 14]
        'c' => 'InterventionBackend', 'm' => 'setCache', 'p' => 0, 't' => 'Psr\\SimpleCache\\CacheInterface', 'n' => 'Changed type of parameter $cache from dynamic to Psr\\SimpleCache\\CacheInterface', [cite: 5]
        'c' => 'InterventionBackend', 'm' => 'setImageManager', 'p' => 0, 't' => 'Intervention\\Image\\ImageManager', 'n' => 'Changed type of parameter $manager from dynamic to Intervention\\Image\\ImageManager', [cite: 11]
        'c' => 'InterventionBackend', 'm' => 'setTempPath', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $path from dynamic to string', [cite: 12]
        'c' => 'InterventionBackend', 'm' => 'warmCache', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $hash from dynamic to string', [cite: 8]
        // 'c' => 'InterventionBackend', 'm' => 'warmCache', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $variant from dynamic to string|null', [cite: 16]

        // LeftAndMain
        'c' => 'LeftAndMain', 'm' => 'jsonError', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $errorMessage from dynamic to string', [cite: 1]
        'c' => 'LeftAndMain', 'm' => 'jsonError', 'p' => 1, 't' => 'int', 'n' => 'Changed type of parameter $errorCode from dynamic to int', [cite: 1]
        'c' => 'LeftAndMain', 'm' => 'getSchemaResponse', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $schemaID from dynamic to string', [cite: 2]
        // 'c' => 'LeftAndMain', 'm' => 'getSchemaResponse', 'p' => 1, 't' => 'ValidationResult|null', 'n' => 'Changed type of parameter $errors from SilverStripe\\ORM\\ValidationResult to ValidationResult|null', [cite: 1]
        // 'c' => 'LeftAndMain', 'm' => 'getSchemaResponse', 'p' => 2, 't' => 'Form|null', 'n' => 'Changed type of parameter $form from dynamic to Form|null', [cite: 2]
        'c' => 'LeftAndMain', 'm' => 'getSchemaResponse', 'p' => 3, 't' => 'array', 'n' => 'Changed type of parameter $extraData from dynamic to array', [cite: 2]
        'c' => 'LeftAndMain', 'm' => 'getTemplatesWithSuffix', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $suffix from dynamic to string', [cite: 2]

        // LeftAndMainSubsites
        // 'c' => 'LeftAndMainSubsites', 'm' => 'alternateAccessCheck', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null', [cite: 94]
        // 'c' => 'LeftAndMainSubsites', 'm' => 'canAccess', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null', [cite: 95]

        // LocalDateTime
        'c' => 'LocalDateTime', 'm' => 'setLocalValue', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $value from dynamic to string', [cite: 103]
        // 'c' => 'LocalDateTime', 'm' => 'setLocalValue', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $timezone from dynamic to string|null', [cite: 103]

        // LocalFilesystemAdapter
        // 'c' => 'LocalFilesystemAdapter', 'm' => '__construct', 'p' => 0, 't' => 'League\\MimeTypeDetection\\MimeTypeDetector|null', 'n' => 'Changed type of parameter $mimeTypeDetector from League\\MimeTypeDetection\\MimeTypeDetector to League\\MimeTypeDetection\\MimeTypeDetector|null', [cite: 11]
        // 'c' => 'LocalFilesystemAdapter', 'm' => '__construct', 'p' => 1, 't' => 'League\\Flysystem\\UnixVisibility\\VisibilityConverter|null', 'n' => 'Changed type of parameter $visibility from League\\Flysystem\\UnixVisibility\\VisibilityConverter to League\\Flysystem\\UnixVisibility\\VisibilityConverter|null', [cite: 16]

        // LoginHandler
        // 'c' => 'LoginHandler', 'm' => 'checkLogin', 'p' => 0, 't' => 'ValidationResult|null', 'n' => 'Changed type of parameter $result from SilverStripe\\ORM\\ValidationResult to ValidationResult|null', [cite: 77]

        // Member
        // 'c' => 'Member', 'm' => 'afterMemberLoggedOut', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 74]
        // 'c' => 'Member', 'm' => 'beforeMemberLoggedOut', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 75]
        // 'c' => 'Member', 'm' => 'set_password_validator', 'p' => 0, 't' => 'PasswordValidator|null', 'n' => 'Changed type of parameter $validator from SilverStripe\\Security\\PasswordValidator to PasswordValidator|null', [cite: 89]
        // 'c' => 'Member', 'm' => 'validateCanLogin', 'p' => 0, 't' => 'ValidationResult|null', 'n' => 'Changed type of parameter $result from SilverStripe\\ORM\\ValidationResult to ValidationResult|null', [cite: 77]

        // MemberAuthenticator
        // 'c' => 'MemberAuthenticator', 'm' => 'authenticateMember', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null', [cite: 55]
        // 'c' => 'MemberAuthenticator', 'm' => 'authenticateMember', 'p' => 1, 't' => 'ValidationResult|null', 'n' => 'Changed type of parameter $result from SilverStripe\\ORM\\ValidationResult to ValidationResult|null', [cite: 77]

        // MemcachedCacheFactory
        // 'c' => 'MemcachedCacheFactory', 'm' => '__construct', 'p' => 0, 't' => 'Psr\\Log\\LoggerInterface|null', 'n' => 'Changed type of parameter $memcachedClient from Memcached to Psr\\Log\\LoggerInterface|null', [cite: 57]

        // ModuleManifest
        // 'c' => 'ModuleManifest', 'm' => '__construct', 'p' => 0, 't' => 'CacheFactory|null', 'n' => 'Changed type of parameter $cacheFactory from CacheFactory to CacheFactory|null', [cite: 25]

        // NotifyUsersWorkflowAction
        // 'c' => 'NotifyUsersWorkflowAction', 'm' => 'getMemberFields', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null', [cite: 100]

        // NumericField
        'c' => 'NumericField', 'm' => 'cast', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $value from dynamic to mixed', [cite: 93]

        // PermissionChecker
        // 'c' => 'PermissionChecker', 'm' => 'canDelete', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null', [cite: 56]
        // 'c' => 'PermissionChecker', 'm' => 'canDeleteMultiple', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null', [cite: 56]
        // 'c' => 'PermissionChecker', 'm' => 'canEdit', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null', [cite: 56]
        // 'c' => 'PermissionChecker', 'm' => 'canEditMultiple', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null', [cite: 56]
        // 'c' => 'PermissionChecker', 'm' => 'canView', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null', [cite: 57]
        // 'c' => 'PermissionChecker', 'm' => 'canViewMultiple', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null', [cite: 57]

        // PjaxResponseNegotiator
        // 'c' => 'PjaxResponseNegotiator', 'm' => '__construct', 'p' => 0, 't' => 'HTTPResponse|null', 'n' => 'Changed type of parameter $response from HTTPResponse to HTTPResponse|null', [cite: 76]

        // QueuedJobService
        // 'c' => 'QueuedJobService', 'm' => 'setRunAsUser', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $originalUser from Member to Member|null', [cite: 101]
        // 'c' => 'QueuedJobService', 'm' => 'unsetRunAsUser', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $runAsUser from Member to Member|null', [cite: 102]
        // 'c' => 'QueuedJobService', 'm' => 'unsetRunAsUser', 'p' => 1, 't' => 'Member|null', 'n' => 'Changed type of parameter $originalUser from Member to Member|null', [cite: 101]

        // RealMeService
        // 'c' => 'RealMeService', 'm' => 'getAuth', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 94]

        // Relation
        'c' => 'Relation', 'm' => 'dbObject', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $fieldName from dynamic to string', [cite: 39]

        // SSListContains
        'c' => 'SSListContains', 'm' => 'checkIfItemEvaluatesRemainingMatches', 'p' => 0, 't' => 'ModelData', 'n' => 'Changed type of parameter $item from SilverStripe\\View\\ViewableData to ModelData', [cite: 47]

        // SSViewer
        'c' => 'SSViewer', 'm' => '__construct', 'p' => 0, 't' => 'string|array', 'n' => 'Changed type of parameter $templates from dynamic to string|array', [cite: 83]
        // 'c' => 'SSViewer', 'm' => '__construct', 'p' => 1, 't' => 'TemplateEngine|null', 'n' => 'Changed type of parameter $parser from SilverStripe\\View\\TemplateParser to TemplateEngine|null', [cite: 65]
        'c' => 'SSViewer', 'm' => 'add_themes', 'p' => 0, 't' => 'array', 'n' => 'Changed type of parameter $themes from dynamic to array', [cite: 84]
        // 'c' => 'SSViewer', 'm' => 'get_templates_by_class', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $baseClass from dynamic to string|null', [cite: 24]
        'c' => 'SSViewer', 'm' => 'get_templates_by_class', 'p' => 1, 't' => 'string', 'n' => 'Changed type of parameter $suffix from dynamic to string', [cite: 81]
        'c' => 'SSViewer', 'm' => 'get_templates_by_class', 'p' => 2, 't' => 'string|object', 'n' => 'Changed type of parameter $classOrObject from dynamic to string|object', [cite: 27]
        'c' => 'SSViewer', 'm' => 'includeRequirements', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $incl from dynamic to bool', [cite: 45]
        'c' => 'SSViewer', 'm' => 'process', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $item from dynamic to mixed', [cite: 47]
        'c' => 'SSViewer', 'm' => 'process', 'p' => 1, 't' => 'array', 'n' => 'Changed type of parameter $arguments from dynamic to array', [cite: 23]
        // 'c' => 'SSViewer', 'm' => 'setRewriteHashLinks', 'p' => 0, 't' => 'null|bool|string', 'n' => 'Changed type of parameter $rewrite from dynamic to null|bool|string', [cite: 78]
        // 'c' => 'SSViewer', 'm' => 'setRewriteHashLinksDefault', 'p' => 0, 't' => 'null|bool|string', 'n' => 'Changed type of parameter $rewrite from dynamic to null|bool|string', [cite: 78]
        'c' => 'SSViewer', 'm' => 'set_themes', 'p' => 0, 't' => 'array', 'n' => 'Changed type of parameter $themes from dynamic to array', [cite: 84]

        // SearchContext
        // 'c' => 'SearchContext', 'm' => 'getQuery', 'p' => 0, 't' => 'int|array|null', 'n' => 'Changed type of parameter $limit from dynamic to int|array|null', [cite: 49]

        // Session
        // 'c' => 'Session', 'm' => 'destroy', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 75]

        // SiteTree
        // 'c' => 'SiteTree', 'm' => 'canAddChildren', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from dynamic to Member|null', [cite: 20]
        'c' => 'SiteTree', 'm' => 'getStatusFlags', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $cached from dynamic to bool', [cite: 18]

        // SiteTreeLinkTracking
        // 'c' => 'SiteTreeLinkTracking', 'm' => 'setParser', 'p' => 0, 't' => 'SiteTreeLinkTracking_Parser|null', 'n' => 'Changed type of parameter $parser from SiteTreeLinkTracking_Parser to SiteTreeLinkTracking_Parser|null', [cite: 20]

        // TestMailer
        // 'c' => 'TestMailer', 'm' => 'send', 'p' => 0, 't' => 'Symfony\\Component\\Mailer\\Envelope|null', 'n' => 'Changed type of parameter $envelope from Symfony\\Component\\Mailer\\Envelope to Symfony\\Component\\Mailer\\Envelope|null', [cite: 36]

        // ThemeManifest
        // 'c' => 'ThemeManifest', 'm' => '__construct', 'p' => 0, 't' => 'CacheFactory|null', 'n' => 'Changed type of parameter $cacheFactory from CacheFactory to CacheFactory|null', [cite: 25]

        // ThumbnailGenerator
        // 'c' => 'ThumbnailGenerator', 'm' => 'generateLink', 'p' => 0, 't' => 'AssetContainer|null', 'n' => 'Changed type of parameter $thumbnail from AssetContainer to AssetContainer|null', [cite: 4]

        // TimeField
        'c' => 'TimeField', 'm' => 'internalToFrontend', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $time from dynamic to mixed', [cite: 85]
        'c' => 'TimeField', 'm' => 'tidyInternal', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $time from dynamic to mixed', [cite: 85]

        // UserDefinedFormController
        // 'c' => 'UserDefinedFormController', 'm' => 'index', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null', [cite: 95]

        // VerifyHandler
        // 'c' => 'VerifyHandler', 'm' => 'getCredentialRequestOptions', 'p' => 0, 't' => 'RegisteredMethod|null', 'n' => 'Changed type of parameter $registeredMethod from RegisteredMethod to RegisteredMethod|null', [cite: 99]

        // Versioned
        'c' => 'Versioned', 'm' => 'augmentLoadLazyFields', 'p' => 0, 't' => 'DataQuery|null', 'n' => 'Changed type of parameter $dataQuery from DataQuery to DataQuery|null', [cite: 96]
        'c' => 'Versioned', 'm' => 'get_by_stage', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $class from dynamic to string', [cite: 96]
        'c' => 'Versioned', 'm' => 'get_by_stage', 'p' => 1, 't' => 'string', 'n' => 'Changed type of parameter $stage from dynamic to string', [cite: 98]
        'c' => 'Versioned', 'm' => 'get_by_stage', 'p' => 2, 't' => 'string|array', 'n' => 'Changed type of parameter $filter from dynamic to string|array', [cite: 97]
        // 'c' => 'Versioned', 'm' => 'get_by_stage', 'p' => 3, 't' => 'string|array|null', 'n' => 'Changed type of parameter $sort from dynamic to string|array|null', [cite: 97]
        'c' => 'Versioned', 'm' => 'get_by_stage', 'p' => 4, 't' => 'string', 'n' => 'Changed type of parameter $containerClass from dynamic to string', [cite: 96]
        // 'c' => 'Versioned', 'm' => 'get_by_stage', 'p' => 5, 't' => 'string|array|null', 'n' => 'Changed type of parameter $limit from dynamic to string|array|null', [cite: 97]
        'c' => 'Versioned', 'm' => 'get_including_deleted', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $class from dynamic to string', [cite: 96]
        'c' => 'Versioned', 'm' => 'get_including_deleted', 'p' => 1, 't' => 'string|array', 'n' => 'Changed type of parameter $filter from dynamic to string|array', [cite: 97]
        'c' => 'Versioned', 'm' => 'get_including_deleted', 'p' => 2, 't' => 'string', 'n' => 'Changed type of parameter $sort from dynamic to string', [cite: 98]

        // VirtualPage
        'c' => 'VirtualPage', 'm' => '__get', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $field from dynamic to string', [cite: 19]
        'c' => 'VirtualPage', 'm' => 'castingHelper', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $field from dynamic to string', [cite: 19]
        'c' => 'VirtualPage', 'm' => 'getField', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $field from dynamic to string', [cite: 19]
        'c' => 'VirtualPage', 'm' => 'getViewerTemplates', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $suffix from dynamic to string', [cite: 21]
        'c' => 'VirtualPage', 'm' => 'hasField', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $field from dynamic to string', [cite: 19]

        // WithinRangeFilter
        'c' => 'WithinRangeFilter', 'm' => 'setMax', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $max from dynamic to mixed', [cite: 53]
        'c' => 'WithinRangeFilter', 'm' => 'setMin', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $min from dynamic to mixed', [cite: 59]

        // WorkflowInstance
        // 'c' => 'WorkflowInstance', 'm' => 'beginWorkflow', 'p' => 0, 't' => 'DataObject|null', 'n' => 'Changed type of parameter $for from DataObject to DataObject|null', [cite: 100]

        // WorkflowTemplate
        // 'c' => 'WorkflowTemplate', 'm' => 'createAction', 'p' => 0, 't' => 'WorkflowDefinition|null', 'n' => 'Changed type of parameter $definition from WorkflowDefinition to WorkflowDefinition|null', [cite: 100]

        // i18nTextCollector
        // 'c' => 'i18nTextCollector', 'm' => 'collectFromEntityProviders', 'p' => 0, 't' => 'Module|null', 'n' => 'Changed type of parameter $module from Module to Module|null', [cite: 59]

        // i18nTextCollectorTask
        'c' => 'i18nTextCollectorTask', 'm' => 'getIsMerge', 'p' => 0, 't' => 'Symfony\\Component\\Console\\Input\\InputInterface', 'n' => 'Changed type of parameter $request from dynamic to Symfony\\Component\\Console\\Input\\InputInterface', [cite: 75]
    ];
}
