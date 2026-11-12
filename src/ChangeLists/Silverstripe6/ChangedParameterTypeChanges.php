<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\ChangeLists\Silverstripe6;

use Netwerkstatt\SilverstripeRector\Interfaces\ChangeListInterface;
use Netwerkstatt\SilverstripeRector\Traits\MethodChangeHelper;

class ChangedParameterTypeChanges implements ChangeListInterface
{
    use MethodChangeHelper;

    protected const LIST = [
        // AdminRootController
        ['c' => 'AdminRootController', 'm' => 'add_rule_for_controller', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $controllerClass from dynamic to string'],

        // AssetFormFactory
        // ['c' => 'AssetFormFactory', 'm' => 'getFormActions', 'p' => 0, 't' => 'RequestHandler|null', 'n' => 'Changed type of parameter $controller from RequestHandler to RequestHandler|null'],
        // ['c' => 'AssetFormFactory', 'm' => 'getFormFields', 'p' => 0, 't' => 'RequestHandler|null', 'n' => 'Changed type of parameter $controller from RequestHandler to RequestHandler|null'],
        // ['c' => 'AssetFormFactory', 'm' => 'getValidator', 'p' => 0, 't' => 'RequestHandler|null', 'n' => 'Changed type of parameter $controller from RequestHandler to RequestHandler|null'],

        // Authenticator
        // ['c' => 'Authenticator', 'm' => 'authenticate', 'p' => 0, 't' => 'ValidationResult|null', 'n' => 'Changed type of parameter $result from SilverStripe\\ORM\\ValidationResult to ValidationResult|null'],
        // ['c' => 'Authenticator', 'm' => 'checkPassword', 'p' => 0, 't' => 'ValidationResult|null', 'n' => 'Changed type of parameter $result from SilverStripe\\ORM\\ValidationResult to ValidationResult|null'],

        // BasicAuth
        // ['c' => 'BasicAuth', 'm' => 'protect_site_if_necessary', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],

        // BlogPostFilter
        // ['c' => 'BlogPostFilter', 'm' => 'augmentLoadLazyFields', 'p' => 0, 't' => 'DataQuery|null', 'n' => 'Changed type of parameter $dataQuery from DataQuery to DataQuery|null'],

        // BuildTask
        ['c' => 'BuildTask', 'm' => 'run', 'p' => 0, 't' => 'Symfony\\Component\\Console\\Input\\InputInterface', 'n' => 'Changed type of parameter $request from dynamic to Symfony\\Component\\Console\\Input\\InputInterface'],

        // CMSMain
        // ['c' => 'CMSMain', 'm' => 'getTreeNodeClasses', 'p' => 0, 't' => 'DataObject', 'n' => 'Changed type of parameter $node from SiteTree to DataObject'],
        // ['c' => 'CMSMain', 'm' => 'getCMSEditLinkForManagedDataObject', 'p' => 0, 't' => 'DataObject', 'n' => 'Changed type of parameter $obj from SiteTree to DataObject'],
        // ['c' => 'CMSMain', 'm' => 'getArchiveWarningMessage', 'p' => 0, 't' => 'DataObject', 'n' => 'Changed type of parameter $record from dynamic to DataObject'],

        // CanViewPermission
        // ['c' => 'CanViewPermission', 'm' => 'listPermissionCheck', 'p' => 0, 't' => 'SS_List', 'n' => 'Changed type of parameter $obj from SilverStripe\\ORM\\Filterable to SS_List'],

        // CanonicalURLMiddleware
        // ['c' => 'CanonicalURLMiddleware', 'm' => 'getOrValidateRequest', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'CanonicalURLMiddleware', 'm' => 'throwRedirectIfNeeded', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'CanonicalURLMiddleware', 'm' => 'hasBasicAuthPrompt', 'p' => 0, 't' => 'HTTPResponse|null', 'n' => 'Changed type of parameter $response from HTTPResponse to HTTPResponse|null'],

        // ChangePasswordHandler
        ['c' => 'ChangePasswordHandler', 'm' => 'setSessionToken', 'p' => 0, 't' => 'Member', 'n' => 'Changed type of parameter $member from dynamic to Member'],
        ['c' => 'ChangePasswordHandler', 'm' => 'setSessionToken', 'p' => 1, 't' => 'string', 'n' => 'Changed type of parameter $token from dynamic to string'],

        // ClassManifest
        // ['c' => 'ClassManifest', 'm' => '__construct', 'p' => 0, 't' => 'CacheFactory|null', 'n' => 'Changed type of parameter $cacheFactory from CacheFactory to CacheFactory|null'],

        // ConfirmedPasswordField
        ['c' => 'ConfirmedPasswordField', 'm' => 'setMaxLength', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $maxLength from dynamic to int'],
        ['c' => 'ConfirmedPasswordField', 'm' => 'setRequireExistingPassword', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $show from dynamic to bool'],
        ['c' => 'ConfirmedPasswordField', 'm' => 'setMinLength', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $minLength from dynamic to int'],

        // Convert
        ['c' => 'Convert', 'm' => 'linkIfMatch', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $string from dynamic to string'],

        // Cookie
        ['c' => 'Cookie', 'm' => 'force_expiry', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $name from dynamic to string'],
        // ['c' => 'Cookie', 'm' => 'force_expiry', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $path from dynamic to string|null'],
        // ['c' => 'Cookie', 'm' => 'force_expiry', 'p' => 2, 't' => 'string|null', 'n' => 'Changed type of parameter $domain from dynamic to string|null'],
        ['c' => 'Cookie', 'm' => 'force_expiry', 'p' => 3, 't' => 'bool', 'n' => 'Changed type of parameter $secure from dynamic to bool'],
        ['c' => 'Cookie', 'm' => 'force_expiry', 'p' => 4, 't' => 'bool', 'n' => 'Changed type of parameter $httpOnly from dynamic to bool'],
        ['c' => 'Cookie', 'm' => 'get', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $name from dynamic to string'],
        ['c' => 'Cookie', 'm' => 'get', 'p' => 1, 't' => 'bool', 'n' => 'Changed type of parameter $includeUnsent from dynamic to bool'],
        ['c' => 'Cookie', 'm' => 'get_all', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $includeUnsent from dynamic to bool'],
        ['c' => 'Cookie', 'm' => 'set', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $name from dynamic to string'],
        // ['c' => 'Cookie', 'm' => 'set', 'p' => 1, 't' => 'string|false', 'n' => 'Changed type of parameter $value from dynamic to string|false'],
        ['c' => 'Cookie', 'm' => 'set', 'p' => 2, 't' => 'int|float', 'n' => 'Changed type of parameter $expiry from dynamic to int|float'],
        // ['c' => 'Cookie', 'm' => 'set', 'p' => 3, 't' => 'string|null', 'n' => 'Changed type of parameter $path from dynamic to string|null'],
        // ['c' => 'Cookie', 'm' => 'set', 'p' => 4, 't' => 'string|null', 'n' => 'Changed type of parameter $domain from dynamic to string|null'],
        ['c' => 'Cookie', 'm' => 'set', 'p' => 5, 't' => 'bool', 'n' => 'Changed type of parameter $secure from dynamic to bool'],
        ['c' => 'Cookie', 'm' => 'set', 'p' => 6, 't' => 'bool', 'n' => 'Changed type of parameter $httpOnly from dynamic to bool'],

        // CookieAuthenticationHandler
        ['c' => 'CookieAuthenticationHandler', 'm' => 'setDeviceCookieName', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $deviceCookieName from dynamic to string'],
        ['c' => 'CookieAuthenticationHandler', 'm' => 'setTokenCookieName', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $tokenCookieName from dynamic to string'],
        ['c' => 'CookieAuthenticationHandler', 'm' => 'setTokenCookieSecure', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $tokenCookieSecure from dynamic to bool'],

        // CookieJar
        ['c' => 'CookieJar', 'm' => 'outputCookie', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $name from dynamic to string'],
        // ['c' => 'CookieJar', 'm' => 'outputCookie', 'p' => 1, 't' => 'string|false', 'n' => 'Changed type of parameter $value from dynamic to string|false'],
        ['c' => 'CookieJar', 'm' => 'outputCookie', 'p' => 2, 't' => 'int', 'n' => 'Changed type of parameter $expiry from dynamic to int'],
        // ['c' => 'CookieJar', 'm' => 'outputCookie', 'p' => 3, 't' => 'string|null', 'n' => 'Changed type of parameter $path from dynamic to string|null'],
        // ['c' => 'CookieJar', 'm' => 'outputCookie', 'p' => 4, 't' => 'string|null', 'n' => 'Changed type of parameter $domain from dynamic to string|null'],
        ['c' => 'CookieJar', 'm' => 'outputCookie', 'p' => 5, 't' => 'bool', 'n' => 'Changed type of parameter $secure from dynamic to bool'],
        ['c' => 'CookieJar', 'm' => 'outputCookie', 'p' => 6, 't' => 'bool', 'n' => 'Changed type of parameter $httpOnly from dynamic to bool'],

        // Cookie_Backend
        ['c' => 'Cookie_Backend', 'm' => '__construct', 'p' => 0, 't' => 'array', 'n' => 'Changed type of parameter $cookies from dynamic to array'],
        ['c' => 'Cookie_Backend', 'm' => 'forceExpiry', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $name from dynamic to string'],
        // ['c' => 'Cookie_Backend', 'm' => 'forceExpiry', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $path from dynamic to string|null'],
        // ['c' => 'Cookie_Backend', 'm' => 'forceExpiry', 'p' => 2, 't' => 'string|null', 'n' => 'Changed type of parameter $domain from dynamic to string|null'],
        ['c' => 'Cookie_Backend', 'm' => 'forceExpiry', 'p' => 3, 't' => 'bool', 'n' => 'Changed type of parameter $secure from dynamic to bool'],
        ['c' => 'Cookie_Backend', 'm' => 'forceExpiry', 'p' => 4, 't' => 'bool', 'n' => 'Changed type of parameter $httpOnly from dynamic to bool'],
        ['c' => 'Cookie_Backend', 'm' => 'get', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $name from dynamic to string'],
        ['c' => 'Cookie_Backend', 'm' => 'get', 'p' => 1, 't' => 'bool', 'n' => 'Changed type of parameter $includeUnsent from dynamic to bool'],
        ['c' => 'Cookie_Backend', 'm' => 'getAll', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $includeUnsent from dynamic to bool'],
        ['c' => 'Cookie_Backend', 'm' => 'set', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $name from dynamic to string'],
        // ['c' => 'Cookie_Backend', 'm' => 'set', 'p' => 1, 't' => 'string|false', 'n' => 'Changed type of parameter $value from dynamic to string|false'],
        ['c' => 'Cookie_Backend', 'm' => 'set', 'p' => 2, 't' => 'int|float', 'n' => 'Changed type of parameter $expiry from dynamic to int|float'],
        // ['c' => 'Cookie_Backend', 'm' => 'set', 'p' => 3, 't' => 'string|null', 'n' => 'Changed type of parameter $path from dynamic to string|null'],
        // ['c' => 'Cookie_Backend', 'm' => 'set', 'p' => 4, 't' => 'string|null', 'n' => 'Changed type of parameter $domain from dynamic to string|null'],
        ['c' => 'Cookie_Backend', 'm' => 'set', 'p' => 5, 't' => 'bool', 'n' => 'Changed type of parameter $secure from dynamic to bool'],
        ['c' => 'Cookie_Backend', 'm' => 'set', 'p' => 6, 't' => 'bool', 'n' => 'Changed type of parameter $httpOnly from dynamic to bool'],

        // CoreConfigFactory
        // ['c' => 'CoreConfigFactory', 'm' => '__construct', 'p' => 0, 't' => 'CacheFactory|null', 'n' => 'Changed type of parameter $cacheFactory from CacheFactory to CacheFactory|null'],

        // CredentialRepositoryProviderTrait
        // ['c' => 'CredentialRepositoryProviderTrait', 'm' => 'getCredentialRepository', 'p' => 0, 't' => 'RegisteredMethod|null', 'n' => 'Changed type of parameter $registeredMethod from RegisteredMethod to RegisteredMethod|null'],

        // DBClassNameTrait
        // ['c' => 'DBClassNameTrait', 'm' => '__construct', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $name from dynamic to string|null'],
        // ['c' => 'DBClassNameTrait', 'm' => '__construct', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $baseClass from dynamic to string|null'],
        ['c' => 'DBClassNameTrait', 'm' => '__construct', 'p' => 2, 't' => 'array', 'n' => 'Changed type of parameter $options from dynamic to array'],
        // ['c' => 'DBClassNameTrait', 'm' => 'setBaseClass', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $baseClass from dynamic to string|null'],
        ['c' => 'DBClassNameTrait', 'm' => 'setValue', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $value from dynamic to mixed'],
        // ['c' => 'DBClassNameTrait', 'm' => 'setValue', 'p' => 1, 't' => 'null|array|ModelData', 'n' => 'Changed type of parameter $record from dynamic to null|array|ModelData'],
        ['c' => 'DBClassNameTrait', 'm' => 'setValue', 'p' => 2, 't' => 'bool', 'n' => 'Changed type of parameter $markChanged from dynamic to bool'],

        // DBComposite
        ['c' => 'DBComposite', 'm' => 'bindTo', 'p' => 0, 't' => 'DataObject', 'n' => 'Changed type of parameter $dataObject from dynamic to DataObject'],
        ['c' => 'DBComposite', 'm' => 'dbObject', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $field from dynamic to string'],

        // DBDate
        ['c' => 'DBDate', 'm' => 'DayOfMonth', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $includeOrdinal from dynamic to bool'],
        ['c' => 'DBDate', 'm' => 'Format', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $format from dynamic to string'],
        // ['c' => 'DBDate', 'm' => 'FormatFromSettings', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from dynamic to Member|null'],
        // ['c' => 'DBDate', 'm' => 'getCustomFormatter', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $pattern from dynamic to string|null'],
        // ['c' => 'DBDate', 'm' => 'getCustomFormatter', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $locale from dynamic to string|null'],
        ['c' => 'DBDate', 'm' => 'getCustomFormatter', 'p' => 2, 't' => 'int', 'n' => 'Changed type of parameter $dateLength from dynamic to int'],
        ['c' => 'DBDate', 'm' => 'getCustomFormatter', 'p' => 3, 't' => 'int', 'n' => 'Changed type of parameter $timeLength from dynamic to int'],
        ['c' => 'DBDate', 'm' => 'getFormatter', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $dateLength from dynamic to int'],
        ['c' => 'DBDate', 'm' => 'getFormatter', 'p' => 1, 't' => 'int', 'n' => 'Changed type of parameter $timeLength from dynamic to int'],
        ['c' => 'DBDate', 'm' => 'parseDate', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $value from dynamic to mixed'],

        // DBDatetime
        ['c' => 'DBDatetime', 'm' => 'set_mock_now', 'p' => 0, 't' => 'DBDatetime|string', 'n' => 'Changed type of parameter $datetime from dynamic to DBDatetime|string'],
        ['c' => 'DBDatetime', 'm' => 'withFixedNow', 'p' => 0, 't' => 'DBDatetime|string', 'n' => 'Changed type of parameter $time from dynamic to DBDatetime|string'],
        ['c' => 'DBDatetime', 'm' => 'withFixedNow', 'p' => 1, 't' => 'callable', 'n' => 'Changed type of parameter $callback from dynamic to callable'],

        // DBEnum
        ['c' => 'DBEnum', 'm' => 'enumValues', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $hasEmpty from dynamic to bool'],
        // ['c' => 'DBEnum', 'm' => 'formField', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $title from dynamic to string|null'],
        // ['c' => 'DBEnum', 'm' => 'formField', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $name from dynamic to string|null'],
        // ['c' => 'DBEnum', 'm' => 'formField', 'p' => 2, 't' => 'string|null', 'n' => 'Changed type of parameter $value from dynamic to string|null'],
        // ['c' => 'DBEnum', 'm' => 'formField', 'p' => 3, 't' => 'string|null', 'n' => 'Changed type of parameter $emptyString from dynamic to string|null'],
        ['c' => 'DBEnum', 'm' => 'formField', 'p' => 4, 't' => 'bool', 'n' => 'Changed type of parameter $hasEmpty from dynamic to bool'],
        // ['c' => 'DBEnum', 'm' => 'setDefault', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $default from dynamic to string|null'],
        ['c' => 'DBEnum', 'm' => 'setEnum', 'p' => 0, 't' => 'string|array', 'n' => 'Changed type of parameter $enum from dynamic to string|array'],

        // DBField
        ['c' => 'DBField', 'm' => 'addToQuery', 'p' => 0, 't' => 'SQLSelect', 'n' => 'Changed type of parameter $query from dynamic to SQLSelect'],
        ['c' => 'DBField', 'm' => 'create_field', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $spec from dynamic to string'],
        ['c' => 'DBField', 'm' => 'create_field', 'p' => 1, 't' => 'mixed', 'n' => 'Changed type of parameter $value from dynamic to mixed'],
        // ['c' => 'DBField', 'm' => 'create_field', 'p' => 2, 't' => 'string|null', 'n' => 'Changed type of parameter $name from dynamic to string|null'],
        ['c' => 'DBField', 'm' => 'create_field', 'p' => 3, 't' => 'mixed', 'n' => 'Changed type of parameter $args from dynamic to mixed'],
        // ['c' => 'DBField', 'm' => 'defaultSearchFilter', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $name from dynamic to string|null'],
        ['c' => 'DBField', 'm' => 'prepValueForDB', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $value from dynamic to mixed'],
        // ['c' => 'DBField', 'm' => 'saveInto', 'p' => 0, 't' => 'ModelData', 'n' => 'Changed type of parameter $dataObject from dynamic to ModelData'],
        // ['c' => 'DBField', 'm' => 'scaffoldFormField', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $title from dynamic to string|null'],
        ['c' => 'DBField', 'm' => 'scaffoldFormField', 'p' => 1, 't' => 'array', 'n' => 'Changed type of parameter $params from dynamic to array'],
        // ['c' => 'DBField', 'm' => 'scaffoldSearchField', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $title from dynamic to string|null'],
        ['c' => 'DBField', 'm' => 'setDefaultValue', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $defaultValue from dynamic to mixed'],
        ['c' => 'DBField', 'm' => 'setName', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $name from dynamic to string'],
        ['c' => 'DBField', 'm' => 'setTable', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $tableName from dynamic to string'],
        ['c' => 'DBField', 'm' => 'setValue', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $value from dynamic to mixed'],
        // ['c' => 'DBField', 'm' => 'setValue', 'p' => 1, 't' => 'null|array|ModelData', 'n' => 'Changed type of parameter $record from dynamic to null|array|ModelData'],
        ['c' => 'DBField', 'm' => 'setValue', 'p' => 2, 't' => 'bool', 'n' => 'Changed type of parameter $markChanged from dynamic to bool'],
        ['c' => 'DBField', 'm' => 'writeToManipulation', 'p' => 0, 't' => 'array', 'n' => 'Changed type of parameter $manipulation from dynamic to array'],

        // DBFile
        ['c' => 'DBFile', 'm' => 'assertFilenameValid', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $filename from dynamic to string'],
        ['c' => 'DBFile', 'm' => 'getSourceURL', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $grant from dynamic to bool'],
        ['c' => 'DBFile', 'm' => 'isValidFilename', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $filename from dynamic to string'],
        ['c' => 'DBFile', 'm' => 'setAllowedCategories', 'p' => 0, 't' => 'array|string', 'n' => 'Changed type of parameter $categories from dynamic to array|string'],
        ['c' => 'DBFile', 'm' => 'setOriginal', 'p' => 0, 't' => 'AssetContainer', 'n' => 'Changed type of parameter $original from dynamic to AssetContainer'],
        // ['c' => 'DBFile', 'm' => 'validateFilename', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $filename from dynamic to string|null'],

        // DBHTMLText
        ['c' => 'DBHTMLText', 'm' => 'setProcessShortcodes', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $process from dynamic to bool'],
        ['c' => 'DBHTMLText', 'm' => 'setWhitelist', 'p' => 0, 't' => 'string|array', 'n' => 'Changed type of parameter $whitelist from dynamic to string|array'],
        ['c' => 'DBHTMLText', 'm' => 'whitelistContent', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $value from dynamic to mixed'],

        // DBHTMLVarchar
        ['c' => 'DBHTMLVarchar', 'm' => 'setProcessShortcodes', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $process from dynamic to bool'],

        // DBLocale
        ['c' => 'DBLocale', 'm' => 'Nice', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $showNative from dynamic to bool'],

        // DBMoney
        ['c' => 'DBMoney', 'm' => 'setAmount', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $amount from dynamic to mixed'],
        ['c' => 'DBMoney', 'm' => 'setAmount', 'p' => 1, 't' => 'bool', 'n' => 'Changed type of parameter $markChanged from dynamic to bool'],
        // ['c' => 'DBMoney', 'm' => 'setCurrency', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $currency from dynamic to string|null'],
        ['c' => 'DBMoney', 'm' => 'setCurrency', 'p' => 1, 't' => 'bool', 'n' => 'Changed type of parameter $markChanged from dynamic to bool'],
        ['c' => 'DBMoney', 'm' => 'setLocale', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $locale from dynamic to string'],

        // DBPolymorphicForeignKey
        ['c' => 'DBPolymorphicForeignKey', 'm' => 'setClassValue', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $value from dynamic to string'],
        ['c' => 'DBPolymorphicForeignKey', 'm' => 'setClassValue', 'p' => 1, 't' => 'bool', 'n' => 'Changed type of parameter $markChanged from dynamic to bool'],
        ['c' => 'DBPolymorphicForeignKey', 'm' => 'setIDValue', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $value from dynamic to int'],
        ['c' => 'DBPolymorphicForeignKey', 'm' => 'setIDValue', 'p' => 1, 't' => 'bool', 'n' => 'Changed type of parameter $markChanged from dynamic to bool'],

        // DBPrimaryKey
        ['c' => 'DBPrimaryKey', 'm' => 'setAutoIncrement', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $autoIncrement from dynamic to bool'],

        // DBString
        ['c' => 'DBString', 'm' => 'LimitCharacters', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $limit from dynamic to int'],
        // ['c' => 'DBString', 'm' => 'LimitCharacters', 'p' => 1, 't' => 'string|false', 'n' => 'Changed type of parameter $add from dynamic to string|false'],
        ['c' => 'DBString', 'm' => 'LimitCharactersToClosestWord', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $limit from dynamic to int'],
        // ['c' => 'DBString', 'm' => 'LimitCharactersToClosestWord', 'p' => 1, 't' => 'string|false', 'n' => 'Changed type of parameter $add from dynamic to string|false'],
        ['c' => 'DBString', 'm' => 'LimitWordCount', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $numWords from dynamic to int'],
        // ['c' => 'DBString', 'm' => 'LimitWordCount', 'p' => 1, 't' => 'string|false', 'n' => 'Changed type of parameter $add from dynamic to string|false'],
        ['c' => 'DBString', 'm' => 'setNullifyEmpty', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $value from dynamic to bool'],

        // DBText
        ['c' => 'DBText', 'm' => 'ContextSummary', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $characters from dynamic to int'],
        // ['c' => 'DBText', 'm' => 'ContextSummary', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $keywords from dynamic to string|null'],
        ['c' => 'DBText', 'm' => 'ContextSummary', 'p' => 2, 't' => 'bool', 'n' => 'Changed type of parameter $highlight from dynamic to bool'],
        // ['c' => 'DBText', 'm' => 'ContextSummary', 'p' => 3, 't' => 'string|false', 'n' => 'Changed type of parameter $prefix from dynamic to string|false'],
        // ['c' => 'DBText', 'm' => 'ContextSummary', 'p' => 4, 't' => 'string|false', 'n' => 'Changed type of parameter $suffix from dynamic to string|false'],
        ['c' => 'DBText', 'm' => 'LimitSentences', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $maxSentences from dynamic to int'],
        ['c' => 'DBText', 'm' => 'Summary', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $maxWords from dynamic to int'],
        // ['c' => 'DBText', 'm' => 'Summary', 'p' => 1, 't' => 'string|false', 'n' => 'Changed type of parameter $add from dynamic to string|false'],

        // DBTime
        ['c' => 'DBTime', 'm' => 'Format', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $format from dynamic to string'],
        // ['c' => 'DBTime', 'm' => 'FormatFromSettings', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from dynamic to Member|null'],
        ['c' => 'DBTime', 'm' => 'getFormatter', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $timeLength from dynamic to int'],
        ['c' => 'DBTime', 'm' => 'parseTime', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $value from dynamic to mixed'],

        // DataList
        ['c' => 'DataList', 'm' => 'columnUnique', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $colName from dynamic to string'],
        ['c' => 'DataList', 'm' => 'dbObject', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $fieldName from dynamic to string'],

        // DataObject
        ['c' => 'DataObject', 'm' => 'dbObject', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $fieldName from dynamic to string'],
        ['c' => 'DataObject', 'm' => 'flushCache', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $persistent from dynamic to bool'],

        // DataObjectInterface
        ['c' => 'DataObjectInterface', 'm' => '__get', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $fieldName from dynamic to string'],

        // DataObjectVersionFormFactory
        // ['c' => 'DataObjectVersionFormFactory', 'm' => 'getFormActions', 'p' => 0, 't' => 'RequestHandler|null', 'n' => 'Changed type of parameter $controller from RequestHandler to RequestHandler|null'],
        // ['c' => 'DataObjectVersionFormFactory', 'm' => 'getFormFields', 'p' => 0, 't' => 'RequestHandler|null', 'n' => 'Changed type of parameter $controller from RequestHandler to RequestHandler|null'],

        // DateField
        ['c' => 'DateField', 'm' => 'internalToFrontend', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $date from dynamic to mixed'],
        ['c' => 'DateField', 'm' => 'tidyInternal', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $date from dynamic to mixed'],

        // DatetimeField
        ['c' => 'DatetimeField', 'm' => 'internalToFrontend', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $datetime from dynamic to mixed'],
        ['c' => 'DatetimeField', 'm' => 'tidyInternal', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $datetime from dynamic to mixed'],

        // Debug
        // ['c' => 'Debug', 'm' => 'create_debug_view', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'Debug', 'm' => 'dump', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'Debug', 'm' => 'endshow', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'Debug', 'm' => 'message', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'Debug', 'm' => 'show', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'Debug', 'm' => 'supportsHTML', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'Debug', 'm' => 'text', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],

        // DefaultCacheFactory
        // ['c' => 'DefaultCacheFactory', 'm' => '__construct', 'p' => 0, 't' => 'Psr\\Log\\LoggerInterface|null', 'n' => 'Changed type of parameter $logger from Psr\\Log\\LoggerInterface to Psr\\Log\\LoggerInterface|null'],

        // DefaultPermissionChecker
        // ['c' => 'DefaultPermissionChecker', 'm' => 'canCreate', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null'],
        // ['c' => 'DefaultPermissionChecker', 'm' => 'canDelete', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null'],
        // ['c' => 'DefaultPermissionChecker', 'm' => 'canEdit', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null'],
        // ['c' => 'DefaultPermissionChecker', 'm' => 'canView', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null'],

        // Director
        // ['c' => 'Director', 'm' => 'absoluteBaseURLWithAuth', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'Director', 'm' => 'currentRequest', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'Director', 'm' => 'forceSSL', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'Director', 'm' => 'forceWWW', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'Director', 'm' => 'host', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'Director', 'm' => 'hostName', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'Director', 'm' => 'is_ajax', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'Director', 'm' => 'is_https', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'Director', 'm' => 'port', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'Director', 'm' => 'protocol', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'Director', 'm' => 'protocolAndHost', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],

        // EagerLoadedList
        ['c' => 'EagerLoadedList', 'm' => 'columnUnique', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $colName from dynamic to string'],

        // EditableFileField
        // ['c' => 'EditableFileField', 'm' => 'getFolderPermissionString', 'p' => 0, 't' => 'Folder|null', 'n' => 'Changed type of parameter $folder from Folder to Folder|null'],

        // Email
        // ['c' => 'Email', 'm' => 'setBody', 'p' => 0, 't' => 'Symfony\\Component\\Mime\\Part\\AbstractPart|string|null', 'n' => 'Changed type of parameter $body from Symfony\\Component\\Mime\\Part\\AbstractPart|string to Symfony\\Component\\Mime\\Part\\AbstractPart|string|null'],
        // ['c' => 'Email', 'm' => 'setData', 'p' => 0, 't' => 'array|ModelData', 'n' => 'Changed type of parameter $data from array|SilverStripe\\View\\ViewableData to array|ModelData'],

        // Factory
        ['c' => 'Factory', 'm' => 'create', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $service from dynamic to string'],

        // FieldFilterInterface
        // ['c' => 'FieldFilterInterface', 'm' => 'apply', 'p' => 0, 't' => 'SS_List', 'n' => 'Changed type of parameter $list from SilverStripe\\ORM\\Filterable to SS_List'],

        // FieldList
        ['c' => 'FieldList', 'm' => 'addFieldToTab', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $tabName from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'addFieldToTab', 'p' => 1, 't' => 'FormField', 'n' => 'Changed type of parameter $field from dynamic to FormField'],
        // ['c' => 'FieldList', 'm' => 'addFieldToTab', 'p' => 2, 't' => 'string|null', 'n' => 'Changed type of parameter $insertBefore from dynamic to string|null'],
        ['c' => 'FieldList', 'm' => 'addFieldsToTab', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $tabName from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'addFieldsToTab', 'p' => 1, 't' => 'array', 'n' => 'Changed type of parameter $fields from dynamic to array'],
        // ['c' => 'FieldList', 'm' => 'addFieldsToTab', 'p' => 2, 't' => 'string|null', 'n' => 'Changed type of parameter $insertBefore from dynamic to string|null'],
        ['c' => 'FieldList', 'm' => 'changeFieldOrder', 'p' => 0, 't' => 'array|string', 'n' => 'Changed type of parameter $fieldNames from dynamic to array|string'],
        ['c' => 'FieldList', 'm' => 'dataFieldByName', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $name from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'fieldByName', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $name from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'fieldNameError', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $functionName from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'fieldPosition', 'p' => 0, 't' => 'string|FormField', 'n' => 'Changed type of parameter $field from dynamic to string|FormField'],
        ['c' => 'FieldList', 'm' => 'findOrMakeTab', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $tabName from dynamic to string'],
        // ['c' => 'FieldList', 'm' => 'findOrMakeTab', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $title from dynamic to string|null'],
        ['c' => 'FieldList', 'm' => 'findTab', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $tabName from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'makeFieldReadonly', 'p' => 0, 't' => 'string|array|FormField', 'n' => 'Changed type of parameter $field from dynamic to string|array|FormField'],
        ['c' => 'FieldList', 'm' => 'removeByName', 'p' => 0, 't' => 'string|array', 'n' => 'Changed type of parameter $fieldName from dynamic to string|array'],
        ['c' => 'FieldList', 'm' => 'removeByName', 'p' => 1, 't' => 'bool', 'n' => 'Changed type of parameter $dataFieldOnly from dynamic to bool'],
        ['c' => 'FieldList', 'm' => 'removeFieldFromTab', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $tabName from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'removeFieldFromTab', 'p' => 1, 't' => 'string', 'n' => 'Changed type of parameter $fieldName from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'removeFieldsFromTab', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $tabName from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'removeFieldsFromTab', 'p' => 1, 't' => 'array', 'n' => 'Changed type of parameter $fields from dynamic to array'],
        ['c' => 'FieldList', 'm' => 'renameField', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $fieldName from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'renameField', 'p' => 1, 't' => 'string', 'n' => 'Changed type of parameter $newFieldTitle from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'replaceField', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $fieldName from dynamic to string'],
        ['c' => 'FieldList', 'm' => 'replaceField', 'p' => 1, 't' => 'FormField', 'n' => 'Changed type of parameter $newField from dynamic to FormField'],
        ['c' => 'FieldList', 'm' => 'replaceField', 'p' => 2, 't' => 'bool', 'n' => 'Changed type of parameter $dataFieldOnly from dynamic to bool'],
        // ['c' => 'FieldList', 'm' => 'setContainerField', 'p' => 0, 't' => 'CompositeField|null', 'n' => 'Changed type of parameter $field from dynamic to CompositeField|null'],
        ['c' => 'FieldList', 'm' => 'setForm', 'p' => 0, 't' => 'Form', 'n' => 'Changed type of parameter $form from dynamic to Form'],
        ['c' => 'FieldList', 'm' => 'setValues', 'p' => 0, 't' => 'array', 'n' => 'Changed type of parameter $data from dynamic to array'],
        ['c' => 'FieldList', 'm' => 'transform', 'p' => 0, 't' => 'FormTransformation', 'n' => 'Changed type of parameter $trans from dynamic to FormTransformation'],

        // FileLinkTracking
        // ['c' => 'FileLinkTracking', 'm' => 'setFileParser', 'p' => 0, 't' => 'FileLinkTrackingParser|null', 'n' => 'Changed type of parameter $parser from FileLinkTrackingParser to FileLinkTrackingParser|null'],

        // FileSearchFormFactory
        // ['c' => 'FileSearchFormFactory', 'm' => 'getFormFields', 'p' => 0, 't' => 'RequestHandler|null', 'n' => 'Changed type of parameter $controller from RequestHandler to RequestHandler|null'],

        // Filesystem
        // ['c' => 'Filesystem', 'm' => '__construct', 'p' => 0, 't' => 'League\\Flysystem\\PathNormalizer|null', 'n' => 'Changed type of parameter $pathNormalizer from League\\Flysystem\\PathNormalizer to League\\Flysystem\\PathNormalizer|null'],

        // FluentExtension
        // ['c' => 'FluentExtension', 'm' => 'getDataQueryLocale', 'p' => 0, 't' => 'DataQuery|null', 'n' => 'Changed type of parameter $dataQuery from DataQuery to DataQuery|null'],

        // FluentFilteredExtension
        // ['c' => 'FluentFilteredExtension', 'm' => 'getDataQueryLocale', 'p' => 0, 't' => 'DataQuery|null', 'n' => 'Changed type of parameter $dataQuery from DataQuery to DataQuery|null'],

        // FluentIsolatedExtension
        // ['c' => 'FluentIsolatedExtension', 'm' => 'getDataQueryLocale', 'p' => 0, 't' => 'DataQuery|null', 'n' => 'Changed type of parameter $dataQuery from DataQuery to DataQuery|null'],

        // FluentSiteTreeExtension
        ['c' => 'FluentSiteTreeExtension', 'm' => 'updateStatusFlags', 'p' => 0, 't' => 'array', 'n' => 'Changed type of parameter $flags from dynamic to array'],

        // Form
        ['c' => 'Form', 'm' => 'loadDataFrom', 'p' => 0, 't' => 'object|array', 'n' => 'Changed type of parameter $data from dynamic to object|array'],
        ['c' => 'Form', 'm' => 'loadDataFrom', 'p' => 1, 't' => 'int', 'n' => 'Changed type of parameter $mergeStrategy from dynamic to int'],
        ['c' => 'Form', 'm' => 'loadDataFrom', 'p' => 2, 't' => 'array', 'n' => 'Changed type of parameter $fieldList from dynamic to array'],
        ['c' => 'Form', 'm' => 'sessionError', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $message from dynamic to string'],
        ['c' => 'Form', 'm' => 'sessionError', 'p' => 1, 't' => 'string', 'n' => 'Changed type of parameter $type from dynamic to string'],
        ['c' => 'Form', 'm' => 'sessionError', 'p' => 2, 't' => 'string', 'n' => 'Changed type of parameter $cast from dynamic to string'],
        ['c' => 'Form', 'm' => 'sessionFieldError', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $message from dynamic to string'],
        ['c' => 'Form', 'm' => 'sessionFieldError', 'p' => 1, 't' => 'string', 'n' => 'Changed type of parameter $fieldName from dynamic to string'],
        ['c' => 'Form', 'm' => 'sessionFieldError', 'p' => 2, 't' => 'string', 'n' => 'Changed type of parameter $type from dynamic to string'],
        ['c' => 'Form', 'm' => 'sessionFieldError', 'p' => 3, 't' => 'string', 'n' => 'Changed type of parameter $cast from dynamic to string'],
        ['c' => 'Form', 'm' => 'sessionMessage', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $message from dynamic to string'],
        ['c' => 'Form', 'm' => 'sessionMessage', 'p' => 1, 't' => 'string', 'n' => 'Changed type of parameter $type from dynamic to string'],
        ['c' => 'Form', 'm' => 'sessionMessage', 'p' => 2, 't' => 'string', 'n' => 'Changed type of parameter $cast from dynamic to string'],
        // ['c' => 'Form', 'm' => 'setController', 'p' => 0, 't' => 'RequestHandler|null', 'n' => 'Changed type of parameter $controller from RequestHandler to RequestHandler|null'],
        // ['c' => 'Form', 'm' => 'setValidator', 'p' => 0, 't' => 'Validator|null', 'n' => 'Changed type of parameter $validator from SilverStripe\\Forms\\Validator to Validator|null'],

        // FormFactory
        // ['c' => 'FormFactory', 'm' => 'getForm', 'p' => 0, 't' => 'RequestHandler|null', 'n' => 'Changed type of parameter $controller from RequestHandler to RequestHandler|null'],

        // FormSchema
        // ['c' => 'FormSchema', 'm' => 'getMultipartSchema', 'p' => 0, 't' => 'ValidationResult|null', 'n' => 'Changed type of parameter $result from SilverStripe\\ORM\\ValidationResult to ValidationResult|null'],
        // ['c' => 'FormSchema', 'm' => 'getMultipartSchema', 'p' => 1, 't' => 'Form|null', 'n' => 'Changed type of parameter $form from Form to Form|null'],

        // GridFieldNestedForm
        // ['c' => 'GridFieldNestedForm', 'm' => 'handleNestedItem', 'p' => 0, 't' => 'ModelData|null', 'n' => 'Changed type of parameter $record from SilverStripe\\View\\ViewableData|null to ModelData|null'],
        // ['c' => 'GridFieldNestedForm', 'm' => 'toggleNestedItem', 'p' => 0, 't' => 'ModelData|null', 'n' => 'Changed type of parameter $record from SilverStripe\\View\\ViewableData|null to ModelData|null'],

        // GridFieldSiteTreeAddNewButton
        // ['c' => 'GridFieldSiteTreeAddNewButton', 'm' => 'getAllowedChildren', 'p' => 0, 't' => 'SiteTree|null', 'n' => 'Changed type of parameter $parent from SiteTree to SiteTree|null'],

        // HTMLEditorConfig
        ['c' => 'HTMLEditorConfig', 'm' => 'getOption', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $key from dynamic to string'],
        ['c' => 'HTMLEditorConfig', 'm' => 'setOption', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $key from dynamic to string'],
        ['c' => 'HTMLEditorConfig', 'm' => 'setOption', 'p' => 1, 't' => 'mixed', 'n' => 'Changed type of parameter $value from dynamic to mixed'],
        ['c' => 'HTMLEditorConfig', 'm' => 'setOptions', 'p' => 0, 't' => 'array', 'n' => 'Changed type of parameter $options from dynamic to array'],
        ['c' => 'HTMLEditorConfig', 'm' => 'setThemes', 'p' => 0, 't' => 'array', 'n' => 'Changed type of parameter $themes from dynamic to array'],
        ['c' => 'HTMLEditorConfig', 'm' => 'set_active_identifier', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $identifier from dynamic to string'],
        ['c' => 'HTMLEditorConfig', 'm' => 'set_config', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $identifier from dynamic to string'],
        // ['c' => 'HTMLEditorConfig', 'm' => 'set_config', 'p' => 1, 't' => 'HTMLEditorConfig|null', 'n' => 'Changed type of parameter $config from HTMLEditorConfig to HTMLEditorConfig|null'],

        // HTTP
        ['c' => 'HTTP', 'm' => 'urlRewriter', 'p' => 0, 't' => 'callable', 'n' => 'Changed type of parameter $code from dynamic to callable'],

        // HistoryViewerController
        // ['c' => 'HistoryViewerController', 'm' => 'compareForm', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'HistoryViewerController', 'm' => 'versionForm', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],

        // IdentityStore
        // ['c' => 'IdentityStore', 'm' => 'logIn', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'IdentityStore', 'm' => 'logOut', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],

        // Image_Backend
        // ['c' => 'Image_Backend', 'm' => '__construct', 'p' => 0, 't' => 'AssetContainer|null', 'n' => 'Changed type of parameter $assetContainer from AssetContainer to AssetContainer|null'],
        ['c' => 'Image_Backend', 'm' => 'crop', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $top from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'crop', 'p' => 1, 't' => 'int', 'n' => 'Changed type of parameter $left from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'crop', 'p' => 2, 't' => 'int', 'n' => 'Changed type of parameter $width from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'crop', 'p' => 3, 't' => 'int', 'n' => 'Changed type of parameter $height from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'croppedResize', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $width from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'croppedResize', 'p' => 1, 't' => 'int', 'n' => 'Changed type of parameter $height from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'loadFrom', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $path from dynamic to string'],
        ['c' => 'Image_Backend', 'm' => 'paddedResize', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $width from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'paddedResize', 'p' => 1, 't' => 'int', 'n' => 'Changed type of parameter $height from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'paddedResize', 'p' => 2, 't' => 'string', 'n' => 'Changed type of parameter $backgroundColor from dynamic to string'],
        ['c' => 'Image_Backend', 'm' => 'paddedResize', 'p' => 3, 't' => 'int', 'n' => 'Changed type of parameter $transparencyPercent from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'resize', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $width from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'resize', 'p' => 1, 't' => 'int', 'n' => 'Changed type of parameter $height from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'resizeByHeight', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $height from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'resizeByWidth', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $width from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'resizeRatio', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $width from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'resizeRatio', 'p' => 1, 't' => 'int', 'n' => 'Changed type of parameter $height from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'resizeRatio', 'p' => 2, 't' => 'bool', 'n' => 'Changed type of parameter $useAsMinimum from dynamic to bool'],
        ['c' => 'Image_Backend', 'm' => 'setQuality', 'p' => 0, 't' => 'int', 'n' => 'Changed type of parameter $quality from dynamic to int'],
        ['c' => 'Image_Backend', 'm' => 'writeTo', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $path from dynamic to string'],
        ['c' => 'Image_Backend', 'm' => 'writeToStore', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $filename from dynamic to string'],
        // ['c' => 'Image_Backend', 'm' => 'writeToStore', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $hash from dynamic to string|null'],
        // ['c' => 'Image_Backend', 'm' => 'writeToStore', 'p' => 2, 't' => 'string|null', 'n' => 'Changed type of parameter $variant from dynamic to string|null'],
        ['c' => 'Image_Backend', 'm' => 'writeToStore', 'p' => 3, 't' => 'array', 'n' => 'Changed type of parameter $config from dynamic to array'],

        // InheritedPermissions
        // ['c' => 'InheritedPermissions', 'm' => '__construct', 'p' => 0, 't' => 'Psr\\SimpleCache\\CacheInterface|null', 'n' => 'Changed type of parameter $cache from Psr\\SimpleCache\\CacheInterface to Psr\\SimpleCache\\CacheInterface|null'],
        // ['c' => 'InheritedPermissions', 'm' => 'batchPermissionCheck', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null'],
        // ['c' => 'InheritedPermissions', 'm' => 'batchPermissionCheckForStage', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null'],
        // ['c' => 'InheritedPermissions', 'm' => 'checkDefaultPermissions', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null'],

        // InterventionBackend
        ['c' => 'InterventionBackend', 'm' => 'getDimensionCacheKey', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $hash from dynamic to string'],
        // ['c' => 'InterventionBackend', 'm' => 'getDimensionCacheKey', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $variant from dynamic to string|null'],
        ['c' => 'InterventionBackend', 'm' => 'getErrorCacheKey', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $hash from dynamic to string'],
        // ['c' => 'InterventionBackend', 'm' => 'getErrorCacheKey', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $variant from dynamic to string|null'],
        ['c' => 'InterventionBackend', 'm' => 'getResourceDimensions', 'p' => 0, 't' => 'Intervention\\Image\\Interfaces\\ImageInterface', 'n' => 'Changed type of parameter $resource from Intervention\\Image\\Image to Intervention\\Image\\Interfaces\\ImageInterface'],
        ['c' => 'InterventionBackend', 'm' => 'hasFailed', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $hash from dynamic to string'],
        // ['c' => 'InterventionBackend', 'm' => 'hasFailed', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $variant from dynamic to string|null'],
        ['c' => 'InterventionBackend', 'm' => 'isStreamReadable', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $stream from dynamic to mixed'],
        ['c' => 'InterventionBackend', 'm' => 'markFailed', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $hash from dynamic to string'],
        // ['c' => 'InterventionBackend', 'm' => 'markFailed', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $variant from dynamic to string|null'],
        ['c' => 'InterventionBackend', 'm' => 'markFailed', 'p' => 2, 't' => 'string', 'n' => 'Changed type of parameter $reason from dynamic to string'],
        ['c' => 'InterventionBackend', 'm' => 'markSuccess', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $hash from dynamic to string'],
        // ['c' => 'InterventionBackend', 'm' => 'markSuccess', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $variant from dynamic to string|null'],
        // ['c' => 'InterventionBackend', 'm' => 'setAssetContainer', 'p' => 0, 't' => 'AssetContainer|null', 'n' => 'Changed type of parameter $assetContainer from dynamic to AssetContainer|null'],
        ['c' => 'InterventionBackend', 'm' => 'setCache', 'p' => 0, 't' => 'Psr\\SimpleCache\\CacheInterface', 'n' => 'Changed type of parameter $cache from dynamic to Psr\\SimpleCache\\CacheInterface'],
        ['c' => 'InterventionBackend', 'm' => 'setImageManager', 'p' => 0, 't' => 'Intervention\\Image\\ImageManager', 'n' => 'Changed type of parameter $manager from dynamic to Intervention\\Image\\ImageManager'],
        ['c' => 'InterventionBackend', 'm' => 'setTempPath', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $path from dynamic to string'],
        ['c' => 'InterventionBackend', 'm' => 'warmCache', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $hash from dynamic to string'],
        // ['c' => 'InterventionBackend', 'm' => 'warmCache', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $variant from dynamic to string|null'],

        // LeftAndMain
        ['c' => 'LeftAndMain', 'm' => 'jsonError', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $errorMessage from dynamic to string'],
        ['c' => 'LeftAndMain', 'm' => 'jsonError', 'p' => 1, 't' => 'int', 'n' => 'Changed type of parameter $errorCode from dynamic to int'],
        ['c' => 'LeftAndMain', 'm' => 'getSchemaResponse', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $schemaID from dynamic to string'],
        // ['c' => 'LeftAndMain', 'm' => 'getSchemaResponse', 'p' => 1, 't' => 'ValidationResult|null', 'n' => 'Changed type of parameter $errors from SilverStripe\\ORM\\ValidationResult to ValidationResult|null'],
        // ['c' => 'LeftAndMain', 'm' => 'getSchemaResponse', 'p' => 2, 't' => 'Form|null', 'n' => 'Changed type of parameter $form from dynamic to Form|null'],
        ['c' => 'LeftAndMain', 'm' => 'getSchemaResponse', 'p' => 3, 't' => 'array', 'n' => 'Changed type of parameter $extraData from dynamic to array'],
        ['c' => 'LeftAndMain', 'm' => 'getTemplatesWithSuffix', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $suffix from dynamic to string'],

        // LeftAndMainSubsites
        // ['c' => 'LeftAndMainSubsites', 'm' => 'alternateAccessCheck', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null'],
        // ['c' => 'LeftAndMainSubsites', 'm' => 'canAccess', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null'],

        // LocalDateTime
        ['c' => 'LocalDateTime', 'm' => 'setLocalValue', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $value from dynamic to string'],
        // ['c' => 'LocalDateTime', 'm' => 'setLocalValue', 'p' => 1, 't' => 'string|null', 'n' => 'Changed type of parameter $timezone from dynamic to string|null'],

        // LocalFilesystemAdapter
        // ['c' => 'LocalFilesystemAdapter', 'm' => '__construct', 'p' => 0, 't' => 'League\\MimeTypeDetection\\MimeTypeDetector|null', 'n' => 'Changed type of parameter $mimeTypeDetector from League\\MimeTypeDetection\\MimeTypeDetector to League\\MimeTypeDetection\\MimeTypeDetector|null'],
        // ['c' => 'LocalFilesystemAdapter', 'm' => '__construct', 'p' => 1, 't' => 'League\\Flysystem\\UnixVisibility\\VisibilityConverter|null', 'n' => 'Changed type of parameter $visibility from League\\Flysystem\\UnixVisibility\\VisibilityConverter to League\\Flysystem\\UnixVisibility\\VisibilityConverter|null'],

        // LoginHandler
        // ['c' => 'LoginHandler', 'm' => 'checkLogin', 'p' => 0, 't' => 'ValidationResult|null', 'n' => 'Changed type of parameter $result from SilverStripe\\ORM\\ValidationResult to ValidationResult|null'],

        // Member
        // ['c' => 'Member', 'm' => 'afterMemberLoggedOut', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'Member', 'm' => 'beforeMemberLoggedOut', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],
        // ['c' => 'Member', 'm' => 'set_password_validator', 'p' => 0, 't' => 'PasswordValidator|null', 'n' => 'Changed type of parameter $validator from SilverStripe\\Security\\PasswordValidator to PasswordValidator|null'],
        // ['c' => 'Member', 'm' => 'validateCanLogin', 'p' => 0, 't' => 'ValidationResult|null', 'n' => 'Changed type of parameter $result from SilverStripe\\ORM\\ValidationResult to ValidationResult|null'],

        // MemberAuthenticator
        // ['c' => 'MemberAuthenticator', 'm' => 'authenticateMember', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null'],
        // ['c' => 'MemberAuthenticator', 'm' => 'authenticateMember', 'p' => 1, 't' => 'ValidationResult|null', 'n' => 'Changed type of parameter $result from SilverStripe\\ORM\\ValidationResult to ValidationResult|null'],

        // MemcachedCacheFactory
        // ['c' => 'MemcachedCacheFactory', 'm' => '__construct', 'p' => 0, 't' => 'Psr\\Log\\LoggerInterface|null', 'n' => 'Changed type of parameter $memcachedClient from Memcached to Psr\\Log\\LoggerInterface|null'],

        // ModuleManifest
        // ['c' => 'ModuleManifest', 'm' => '__construct', 'p' => 0, 't' => 'CacheFactory|null', 'n' => 'Changed type of parameter $cacheFactory from CacheFactory to CacheFactory|null'],

        // NotifyUsersWorkflowAction
        // ['c' => 'NotifyUsersWorkflowAction', 'm' => 'getMemberFields', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null'],

        // NumericField
        ['c' => 'NumericField', 'm' => 'cast', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $value from dynamic to mixed'],

        // PermissionChecker
        // ['c' => 'PermissionChecker', 'm' => 'canDelete', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null'],
        // ['c' => 'PermissionChecker', 'm' => 'canDeleteMultiple', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null'],
        // ['c' => 'PermissionChecker', 'm' => 'canEdit', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null'],
        // ['c' => 'PermissionChecker', 'm' => 'canEditMultiple', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null'],
        // ['c' => 'PermissionChecker', 'm' => 'canView', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null'],
        // ['c' => 'PermissionChecker', 'm' => 'canViewMultiple', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from Member to Member|null'],

        // PjaxResponseNegotiator
        // ['c' => 'PjaxResponseNegotiator', 'm' => '__construct', 'p' => 0, 't' => 'HTTPResponse|null', 'n' => 'Changed type of parameter $response from HTTPResponse to HTTPResponse|null'],

        // QueuedJobService
        // ['c' => 'QueuedJobService', 'm' => 'setRunAsUser', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $originalUser from Member to Member|null'],
        // ['c' => 'QueuedJobService', 'm' => 'unsetRunAsUser', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $runAsUser from Member to Member|null'],
        // ['c' => 'QueuedJobService', 'm' => 'unsetRunAsUser', 'p' => 1, 't' => 'Member|null', 'n' => 'Changed type of parameter $originalUser from Member to Member|null'],

        // RealMeService
        // ['c' => 'RealMeService', 'm' => 'getAuth', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],

        // Relation
        ['c' => 'Relation', 'm' => 'dbObject', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $fieldName from dynamic to string'],

        // SSListContains
        ['c' => 'SSListContains', 'm' => 'checkIfItemEvaluatesRemainingMatches', 'p' => 0, 't' => 'ModelData', 'n' => 'Changed type of parameter $item from SilverStripe\\View\\ViewableData to ModelData'],

        // SSViewer
        ['c' => 'SSViewer', 'm' => '__construct', 'p' => 0, 't' => 'string|array', 'n' => 'Changed type of parameter $templates from dynamic to string|array'],
        // ['c' => 'SSViewer', 'm' => '__construct', 'p' => 1, 't' => 'TemplateEngine|null', 'n' => 'Changed type of parameter $parser from SilverStripe\\View\\TemplateParser to TemplateEngine|null'],
        ['c' => 'SSViewer', 'm' => 'add_themes', 'p' => 0, 't' => 'array', 'n' => 'Changed type of parameter $themes from dynamic to array'],
        // ['c' => 'SSViewer', 'm' => 'get_templates_by_class', 'p' => 0, 't' => 'string|null', 'n' => 'Changed type of parameter $baseClass from dynamic to string|null'],
        ['c' => 'SSViewer', 'm' => 'get_templates_by_class', 'p' => 1, 't' => 'string', 'n' => 'Changed type of parameter $suffix from dynamic to string'],
        ['c' => 'SSViewer', 'm' => 'get_templates_by_class', 'p' => 2, 't' => 'string|object', 'n' => 'Changed type of parameter $classOrObject from dynamic to string|object'],
        ['c' => 'SSViewer', 'm' => 'includeRequirements', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $incl from dynamic to bool'],
        ['c' => 'SSViewer', 'm' => 'process', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $item from dynamic to mixed'],
        ['c' => 'SSViewer', 'm' => 'process', 'p' => 1, 't' => 'array', 'n' => 'Changed type of parameter $arguments from dynamic to array'],
        // ['c' => 'SSViewer', 'm' => 'setRewriteHashLinks', 'p' => 0, 't' => 'null|bool|string', 'n' => 'Changed type of parameter $rewrite from dynamic to null|bool|string'],
        // ['c' => 'SSViewer', 'm' => 'setRewriteHashLinksDefault', 'p' => 0, 't' => 'null|bool|string', 'n' => 'Changed type of parameter $rewrite from dynamic to null|bool|string'],
        ['c' => 'SSViewer', 'm' => 'set_themes', 'p' => 0, 't' => 'array', 'n' => 'Changed type of parameter $themes from dynamic to array'],

        // SearchContext
        // ['c' => 'SearchContext', 'm' => 'getQuery', 'p' => 0, 't' => 'int|array|null', 'n' => 'Changed type of parameter $limit from dynamic to int|array|null'],

        // Session
        // ['c' => 'Session', 'm' => 'destroy', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],

        // SiteTree
        // ['c' => 'SiteTree', 'm' => 'canAddChildren', 'p' => 0, 't' => 'Member|null', 'n' => 'Changed type of parameter $member from dynamic to Member|null'],
        ['c' => 'SiteTree', 'm' => 'getStatusFlags', 'p' => 0, 't' => 'bool', 'n' => 'Changed type of parameter $cached from dynamic to bool'],

        // SiteTreeLinkTracking
        // ['c' => 'SiteTreeLinkTracking', 'm' => 'setParser', 'p' => 0, 't' => 'SiteTreeLinkTracking_Parser|null', 'n' => 'Changed type of parameter $parser from SiteTreeLinkTracking_Parser to SiteTreeLinkTracking_Parser|null'],

        // TestMailer
        // ['c' => 'TestMailer', 'm' => 'send', 'p' => 0, 't' => 'Symfony\\Component\\Mailer\\Envelope|null', 'n' => 'Changed type of parameter $envelope from Symfony\\Component\\Mailer\\Envelope to Symfony\\Component\\Mailer\\Envelope|null'],

        // ThemeManifest
        // ['c' => 'ThemeManifest', 'm' => '__construct', 'p' => 0, 't' => 'CacheFactory|null', 'n' => 'Changed type of parameter $cacheFactory from CacheFactory to CacheFactory|null'],

        // ThumbnailGenerator
        // ['c' => 'ThumbnailGenerator', 'm' => 'generateLink', 'p' => 0, 't' => 'AssetContainer|null', 'n' => 'Changed type of parameter $thumbnail from AssetContainer to AssetContainer|null'],

        // TimeField
        ['c' => 'TimeField', 'm' => 'internalToFrontend', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $time from dynamic to mixed'],
        ['c' => 'TimeField', 'm' => 'tidyInternal', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $time from dynamic to mixed'],

        // UserDefinedFormController
        // ['c' => 'UserDefinedFormController', 'm' => 'index', 'p' => 0, 't' => 'HTTPRequest|null', 'n' => 'Changed type of parameter $request from HTTPRequest to HTTPRequest|null'],

        // VerifyHandler
        // ['c' => 'VerifyHandler', 'm' => 'getCredentialRequestOptions', 'p' => 0, 't' => 'RegisteredMethod|null', 'n' => 'Changed type of parameter $registeredMethod from RegisteredMethod to RegisteredMethod|null'],

        // Versioned
        ['c' => 'Versioned', 'm' => 'augmentLoadLazyFields', 'p' => 0, 't' => 'DataQuery|null', 'n' => 'Changed type of parameter $dataQuery from DataQuery to DataQuery|null'],
        ['c' => 'Versioned', 'm' => 'get_by_stage', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $class from dynamic to string'],
        ['c' => 'Versioned', 'm' => 'get_by_stage', 'p' => 1, 't' => 'string', 'n' => 'Changed type of parameter $stage from dynamic to string'],
        ['c' => 'Versioned', 'm' => 'get_by_stage', 'p' => 2, 't' => 'string|array', 'n' => 'Changed type of parameter $filter from dynamic to string|array'],
        // ['c' => 'Versioned', 'm' => 'get_by_stage', 'p' => 3, 't' => 'string|array|null', 'n' => 'Changed type of parameter $sort from dynamic to string|array|null'],
        ['c' => 'Versioned', 'm' => 'get_by_stage', 'p' => 4, 't' => 'string', 'n' => 'Changed type of parameter $containerClass from dynamic to string'],
        // ['c' => 'Versioned', 'm' => 'get_by_stage', 'p' => 5, 't' => 'string|array|null', 'n' => 'Changed type of parameter $limit from dynamic to string|array|null'],
        ['c' => 'Versioned', 'm' => 'get_including_deleted', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $class from dynamic to string'],
        ['c' => 'Versioned', 'm' => 'get_including_deleted', 'p' => 1, 't' => 'string|array', 'n' => 'Changed type of parameter $filter from dynamic to string|array'],
        ['c' => 'Versioned', 'm' => 'get_including_deleted', 'p' => 2, 't' => 'string', 'n' => 'Changed type of parameter $sort from dynamic to string'],

        // VirtualPage
        ['c' => 'VirtualPage', 'm' => '__get', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $field from dynamic to string'],
        ['c' => 'VirtualPage', 'm' => 'castingHelper', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $field from dynamic to string'],
        ['c' => 'VirtualPage', 'm' => 'getField', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $field from dynamic to string'],
        ['c' => 'VirtualPage', 'm' => 'getViewerTemplates', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $suffix from dynamic to string'],
        ['c' => 'VirtualPage', 'm' => 'hasField', 'p' => 0, 't' => 'string', 'n' => 'Changed type of parameter $field from dynamic to string'],

        // WithinRangeFilter
        ['c' => 'WithinRangeFilter', 'm' => 'setMax', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $max from dynamic to mixed'],
        ['c' => 'WithinRangeFilter', 'm' => 'setMin', 'p' => 0, 't' => 'mixed', 'n' => 'Changed type of parameter $min from dynamic to mixed'],

        // WorkflowInstance
        // ['c' => 'WorkflowInstance', 'm' => 'beginWorkflow', 'p' => 0, 't' => 'DataObject|null', 'n' => 'Changed type of parameter $for from DataObject to DataObject|null'],

        // WorkflowTemplate
        // ['c' => 'WorkflowTemplate', 'm' => 'createAction', 'p' => 0, 't' => 'WorkflowDefinition|null', 'n' => 'Changed type of parameter $definition from WorkflowDefinition to WorkflowDefinition|null'],

        // i18nTextCollector
        // ['c' => 'i18nTextCollector', 'm' => 'collectFromEntityProviders', 'p' => 0, 't' => 'Module|null', 'n' => 'Changed type of parameter $module from Module to Module|null'],

        // i18nTextCollectorTask
        ['c' => 'i18nTextCollectorTask', 'm' => 'getIsMerge', 'p' => 0, 't' => 'Symfony\\Component\\Console\\Input\\InputInterface', 'n' => 'Changed type of parameter $request from dynamic to Symfony\\Component\\Console\\Input\\InputInterface'],
    ];
}
