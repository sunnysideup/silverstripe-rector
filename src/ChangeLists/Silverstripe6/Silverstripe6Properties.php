<?php
// [silverstripe/admin#] => Array
//                 (
//                     [0] => Removed deprecated property SilverStripe\Admin\LeftAndMain->pageID - renamed to recordID.
//                     [1] => Removed deprecated property SilverStripe\Admin\LeftAndMain->schema - made private.
//                     [2] => Removed deprecated property SilverStripe\Admin\ModalController->controller - removed without equivalent functionality to replace it
//                     [3] => Removed deprecated property SilverStripe\Admin\ModalController->name - removed without equivalent functionality to replace it
//                 )

//             [silverstripe/cms#] => Array
//                 (
//                     [0] => Removed deprecated property SilverStripe\CMS\Controllers\CMSSiteTreeFilter->_cache_expanded - removed without equivalent functionality to replace it
//                     [1] => Removed deprecated property SilverStripe\CMS\Controllers\CMSSiteTreeFilter->_cache_highlight_ids - removed without equivalent functionality to replace it
//                     [2] => Removed deprecated property SilverStripe\CMS\Controllers\CMSSiteTreeFilter->_cache_ids - removed without equivalent functionality to replace it
//                     [3] => Removed deprecated property SilverStripe\CMS\Controllers\CMSSiteTreeFilter->childrenMethod - removed without equivalent functionality to replace it
//                     [4] => Removed deprecated property SilverStripe\CMS\Controllers\CMSSiteTreeFilter->numChildrenMethod - removed without equivalent functionality to replace it
//                     [5] => Removed deprecated property SilverStripe\CMS\Controllers\CMSSiteTreeFilter->params - removed without equivalent functionality to replace it
//                     [6] => Removed deprecated property SilverStripe\CMS\Model\SiteTree->_allowedChildren - moved to Hierarchy.>cache_allowedChildren
//                     [7] => Removed deprecated property SilverStripe\CMS\Model\SiteTree->_cache_statusFlags - moved to ModelData and made private
//                     [8] => Removed deprecated property SilverStripe\CMS\Model\SiteTree->creatableChildrenCache - moved to CMSMain and made private
//                 )

//             [silverstripe/framework#] => Array
//                 (
//                     [0] => Removed deprecated property SilverStripe\Core\Cache\ApcuCacheFactory->version - replaced with a key in the $params argument
//                     [1] => Removed deprecated property SilverStripe\Core\Cache\MemcachedCacheFactory->memcachedClient - replaced with setting the SS_MEMCACHED_DSN environment variable
//                     [2] => Removed deprecated property SilverStripe\Dev\BuildTask->enabled - use the is_enabled configuration property instead.
//                     [3] => Removed deprecated property SilverStripe\Dev\Constraint\SSListContains->exporter - removed without equivalent functionality to replace it
//                     [4] => Removed deprecated property SilverStripe\Dev\Constraint\SSListContainsOnlyMatchingItems->exporter - removed without equivalent functionality to replace it
//                     [5] => Removed deprecated property SilverStripe\Forms\DateField->rawValue - use $value instead
//                     [6] => Removed deprecated property SilverStripe\Forms\DatetimeField->rawValue - use $value instead
//                     [7] => Removed deprecated property SilverStripe\Forms\FormScaffolder->ajaxSafe - removed without equivalent functionality
//                     [8] => Removed deprecated property SilverStripe\Forms\GridField\GridFieldFilterHeader->throwExceptionOnBadDataType - removed without equivalent functionality
//                     [9] => Removed deprecated property SilverStripe\Forms\GridField\GridFieldPaginator->throwExceptionOnBadDataType - removed without equivalent functionality
//                     [10] => Removed deprecated property SilverStripe\Forms\GridField\GridFieldSortableHeader->throwExceptionOnBadDataType - removed without equivalent functionality
//                     [11] => Removed deprecated property SilverStripe\Forms\HTMLEditor\HTMLEditorSanitiser->elementPatterns - replaced with HTMLEditorRuleSet
//                     [12] => Removed deprecated property SilverStripe\Forms\HTMLEditor\HTMLEditorSanitiser->elements - replaced with HTMLEditorRuleSet
//                     [13] => Removed deprecated property SilverStripe\Forms\HTMLEditor\HTMLEditorSanitiser->globalAttributes - replaced with HTMLEditorRuleSet
//                     [14] => Removed deprecated property SilverStripe\Forms\TimeField->rawValue - use $value instead
//                     [15] => Removed deprecated property SilverStripe\ORM\FieldType\DBDecimal->defaultValue - replaced with getDefaultValue() and setDefaultValue()
//                     [16] => Removed deprecated property SilverStripe\ORM\FieldType\DBField->defaultVal - use getDefaultValue() and setDefaultValue() instead
//                     [17] => Removed deprecated property SilverStripe\ORM\FieldType\DBForeignKey->foreignListCache - removed without equivalent functionality to replace it
//                     [18] => Removed deprecated property SilverStripe\View\SSViewer->chosen - moved to SSTemplateEngine
//                     [19] => Removed deprecated property SilverStripe\View\SSViewer->parser - moved to SSTemplateEngine
//                     [20] => Removed deprecated property SilverStripe\View\SSViewer->partialCacheStore - moved to SSTemplateEngine
//                     [21] => Removed deprecated property SilverStripe\View\SSViewer->subTemplates - moved to SSTemplateEngine
//                     [22] => Removed deprecated property SilverStripe\View\SSViewer->templates - moved to SSTemplateEngine
//                     [23] => Removed deprecated property SilverStripe\View\SSViewer->topLevel - moved to SSTemplateEngine
//                 )

//             [silverstripe/userforms#] => Array
//                 (
//                     [0] => Removed deprecated property SilverStripe\UserForms\UserForm->fieldsFromTo - removed without equivalent functionality to replace it
//                 )



    // [Changed type of property] => Array
    //     (
    //         [silverstripe/asset-admin#] => Array
    //             (
    //                 [0] => Changed type of property AssetAdmin->thumbnailGenerator from dynamic to ThumbnailGenerator
    //             )

    //         [silverstripe/assets#] => Array
    //             (
    //                 [0] => Changed type of property DBFile->allowedCategories from dynamic to array
    //             )

    //         [silverstripe/cms#] => Array
    //             (
    //                 [0] => Changed type of property CMSMain->hintsCache from dynamic to Psr\SimpleCache\CacheInterface|null
    //             )

    //         [silverstripe/framework#] => Array
    //             (
    //                 [0] => Changed type of property BuildTask->description from dynamic to string
    //                 [1] => Changed type of property BuildTask->title from dynamic to string
    //                 [2] => Changed type of property ConfirmedPasswordField->canBeEmpty from dynamic to bool
    //                 [3] => Changed type of property ConfirmedPasswordField->children from dynamic to FieldList
    //                 [4] => Changed type of property ConfirmedPasswordField->confirmPasswordfield from dynamic to PasswordField|null
    //                 [5] => Changed type of property ConfirmedPasswordField->confirmValue from dynamic to string|null
    //                 [6] => Changed type of property ConfirmedPasswordField->currentPasswordValue from dynamic to string|null
    //                 [7] => Changed type of property ConfirmedPasswordField->hiddenField from dynamic to HiddenField|null
    //                 [8] => Changed type of property ConfirmedPasswordField->maxLength from dynamic to int
    //                 [9] => Changed type of property ConfirmedPasswordField->minLength from dynamic to int
    //                 [10] => Changed type of property ConfirmedPasswordField->passwordField from dynamic to PasswordField|null
    //                 [11] => Changed type of property ConfirmedPasswordField->requireExistingPassword from dynamic to bool
    //                 [12] => Changed type of property ConfirmedPasswordField->requireStrongPassword from dynamic to bool
    //                 [13] => Changed type of property ConfirmedPasswordField->showOnClickTitle from dynamic to string
    //                 [14] => Changed type of property ConfirmedPasswordField->showOnClick from dynamic to bool
    //                 [15] => Changed type of property CookieJar->current from dynamic to array
    //                 [16] => Changed type of property CookieJar->existing from dynamic to array
    //                 [17] => Changed type of property CookieJar->new from dynamic to array
    //                 [18] => Changed type of property DBClassNameTrait->baseClass from dynamic to string|null
    //                 [19] => Changed type of property DBClassNameTrait->record from dynamic to DataObject|null
    //                 [20] => Changed type of property DBComposite->isChanged from dynamic to bool
    //                 [21] => Changed type of property DBComposite->record from dynamic to array|ModelData
    //                 [22] => Changed type of property DBDatetime->immutable from dynamic to bool
    //                 [23] => Changed type of property DBDatetime->mock_now from dynamic to DBDatetime|null
    //                 [24] => Changed type of property DBDecimal->decimalSize from dynamic to int
    //                 [25] => Changed type of property DBDecimal->wholeSize from dynamic to int
    //                 [26] => Changed type of property DBEnum->default from dynamic to string|null
    //                 [27] => Changed type of property DBEnum->enum_cache from dynamic to array
    //                 [28] => Changed type of property DBEnum->enum from dynamic to array
    //                 [29] => Changed type of property DBField->name from dynamic to string|null
    //                 [30] => Changed type of property DBField->options from dynamic to array
    //                 [31] => Changed type of property DBField->tableName from dynamic to string|null
    //                 [32] => Changed type of property DBField->value from dynamic to mixed
    //                 [33] => Changed type of property DBForeignKey->object from dynamic to DataObject|null
    //                 [34] => Changed type of property DBHTMLText->processShortcodes from dynamic to bool
    //                 [35] => Changed type of property DBHTMLText->whitelist from dynamic to array
    //                 [36] => Changed type of property DBHTMLVarchar->processShortcodes from dynamic to bool
    //                 [37] => Changed type of property DBMoney->locale from dynamic to string|null
    //                 [38] => Changed type of property DBPrimaryKey->autoIncrement from dynamic to bool
    //                 [39] => Changed type of property DBPrimaryKey->object from dynamic to DataObject|null
    //                 [40] => Changed type of property DBVarchar->size from dynamic to int
    //                 [41] => Changed type of property DefaultCacheFactory->args from dynamic to array
    //                 [42] => Changed type of property DefaultCacheFactory->logger from dynamic to Psr\Log\LoggerInterface|null
    //                 [43] => Changed type of property FieldList->containerField from dynamic to CompositeField|null
    //                 [44] => Changed type of property FieldList->sequentialSaveableSet from dynamic to array
    //                 [45] => Changed type of property FieldList->sequentialSet from dynamic to array
    //                 [46] => Changed type of property FormScaffolder->fieldClasses from dynamic to array
    //                 [47] => Changed type of property FormScaffolder->includeRelations from dynamic to bool|array
    //                 [48] => Changed type of property FormScaffolder->obj from dynamic to DataObject
    //                 [49] => Changed type of property FormScaffolder->restrictFields from dynamic to array
    //                 [50] => Changed type of property FormScaffolder->tabbed from dynamic to bool
    //                 [51] => Changed type of property HTMLEditorConfig->configs from dynamic to array
    //                 [52] => Changed type of property HTMLEditorConfig->current_themes from dynamic to array
    //                 [53] => Changed type of property HTMLEditorConfig->current from dynamic to string
    //                 [54] => Changed type of property Hierarchy->cache_numChildren from dynamic to array
    //                 [55] => Changed type of property SSViewer->current_rewrite_hash_links from dynamic to null|bool|string
    //                 [56] => Changed type of property SSViewer->current_themes from dynamic to array
    //                 [57] => Changed type of property SSViewer->includeRequirements from dynamic to bool
    //                 [58] => Changed type of property SSViewer->rewriteHashlinks from dynamic to null|bool|string
    //             )

    //         [tractorcow/silverstripe-fluent#] => Array
    //             (
    //                 [0] => Changed type of property LocalDateTime->timezone from dynamic to string|null
    //             )

    //     )




    // [Changed default config value] => Array
    //     (
    //         [silverstripe/admin#] => Array
    //             (
    //                 [0] => Changed default value for config LeftAndMain.dependencies - array values have changed
    //                 [1] => Changed default value for config LeftAndMain.help_links - array values have changed
    //                 [2] => Changed default value for config LeftAndMain.required_permission_codes from null to []
    //                 [3] => Changed default value for config SudoModeController.help_link from 'https://userhelp.silverstripe.org/en/5/managing_your_website/logging_in/#sudo-mode' to 'https://userhelp.silverstripe.org/en/6/managing_your_website/logging_in/#sudo-mode'
    //             )

    //         [silverstripe/cms#] => Array
    //             (
    //                 [0] => Changed default value for config SiteTree.allowed_children - array values have changed
    //                 [1] => Changed default value for config SiteTree.default_child from 'Page' to Page
    //             )

    //         [silverstripe/framework#] => Array
    //             (
    //                 [0] => Changed default value for config DataObject.scaffold_cms_fields_settings - array values have changed
    //                 [1] => Changed default value for config BuildTask.is_enabled from null to true
    //                 [2] => Changed default value for config MySQLDatabase.charset from 'utf8' to 'utf8mb4'
    //                 [3] => Changed default value for config MySQLDatabase.collation from 'utf8_general_ci' to 'utf8mb4_unicode_ci'
    //                 [4] => Changed default value for config MySQLDatabase.connection_charset from 'utf8' to 'utf8mb4'
    //                 [5] => Changed default value for config MySQLDatabase.connection_collation from 'utf8_general_ci' to 'utf8mb4_unicode_ci'
    //                 [6] => Changed default value for config Session.cookie_samesite from Cookie::SAMESITE_LAX to Cookie::SAMESITE_STRICT
    //                 [7] => Changed default value for config Session.cookie_secure from false to true
    //             )

    //         [silverstripe/mfa#] => Array
    //             (
    //                 [0] => Changed default value for config LoginHandler.user_help_link from 'https://userhelp.silverstripe.org/en/5/optional_features/multi-factor_authentication/' to 'https://userhelp.silverstripe.org/en/6/optional_features/multi-factor_authentication/'
    //                 [1] => Changed default value for config RegisterHandler.user_help_link from 'https://userhelp.silverstripe.org/en/5/optional_features/multi-factor_authentication/user_manual/regaining_access/' to 'https://userhelp.silverstripe.org/en/6/optional_features/multi-factor_authentication/user_manual/regaining_access/'
    //                 [2] => Changed default value for config SiteConfigExtension.mfa_help_link from 'https://userhelp.silverstripe.org/en/5/optional_features/multi-factor_authentication/' to 'https://userhelp.silverstripe.org/en/6/optional_features/multi-factor_authentication/'
    //             )

    //         [silverstripe/session-manager#] => Array
    //             (
    //                 [0] => Changed default value for config MemberExtension.session_login_help_url from 'https://userhelp.silverstripe.org/en/5/managing_your_website/session_manager' to 'https://userhelp.silverstripe.org/en/6/managing_your_website/session_manager'
    //             )

    //         [silverstripe/subsites#] => Array
    //             (
    //                 [0] => Changed default value for config InitStateMiddleware.admin_url_paths - array values have changed
    //             )

    //         [silverstripe/totp-authenticator#] => Array
    //             (
    //                 [0] => Changed default value for config RegisterHandler.user_help_link from 'https://userhelp.silverstripe.org/en/5/optional_features/multi-factor_authentication/user_manual/using_authenticator_apps/' to 'https://userhelp.silverstripe.org/en/6/optional_features/multi-factor_authentication/user_manual/using_authenticator_apps/'
    //             )

    //         [silverstripe/userforms#] => Array
    //             (
    //                 [0] => Changed default value for config UserForm.has_many - array values have changed
    //             )

    //         [symbiote/silverstripe-queuedjobs#] => Array
    //             (
    //                 [0] => Changed default value for config DoormanRunner.child_runner from 'ProcessJobQueueChildTask' to 'queuedjobs:process-queue-child'
    //             )

    //     )