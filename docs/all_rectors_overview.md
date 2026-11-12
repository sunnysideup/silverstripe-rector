# 66 Rules Overview

<br>

## Categories

- [Rector](#rector) (66)

<br>

## Rector

### AddConfigPropertiesRector

Code Style: Adds `@config` property to predefined private statics, e.g. `$db` or `$allowed_actions`

:wrench: **configure it!**

- class: [`Netwerkstatt\SilverstripeRector\Rector\Misc\AddConfigPropertiesRector`](../scripts/../src/Rector/Misc/AddConfigPropertiesRector.php)

```diff
@@ -1,4 +1,7 @@
 class SomeClass extends \SilverStripe\ORM\DataObject
 {
+    /**
+    * @config
+    */
     private static $db = [];
 }
```

<br>

### AddNewParameter

Adds TODO upgrade comments for method calls/overrides where a new parameter was added.

:wrench: **configure it!**

- class: [`Netwerkstatt\SilverstripeRector\Rector\Methods\AddNewParameter`](../scripts/../src/Rector/Methods/AddNewParameter.php)

```diff
@@ -1,1 +1,2 @@
+/** @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Added new parameter $output in BuildTask::run() */
 $service->run($request);
```

<br>

### AddParentConstructToControllerRector

Adds missing `parent::__construct()` call to Controller subclasses

- class: [`Netwerkstatt\SilverstripeRector\Rector\Control\AddParentConstructToControllerRector`](../scripts/../src/Rector/Control/AddParentConstructToControllerRector.php)

```diff
@@ -1,7 +1,8 @@
 class PropertyHolderController extends Controller
 {
     public function __construct(?PropertyHolder $model = null)
     {
+        parent::__construct($model);
         $this->locatable = $this;
     }
 }
```

<br>

### AddScaffoldCmsFieldsSettingsRector

Adds `$scaffold_cms_fields_settings` to SiteTree subclasses and Extensions, auto-populating ignoreFields and ignoreRelations.

- class: [`Netwerkstatt\SilverstripeRector\Rector\CMS\AddScaffoldCmsFieldsSettingsRector`](../scripts/../src/Rector/CMS/AddScaffoldCmsFieldsSettingsRector.php)

```diff
@@ -1,4 +1,16 @@
 class BlogPage extends \SilverStripe\CMS\Model\SiteTree {
     private static $db = ['Author' => 'Varchar'];
     private static $has_one = ['HeroImage' => 'Image'];
+    /**
+     * This property is used by the Rector upgrader to manage CMS field scaffolding.
+     * Manual modifications to the array values are permitted, but the property should remain defined.
+     * @see https://github.com/wernerkrauss/silverstripe-rector
+     */
+    private static array $scaffold_cms_fields_settings = [
+        'ignoreFields' => ['Author'],
+        'includeRelations' => [],
+        'restrictRelations' => [],
+        'ignoreRelations' => ['HeroImage'],
+        'restrictFields' => [],
+    ];
 }
```

<br>

### AddUnionReturnTypeToPrepValueForDBRector

Adds array|string|null return type to `prepValueForDB()` on DBString subclasses

- class: [`Netwerkstatt\SilverstripeRector\Rector\ORM\AddUnionReturnTypeToPrepValueForDBRector`](../scripts/../src/Rector/ORM/AddUnionReturnTypeToPrepValueForDBRector.php)

```diff
@@ -1,7 +1,7 @@
 class EmailAddress extends DBString
 {
-    public function prepValueForDB(mixed $value): mixed
+    public function prepValueForDB(mixed $value): array|string|null
     {
         return $value;
     }
 }
```

<br>

### AddVoidReturnTypeToSaveIntoRector

Adds void return type to `saveInto()` on DBField subclasses

- class: [`Netwerkstatt\SilverstripeRector\Rector\ORM\AddVoidReturnTypeToSaveIntoRector`](../scripts/../src/Rector/ORM/AddVoidReturnTypeToSaveIntoRector.php)

```diff
@@ -1,7 +1,7 @@
 class MyField extends DBField
 {
-    public function saveInto($dataObject)
+    public function saveInto($dataObject): void
     {
         // save logic
     }
 }
```

<br>

### BuildTaskDescriptionPropertyRector

Ensures the `$description` property on BuildTask classes is protected, static, and typed as string.

- class: [`Netwerkstatt\SilverstripeRector\Rector\BuildTask\BuildTaskDescriptionPropertyRector`](../scripts/../src/Rector/BuildTask/BuildTaskDescriptionPropertyRector.php)

```diff
@@ -1,6 +1,6 @@
 use SilverStripe\Dev\BuildTask;

 class MyTask extends BuildTask
 {
-    private $description = 'My description';
+    protected static string $description = 'My description';
 }
```

<br>

### BuildTaskIsEnabledPropertyRector

Renames `$enabled` to `$is_enabled` on BuildTask and ensures it is private static bool.

- class: [`Netwerkstatt\SilverstripeRector\Rector\BuildTask\BuildTaskIsEnabledPropertyRector`](../scripts/../src/Rector/BuildTask/BuildTaskIsEnabledPropertyRector.php)

```diff
@@ -1,6 +1,6 @@
 use SilverStripe\Dev\BuildTask;

 class MyTask extends BuildTask
 {
-    private $enabled = false;
+    private static bool $is_enabled = false;
 }
```

<br>

### BuildTaskSegmentToCommandNameRector

Changes BuildTask `$segment` to `$commandName` with protected static string signature

- class: [`Netwerkstatt\SilverstripeRector\Rector\BuildTask\BuildTaskSegmentToCommandNameRector`](../scripts/../src/Rector/BuildTask/BuildTaskSegmentToCommandNameRector.php)

```diff
@@ -1,3 +1,3 @@
 class MyTask extends \SilverStripe\Dev\BuildTask {
-    private static $segment = 'my-task';
+    protected static string $commandName = 'my-task';
 }
```

<br>

### BuildTaskTitlePropertyRector

Ensures the `$title` property on BuildTask classes is protected and typed as string. 
            See https://docs.silverstripe.org/en/6/developer_guides/cli/polycommand/#buildtask

- class: [`Netwerkstatt\SilverstripeRector\Rector\BuildTask\BuildTaskTitlePropertyRector`](../scripts/../src/Rector/BuildTask/BuildTaskTitlePropertyRector.php)

```diff
@@ -1,4 +1,4 @@
 class MyTask extends \SilverStripe\Dev\BuildTask
 {
-    private $title = 'My Title';
+    protected string $title = 'My Title';
 }
```

<br>

### BuildTaskToExecuteRector

Changes BuildTask `run()` to protected `execute()` with proper CLI types

- class: [`Netwerkstatt\SilverstripeRector\Rector\BuildTask\BuildTaskToExecuteRector`](../scripts/../src/Rector/BuildTask/BuildTaskToExecuteRector.php)

```diff
@@ -1,5 +1,7 @@
 class MyTask extends \SilverStripe\Dev\BuildTask {
-    public function run($request) {
+    protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \SilverStripe\PolyExecution\PolyOutput $output): int
+    {
         echo "done";
+        return 0;
     }
 }
```

<br>

### BuildTaskUpdateRector

Silverstripe 6.0: Updates Silverstripe BuildTask from v5 to v6

- class: [`Netwerkstatt\SilverstripeRector\Rector\Misc\BuildTaskUpdateRector`](../scripts/../src/Rector/Misc/BuildTaskUpdateRector.php)

```diff
@@ -1,11 +1,14 @@
 use SilverStripe\Dev\BuildTask;
+use Symfony\Component\Console\Input\InputInterface;
+use SilverStripe\PolyExecution\PolyOutput;

 class MyTask extends BuildTask
 {
-    protected $title = 'My Task';
+    protected string $title = 'My Task';

-    public function run($request)
+    protected function execute(InputInterface $input, PolyOutput $output): int
     {
-        echo "Running task";
+        $output->writeln('Running task');
+        return \Symfony\Component\Console\Command\Command::SUCCESS;
     }
 }
```

<br>

### ChangedDefaultParameterValue

Adds TODO upgrade comments for method calls/overrides where a parameter default value changed.

:wrench: **configure it!**

- class: [`Netwerkstatt\SilverstripeRector\Rector\Methods\ChangedDefaultParameterValue`](../scripts/../src/Rector/Methods/ChangedDefaultParameterValue.php)

```diff
@@ -1,1 +1,2 @@
+/** @TODO SSU RECTOR UPGRADE TASK - SomeClass::doThing: Changed default value of parameter $mode in SomeClass::doThing() from null to 'strict' */
 $service->doThing($value);
```

<br>

### ChangedParameterType

Adds TODO upgrade comments for method calls/overrides where a parameter type changed, but only if the current argument/parameter does not already match the new type.

:wrench: **configure it!**

- class: [`Netwerkstatt\SilverstripeRector\Rector\Methods\ChangedParameterType`](../scripts/../src/Rector/Methods/ChangedParameterType.php)

```diff
@@ -1,19 +1,20 @@
 use SilverStripe\Dev\BuildTask;
 use Symfony\Component\Console\Output\OutputInterface;

 class MyTask extends BuildTask
 {
     // Old signature: public function run($request)
     // New signature: public function run(HTTPRequest $request)

+    // @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Changed parameter type from mixed to HTTPRequest for $request
     public function run($request)
     {
         // ...
     }
 }

-// This will get flagged - passing wrong type
+// @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Changed parameter type from mixed to HTTPRequest for $request
 $task->run($_GET);

 // This will NOT get flagged - already passing correct type
 $task->run($httpRequest);
```

<br>

### ConvertArrayToInputOptionRector

Convert array option definitions to InputOption objects in `getOptions()`. 
            See https://docs.silverstripe.org/en/6/developer_guides/cli/polycommand/#buildtask

- class: [`Netwerkstatt\SilverstripeRector\Rector\Console\ConvertArrayToInputOptionRector`](../scripts/../src/Rector/Console/ConvertArrayToInputOptionRector.php)

```diff
@@ -1,6 +1,6 @@
 public function getOptions(): array
 {
     return [
-        ['limit', 'l', InputOption::VALUE_OPTIONAL, 'Limit number of examples']
+        new \Symfony\Component\Console\Input\InputOption('limit', 'l', InputOption::VALUE_OPTIONAL, 'Limit number of examples')
     ];
 }
```

<br>

### DBCompositeWriteToManipulationSignatureRector

Ensures `writeToManipulation()` method on classes extending DBComposite has the signature: writeToManipulation(array &$manipulation): void

- class: [`Netwerkstatt\SilverstripeRector\Rector\ORM\DBCompositeWriteToManipulationSignatureRector`](../scripts/../src/Rector/ORM/DBCompositeWriteToManipulationSignatureRector.php)

```diff
@@ -1,6 +1,6 @@
 class MyComposite extends \SilverStripe\ORM\FieldType\DBComposite
 {
-    public function writeToManipulation($manipulation)
+    public function writeToManipulation(array &$manipulation): void
     {
     }
 }
```

<br>

### DBFieldAddToQuerySignatureRector

Ensures `addToQuery()` method on classes extending DBField has the signature: public function addToQuery(SQLSelect &$query)

- class: [`Netwerkstatt\SilverstripeRector\Rector\ORM\DBFieldAddToQuerySignatureRector`](../scripts/../src/Rector/ORM/DBFieldAddToQuerySignatureRector.php)

```diff
@@ -1,6 +1,6 @@
 class MyField extends \SilverStripe\ORM\FieldType\DBField
 {
-    protected function addToQuery($query)
+    public function addToQuery(\SilverStripe\ORM\Queries\SQLSelect &$query)
     {
     }
 }
```

<br>

### DBFieldScaffoldFormFieldSignatureRector

Ensures `scaffoldFormField()` method on classes extending DBField has the correct signature

- class: [`Netwerkstatt\SilverstripeRector\Rector\ORM\DBFieldScaffoldFormFieldSignatureRector`](../scripts/../src/Rector/ORM/DBFieldScaffoldFormFieldSignatureRector.php)

```diff
@@ -1,7 +1,7 @@
 class MyField extends \SilverStripe\ORM\FieldType\DBField
 {
-    public function scaffoldFormField($title = null, $params = null)
+    public function scaffoldFormField(?string $title = null, array $params = []): ?\SilverStripe\Forms\FormField
     {
         return null;
     }
 }
```

<br>

### DBFieldSetValueSignatureRector

Ensures `setValue()` method on classes extending DBField has the correct PHP 8 signature

- class: [`Netwerkstatt\SilverstripeRector\Rector\ORM\DBFieldSetValueSignatureRector`](../scripts/../src/Rector/ORM/DBFieldSetValueSignatureRector.php)

```diff
@@ -1,7 +1,7 @@
 class MyField extends \SilverStripe\ORM\FieldType\DBField
 {
-    public function setValue($value, $record = null, $markChanged = true)
+    public function setValue(mixed $value, null|array|ModelData $record = null, bool $markChanged = true): static
     {
         return $this;
     }
 }
```

<br>

### DBVarcharURLReturnTypeRector

Changes return type of `URL()` methods to ?string for classes extending DBVarchar

- class: [`Netwerkstatt\SilverstripeRector\Rector\ORM\DBVarcharURLReturnTypeRector`](../scripts/../src/Rector/ORM/DBVarcharURLReturnTypeRector.php)

```diff
@@ -1,7 +1,7 @@
 class MyVarchar extends \SilverStripe\ORM\FieldType\DBVarchar
 {
-    public function URL()
+    public function URL(): ?string
     {
         return 'https://example.com';
     }
 }
```

<br>

### DataObjectDebugReturnTypeRector

Changes DataObject `debug()` method to public with string return type

- class: [`Netwerkstatt\SilverstripeRector\Rector\DataObject\DataObjectDebugReturnTypeRector`](../scripts/../src/Rector/DataObject/DataObjectDebugReturnTypeRector.php)

```diff
@@ -1,5 +1,5 @@
 class MyObject extends \SilverStripe\ORM\DataObject {
-    protected function debug() {
+    public function debug(): string {
         return "Debug info";
     }
 }
```

<br>

### DataObjectGetByIdToByIDRector

Code Style: Changes DataObject::get_by_id('ClassName', `$id)` to `ClassName::get()->byID($id)`

- class: [`Netwerkstatt\SilverstripeRector\Rector\DataObject\DataObjectGetByIdToByIDRector`](../scripts/../src/Rector/DataObject/DataObjectGetByIdToByIDRector.php)

```diff
@@ -1,1 +1,1 @@
-DataObject::get_by_id('MyPage', $id);
+MyPage::get()->byID($id);
```

<br>

### DataObjectGetToClassGetRector

Converts DataObject::get(SomeClass::class) to `SomeClass::get()`

- class: [`Netwerkstatt\SilverstripeRector\Rector\ORM\DataObjectGetToClassGetRector`](../scripts/../src/Rector/ORM/DataObjectGetToClassGetRector.php)

```diff
@@ -1,2 +1,2 @@
-$steps = \SilverStripe\ORM\DataObject::get(OrderStep::class);
-$more = \SilverStripe\ORM\DataObject::get('My\Custom\Class');
+$steps = OrderStep::get();
+$more = \My\Custom\Class::get();
```

<br>

### DataObjectStaticMethodsToFluentRector

Silverstripe 6.1: Replace DataObject static methods with fluent equivalents using Late Static Binding.

- class: [`Netwerkstatt\SilverstripeRector\Rector\DataObject\DataObjectStaticMethodsToFluentRector`](../scripts/../src/Rector/DataObject/DataObjectStaticMethodsToFluentRector.php)

```diff
@@ -1,3 +1,3 @@
-DataObject::get_by_id(MyClass::class, 1);
-DataObject::get_one(MyClass::class, ['Email' => $email]);
-DataObject::delete_by_id(MyClass::class, 1);
+MyClass::get()->byID(1);
+MyClass::get()->setUseCache(true)->filter(['Email' => $email])->first();
+MyClass::get()->byID(1)->delete();
```

<br>

### EnsureExecuteReturnsCommandSuccessRector

Ensures BuildTask `execute()` methods always finish by returning Command::SUCCESS

- class: [`Netwerkstatt\SilverstripeRector\Rector\BuildTask\EnsureExecuteReturnsCommandSuccessRector`](../scripts/../src/Rector/BuildTask/EnsureExecuteReturnsCommandSuccessRector.php)

```diff
@@ -1,5 +1,5 @@
 protected function execute(InputInterface $input, PolyOutput $output): int
 {
     $this->doSomething();
-    return true;
+    return \Symfony\Component\Console\Command\Command::SUCCESS;
 }
```

<br>

### EnsureTableNameIsSetRector

Silverstripe 4.0: DataObject subclasses must have "$table_name" defined

- class: [`Netwerkstatt\SilverstripeRector\Rector\DataObject\EnsureTableNameIsSetRector`](../scripts/../src/Rector/DataObject/EnsureTableNameIsSetRector.php)

```diff
@@ -1,4 +1,6 @@
 class SomeClass extends \SilverStripe\ORM\DataObject
 {
+    private static $table_name = 'SomeClass';
+
     private static $db = [];
 }
```

<br>

### FactoryCreateMethodSignatureRector

Ensures `create()` method on Injector Factories is public, has correct parameters, and returns ?object

- class: [`Netwerkstatt\SilverstripeRector\Rector\Injector\FactoryCreateMethodSignatureRector`](../scripts/../src/Rector/Injector/FactoryCreateMethodSignatureRector.php)

```diff
@@ -1,7 +1,7 @@
 class MyFactory implements \SilverStripe\Core\Injector\Factory
 {
-    protected function create($service, $params)
+    public function create($service, array $params = []): ?object
     {
         return new \stdClass();
     }
 }
```

<br>

### FormFieldCompositeDatabaseFieldsReturnTypeRector

Changes FormField `compositeDatabaseFields()` to public with array return type

- class: [`Netwerkstatt\SilverstripeRector\Rector\Forms\FormFieldCompositeDatabaseFieldsReturnTypeRector`](../scripts/../src/Rector/Forms/FormFieldCompositeDatabaseFieldsReturnTypeRector.php)

```diff
@@ -1,5 +1,5 @@
 class MyField extends \SilverStripe\Forms\FormField {
-    protected function compositeDatabaseFields() {
+    public function compositeDatabaseFields(): array {
         return ['Field1' => 'Varchar'];
     }
 }
```

<br>

### FormFieldValidateSignatureRector

Migrates `validate()` method on FormField to return ValidationResult and use `addError()`. 
            See https://docs.silverstripe.org/en/6/changelogs/6.0.0/#formfield-validation

- class: [`Netwerkstatt\SilverstripeRector\Rector\Forms\FormFieldValidateSignatureRector`](../scripts/../src/Rector/Forms/FormFieldValidateSignatureRector.php)

```diff
@@ -1,12 +1,11 @@
 class MyField extends \SilverStripe\Forms\FormField
 {
-    public function validate($validator)
+    public function validate(): \SilverStripe\ORM\ValidationResult
     {
-        $valid = parent::validate($validator);
+        $valid = parent::validate();
         if (true) {
-            $validator->validationError($this->name, 'Not unique', 'validation');
-            $valid = false;
+            $valid->addError('Not unique', 'validation');
         }
         return $valid;
     }
 }
```

<br>

### FormFieldValueToGetValueRector

Renames `Value()` and `value()` methods to `getValue()`: mixed for classes extending FormField. 
            See https://docs.silverstripe.org/en/6/changelogs/6.0.0/#formfield-value

- class: [`Netwerkstatt\SilverstripeRector\Rector\Forms\FormFieldValueToGetValueRector`](../scripts/../src/Rector/Forms/FormFieldValueToGetValueRector.php)

```diff
@@ -1,10 +1,10 @@
 class MyField extends \SilverStripe\Forms\FormField
 {
-    protected function Value()
+    public function getValue(): mixed
     {
         return 'test';
     }
 }

 $field = new MyField('Test');
-$val = $field->value();
+$val = $field->getValue();
```

<br>

### FormLoadDataFromMergeStrategyRector

Silverstripe 5.4: Replace `Form::loadDataFrom()` merge strategy booleans with explicit values.

- class: [`Netwerkstatt\SilverstripeRector\Rector\Forms\FormLoadDataFromMergeStrategyRector`](../scripts/../src/Rector/Forms/FormLoadDataFromMergeStrategyRector.php)

```diff
@@ -1,4 +1,4 @@
 use SilverStripe\Forms\Form;

-$form->loadDataFrom($data, true);
-$form->loadDataFrom($data, false);
+$form->loadDataFrom($data, \SilverStripe\Forms\Form::MERGE_CLEAR_MISSING);
+$form->loadDataFrom($data, 0);
```

<br>

### FormValidateToIsValidRector

Silverstripe 6.0: Convert `Form::validate()` boolean usage to `isValid()`.

- class: [`Netwerkstatt\SilverstripeRector\Rector\Forms\FormValidateToIsValidRector`](../scripts/../src/Rector/Forms/FormValidateToIsValidRector.php)

```diff
@@ -1,10 +1,10 @@
 use SilverStripe\Forms\Form;

 function check(Form $form, bool $other): bool
 {
-    if ($form->validate()) {
+    if ($form->validate()->isValid()) {
         return true;
     }

-    return $form->validate() && $other;
+    return $form->validate()->isValid() && $other;
 }
```

<br>

### GetIDListToColumnIDRector

Silverstripe 6.2: Replace `getIDList()` with sort(null)->column('ID') or column('ID')

- class: [`Netwerkstatt\SilverstripeRector\Rector\ORM\GetIDListToColumnIDRector`](../scripts/../src/Rector/ORM/GetIDListToColumnIDRector.php)

```diff
@@ -1,2 +1,2 @@
-$dataList->getIDList();
-$eagerLoadedList->getIDList();
+$dataList->sort(null)->column('ID');
+$eagerLoadedList->column('ID');
```

<br>

### ListFilterToArrayRector

Code Style: Translates Silverstripe ORM `filter()` and similar calls from string notation to array notation.

- class: [`Netwerkstatt\SilverstripeRector\Rector\ORM\ListFilterToArrayRector`](../scripts/../src/Rector/ORM/ListFilterToArrayRector.php)

```diff
@@ -1,4 +1,4 @@
-$list->filter('key', 'value');
-$list->exclude('key', 'value');
-$list->filterAny('key', 'value');
-$list->excludeAny('key', 'value');
+$list->filter(['key' => 'value']);
+$list->exclude(['key' => 'value']);
+$list->filterAny(['key' => 'value']);
+$list->excludeAny(['key' => 'value']);
```

<br>

### ListSortToArrayRector

Code Style: Translates Silverstripe ORM `sort()` calls from string notation to array notation.

- class: [`Netwerkstatt\SilverstripeRector\Rector\ORM\ListSortToArrayRector`](../scripts/../src/Rector/ORM/ListSortToArrayRector.php)

```diff
@@ -1,3 +1,3 @@
-$list->sort('Title', 'DESC');
-$list->sort('Title');
-$list->sort('Title ASC, Created DESC');
+$list->sort(['Title' => 'DESC']);
+$list->sort(['Title' => 'ASC']);
+$list->sort(['Title' => 'ASC', 'Created' => 'DESC']);
```

<br>

### MigrateTaskRunToPolyExecutionRector

Migrates old `Task->run()` syntax to PolyExecution with Input/Output objects

- class: [`Netwerkstatt\SilverstripeRector\Rector\BuildTask\MigrateTaskRunToPolyExecutionRector`](../scripts/../src/Rector/BuildTask/MigrateTaskRunToPolyExecutionRector.php)

```diff
@@ -1,3 +1,5 @@
 $task = new MyTask();
-$task->setMyParam(true);
-$task->run(null);
+$definition = new \Symfony\Component\Console\Input\InputDefinition($task->getOptions());
+$input = new \Symfony\Component\Console\Input\ArrayInput(['MyParam' => true], $definition);
+$output = \SilverStripe\PolyExecution\PolyOutput::create(\SilverStripe\PolyExecution\PolyOutput::FORMAT_ANSI);
+$task->run($input, $output);
```

<br>

### ModelDataExistsReturnTypeRector

Ensures that any `exists()` method on classes extending ModelData is public and returns bool

- class: [`Netwerkstatt\SilverstripeRector\Rector\Misc\ModelDataExistsReturnTypeRector`](../scripts/../src/Rector/Misc/ModelDataExistsReturnTypeRector.php)

```diff
@@ -1,7 +1,7 @@
 class MyModel extends ModelData
 {
-    protected function exists()
+    public function exists(): bool
     {
         return true;
     }
 }
```

<br>

### ModelDataForTemplateReturnTypeRector

Adds strict string return type to `forTemplate()` on all ModelData subclasses (including DBField, DataObject, etc.)

- class: [`Netwerkstatt\SilverstripeRector\Rector\ORM\ModelDataForTemplateReturnTypeRector`](../scripts/../src/Rector/ORM/ModelDataForTemplateReturnTypeRector.php)

```diff
@@ -1,9 +1,9 @@
 use SilverStripe\ORM\FieldType\DBField;

 class MyField extends DBField
 {
-    public function forTemplate()
+    public function forTemplate(): string
     {
         return 'test';
     }
 }
```

<br>

### ObsoleteMethod

Adds TODO upgrade comments for method calls/overrides of methods that have been removed.

:wrench: **configure it!**

- class: [`Netwerkstatt\SilverstripeRector\Rector\Methods\ObsoleteMethod`](../scripts/../src/Rector/Methods/ObsoleteMethod.php)

```diff
@@ -1,1 +1,2 @@
+/** @TODO SSU RECTOR UPGRADE TASK - DNADesign\Elemental\Controllers\ElementalAreaController::formAction: removed without equivalent functionality to replace it */
 $controller->formAction();
```

<br>

### OtherMethod

Adds TODO upgrade comments for various method, static, and trait changes.

:wrench: **configure it!**

- class: [`Netwerkstatt\SilverstripeRector\Rector\Methods\OtherMethod`](../scripts/../src/Rector/Methods/OtherMethod.php)

```diff
@@ -1,1 +1,2 @@
+/** @TODO SSU RECTOR UPGRADE TASK - VersionProvider::getComposerLock: has been replaced by composer-runtime-api FQCN: (SilverStripe\Core\Manifest\VersionProvider) */
 $provider->getComposerLock();
```

<br>

### ParentClassToTraitsRector

Silverstripe 4.0: Replace specific parent classes with traits and remove extends

:wrench: **configure it!**

- class: [`Netwerkstatt\SilverstripeRector\Rector\Misc\ParentClassToTraitsRector`](../scripts/../src/Rector/Misc/ParentClassToTraitsRector.php)

```diff
@@ -1,5 +1,12 @@
 <?php

-class MyObject extends Object
+use SilverStripe\Core\Injector\Injectable;
+use SilverStripe\Core\Config\Configurable;
+use SilverStripe\Core\Extensible;
+
+class MyObject
 {
+    use Injectable;
+    use Configurable;
+    use Extensible;
 }
```

<br>

### PolyCommandGetOptionsPublicRector

Changes `getOptions()` method visibility to public for classes extending SilverStripe\PolyExecution\PolyCommand. 
            See https://docs.silverstripe.org/en/6/developer_guides/cli/polycommand/#buildtask

- class: [`Netwerkstatt\SilverstripeRector\Rector\BuildTask\PolyCommandGetOptionsPublicRector`](../scripts/../src/Rector/BuildTask/PolyCommandGetOptionsPublicRector.php)

```diff
@@ -1,7 +1,7 @@
 class MyCommand extends \SilverStripe\PolyExecution\PolyCommand
 {
-    protected function getOptions()
+    public function getOptions()
     {
         return [];
     }
 }
```

<br>

### PropertyFetchToMethodCallRector

Code Style: Replace specific property fetches with method calls

:wrench: **configure it!**

- class: [`Netwerkstatt\SilverstripeRector\Rector\Misc\PropertyFetchToMethodCallRector`](../scripts/../src/Rector/Misc/PropertyFetchToMethodCallRector.php)

```diff
@@ -1,13 +1,13 @@
 <?php

 use App\Model\User;

 class User
 {
     public string $name;

     public function print(): void
     {
-        echo $this->name;
+        echo $this->getName();
     }
 }
```

<br>

### PropertyToConfigRector

Convert protected properties to private static config properties

:wrench: **configure it!**

- class: [`Netwerkstatt\SilverstripeRector\Rector\Misc\PropertyToConfigRector`](../scripts/../src/Rector/Misc/PropertyToConfigRector.php)

```diff
@@ -1,4 +1,7 @@
 class MyTask extends BuildTask
 {
-    protected $enabled = true;
+    /**
+     * @config
+     */
+    private static $is_enabled = true;
 }
```

<br>

### RemoveEmptyFilterRector

Remove empty `filter()` calls from `DataObject::get()` chains

- class: [`Netwerkstatt\SilverstripeRector\Rector\ORM\RemoveEmptyFilterRector`](../scripts/../src/Rector/ORM/RemoveEmptyFilterRector.php)

```diff
@@ -1,1 +1,1 @@
-SiteTree::get()->filter("");
+SiteTree::get();
```

<br>

### RemoveSilverstripeDeprecationCommentRector

Silverstripe: Remove deprecation comments that are no longer applicable after upgrade.

:wrench: **configure it!**

- class: [`Netwerkstatt\SilverstripeRector\Rector\Misc\RemoveSilverstripeDeprecationCommentRector`](../scripts/../src/Rector/Misc/RemoveSilverstripeDeprecationCommentRector.php)

```diff
@@ -1,10 +1,6 @@
 class SomeClass
 {
-    /**
-     * @deprecated This method is deprecated. Use newMethod() instead.
-     * See: https://docs.silverstripe.org/...
-     */
     public function newMethod()
     {
     }
 }
```

<br>

### RemoveVersionedGridFieldStateRector

Removes `addComponent()` calls for deprecated VersionedGridFieldState from any context

- class: [`Netwerkstatt\SilverstripeRector\Rector\Deprecations\RemoveVersionedGridFieldStateRector`](../scripts/../src/Rector/Deprecations/RemoveVersionedGridFieldStateRector.php)

```diff
@@ -1,1 +1,5 @@
-return $this->config->doSomething()->addComponent(VersionedGridFieldState::class);
+// VersionedGridFieldState is Deprecated, consider Using:
+// $dataColumns = $config->getComponentByType(GridFieldDataColumns::class);
+// Show the flags against a specific column (e.g. if you don't have a Title column)
+// $dataColumns->setColumnsForStatusFlag(['Name']);
+return $this->config->doSomething();
```

<br>

### RemovedParameter

Adds TODO upgrade comments for method calls/overrides where a parameter was removed.

:wrench: **configure it!**

- class: [`Netwerkstatt\SilverstripeRector\Rector\Methods\RemovedParameter`](../scripts/../src/Rector/Methods/RemovedParameter.php)

```diff
@@ -1,1 +1,2 @@
+/** @TODO SSU RECTOR UPGRADE TASK - ElementalAreaController::elementForm: Removed deprecated parameter $request in ElementalAreaController::elementForm() FQCN: (DNADesign\Elemental\Controllers\ElementalAreaController) */
 $controller->elementForm($request);
```

<br>

### RenameCurrentPageIdToCurrentRecordIdRector

Rename `CMSPageEditController::currentPageID()` to `currentRecordID()`, 
            See https://docs.silverstripe.org/en/6/changelogs/6.0.0/#silverstripeadmin

- class: [`Netwerkstatt\SilverstripeRector\Rector\CMS\RenameCurrentPageIdToCurrentRecordIdRector`](../scripts/../src/Rector/CMS/RenameCurrentPageIdToCurrentRecordIdRector.php)

```diff
@@ -1,1 +1,1 @@
-$controller->currentPageID();
+$controller->currentRecordID();
```

<br>

### RenameFieldListMethodsWithoutArrayParamRector

Silverstripe 5.3: Safely renames `->addFieldsToTab()` and `->removeFieldsFromTab()` to singular equivalents, or adds a TODO for unknown variables.

- class: [`Netwerkstatt\SilverstripeRector\Rector\Misc\RenameFieldListMethodsWithoutArrayParamRector`](../scripts/../src/Rector/Misc/RenameFieldListMethodsWithoutArrayParamRector.php)

```diff
@@ -1,2 +1,3 @@
+// @TODO SSU RECTOR UPGRADE TASK - FieldList::addFieldsToTab: Mixed type detected. If this is a single field/string, change to addFieldToTab() manually.
 $fields->addFieldsToTab('Root.Main', $unknownVariable);
-$fields->removeFieldsFromTab('Root.Main', 'Content');
+$fields->removeFieldFromTab('Root.Main', 'Content');
```

<br>

### RenamedParameter

Adds TODO upgrade comments for method calls/overrides where a parameter was renamed.

:wrench: **configure it!**

- class: [`Netwerkstatt\SilverstripeRector\Rector\Methods\RenamedParameter`](../scripts/../src/Rector/Methods/RenamedParameter.php)

```diff
@@ -1,1 +1,2 @@
+/** @TODO SSU RECTOR UPGRADE TASK - BuildTask::run: Renamed parameter $request in BuildTask::run() to $input */
 $service->run($request);
```

<br>

### RenamedTo

Adds TODO upgrade comments and attempts to auto-rename method calls/overrides.

:wrench: **configure it!**

- class: [`Netwerkstatt\SilverstripeRector\Rector\Methods\RenamedTo`](../scripts/../src/Rector/Methods/RenamedTo.php)

```diff
@@ -1,1 +1,2 @@
-$extension->MetaTags($params);
+/** @TODO SSU RECTOR UPGRADE TASK - FluentSiteTreeExtension::MetaTags: renamed to updateMetaTags() FQCN: (TractorCow\Fluent\Extension\FluentSiteTreeExtension) */
+$extension->updateMetaTags($params);
```

<br>

### ReplaceHasCurrWithCurrRector

Silverstripe 6.0: Replace `Controller::has_curr()` with `Controller::curr()` !== null

- class: [`Netwerkstatt\SilverstripeRector\Rector\Control\ReplaceHasCurrWithCurrRector`](../scripts/../src/Rector/Control/ReplaceHasCurrWithCurrRector.php)

```diff
@@ -1,4 +1,4 @@
 use SilverStripe\Control\Controller;
-if (Controller::has_curr()) {
+if (Controller::curr() !== null) {
     // ...
 }
```

<br>

### ReplacePageTypeClassesRector

Replaces deprecated `SiteTree::page_type_classes()` with ClassInfo and invokeWithExtensions setup within the local method scope.
            See https://docs.silverstripe.org/en/6/changelogs/6.0.0/#silverstripecms

- class: [`Netwerkstatt\SilverstripeRector\Rector\CMS\ReplacePageTypeClassesRector`](../scripts/../src/Rector/CMS/ReplacePageTypeClassesRector.php)

```diff
@@ -1,5 +1,7 @@
 class MyPage extends SiteTree {
     public function getList() {
-        return SiteTree::page_type_classes();
+        $classes = \SilverStripe\Core\ClassInfo::getValidSubClasses(\SilverStripe\CMS\Model\SiteTree::class);
+        \SilverStripe\ORM\DataObject::singleton(\SilverStripe\CMS\Model\SiteTree::class)->invokeWithExtensions('updateAllowedSubClasses', $classes);
+        return $classes;
     }
 }
```

<br>

### ReplaceSilverstripeNullArgumentRector

Silverstripe 5.4: Replace deprecated null arguments with explicit defaults.

:wrench: **configure it!**

- class: [`Netwerkstatt\SilverstripeRector\Rector\Misc\ReplaceSilverstripeNullArgumentRector`](../scripts/../src/Rector/Misc/ReplaceSilverstripeNullArgumentRector.php)

```diff
@@ -1,1 +1,1 @@
-$result->addError("Message", "bad", null, null);
+$result->addError("Message", "bad", "", ValidationResult::CAST_TEXT);
```

<br>

### ReplacedWith

Adds TODO upgrade comments and attempts to auto-fix method replacements.

:wrench: **configure it!**

- class: [`Netwerkstatt\SilverstripeRector\Rector\Methods\ReplacedWith`](../scripts/../src/Rector/Methods/ReplacedWith.php)

```diff
@@ -1,1 +1,2 @@
-$form->getCMSValidator();
+/** @TODO SSU RECTOR UPGRADE TASK - SilverStripe\UserForms\UserForm::getCMSValidator: replaced with getCMSCompositeValidator() */
+$form->getCMSCompositeValidator();
```

<br>

### ReturnType

Adds TODO upgrade comments for method calls/overrides where the return type changed.

:wrench: **configure it!**

- class: [`Netwerkstatt\SilverstripeRector\Rector\Methods\ReturnType`](../scripts/../src/Rector/Methods/ReturnType.php)

```diff
@@ -1,1 +1,2 @@
+/** @TODO SSU RECTOR UPGRADE TASK - BaseElement::forTemplate: Changed return type for method BaseElement::forTemplate() from dynamic to string */
 $value = $item->forTemplate();
```

<br>

### Silverstripe54MethodCallToStaticCallRector

Silverstripe 5.4: Move deprecated instance calls to their static replacements.

- class: [`Netwerkstatt\SilverstripeRector\Rector\Forms\Silverstripe54MethodCallToStaticCallRector`](../scripts/../src/Rector/Forms/Silverstripe54MethodCallToStaticCallRector.php)

```diff
@@ -1,2 +1,2 @@
-$emails->isValidEmail($email);
-$handler->isCli();
+\SilverStripe\Control\Email\Email::is_valid_address($email);
+\SilverStripe\Control\Director::is_cli();
```

<br>

### Silverstripe54StaticCallRenameRector

Silverstripe 5.4: Rename selected static API calls.

- class: [`Netwerkstatt\SilverstripeRector\Rector\Misc\Silverstripe54StaticCallRenameRector`](../scripts/../src/Rector/Misc/Silverstripe54StaticCallRenameRector.php)

```diff
@@ -1,1 +1,1 @@
-SilverStripe\View\SSViewer::fromString($template);
+SilverStripe\TemplateEngine\SSTemplateEngine::renderString($template);
```

<br>

### SilverstripeDeprecationCommentRector

Silverstripe: Add deprecation comments to classes or methods without direct substitute.

:wrench: **configure it!**

- class: [`Netwerkstatt\SilverstripeRector\Rector\Misc\SilverstripeDeprecationCommentRector`](../scripts/../src/Rector/Misc/SilverstripeDeprecationCommentRector.php)

```diff
@@ -1,6 +1,10 @@
 class SomeClass
 {
+    /**
+     * @deprecated This method is deprecated.
+     * See: https://docs.silverstripe.org/...
+     */
     public function oldMethod()
     {
     }
 }
```

<br>

### StatToConfigGetRector

Silverstripe 4.0: Replace $this->stat('foo') with `static::config()->get('foo')`

- class: [`Netwerkstatt\SilverstripeRector\Rector\Config\StatToConfigGetRector`](../scripts/../src/Rector/Config/StatToConfigGetRector.php)

```diff
@@ -1,6 +1,6 @@
 class MyClass {
     use \SilverStripe\Core\Config\Configurable;
     public function myMethod() {
-        $this->stat('foo');
+        static::config()->get('foo');
     }
 }
```

<br>

### StaticCallToConfigRector

Convert static method calls to config properties

:wrench: **configure it!**

- class: [`Netwerkstatt\SilverstripeRector\Rector\Misc\StaticCallToConfigRector`](../scripts/../src/Rector/Misc/StaticCallToConfigRector.php)

```diff
@@ -1,8 +1,11 @@
 class MyObject extends DataObject
 {
+    /**
+     * @config
+     */
+    private static $subclass_access = false;
     public function onBeforeWrite()
     {
         parent::onBeforeWrite();
-        self::disable_subclass_access();
     }
 }
```

<br>

### UpdateControllerRenderSignatureRector

Updates `render()` method signature in Controller subclasses to use `$params` = null and DBHTMLText return type.

- class: [`Netwerkstatt\SilverstripeRector\Rector\Control\UpdateControllerRenderSignatureRector`](../scripts/../src/Rector/Control/UpdateControllerRenderSignatureRector.php)

```diff
@@ -1,5 +1,5 @@
 class MyController extends \SilverStripe\Control\Controller {
-    public function render($context = null) {
+    public function render($params = null): \SilverStripe\ORM\FieldType\DBHTMLText {
         return null;
     }
 }
```

<br>

### UseCreateRector

Code Style: Change new Object to static call for classes that use Injectable trait

- class: [`Netwerkstatt\SilverstripeRector\Rector\Injector\UseCreateRector`](../scripts/../src/Rector/Injector/UseCreateRector.php)

```diff
@@ -1,7 +1,7 @@
 class SomeClass
 {
     public function run()
     {
-        new InjectableClass($name);
+        InjectableClass::create($name);
     }
 }
```

<br>

### UseInstead

Adds TODO upgrade comments and attempts to auto-fix methods that should use an alternative instead.

:wrench: **configure it!**

- class: [`Netwerkstatt\SilverstripeRector\Rector\Methods\UseInstead`](../scripts/../src/Rector/Methods/UseInstead.php)

```diff
@@ -1,1 +1,2 @@
-$value = $service->oldMethod($arg);
+/** @TODO SSU RECTOR UPGRADE TASK - SomeClass::oldMethod: use newMethod() instead. */
+$value = $service->newMethod($arg);
```

<br>

### Visibility

Adds TODO upgrade comments for method calls/overrides where the method visibility changed.

:wrench: **configure it!**

- class: [`Netwerkstatt\SilverstripeRector\Rector\Methods\Visibility`](../scripts/../src/Rector/Methods/Visibility.php)

```diff
@@ -1,1 +1,2 @@
+/** @TODO SSU RECTOR UPGRADE TASK - LeftAndMain::jsonError: Changed visibility for method LeftAndMain::jsonError() from public to protected */
 $leftAndMain->jsonError($message, 400);
```

<br>
