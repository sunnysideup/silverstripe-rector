<?php

namespace App\Tests {
    class ComplexAdmin {
        public function getGridFieldConfig() {
            // Case 1: Method call inside a return statement (using ::class)
            return (new \Config())->addComponent(\SilverStripe\Versioned\VersionedGridFieldState\VersionedGridFieldState::class);
        }

        public function configureGrid($myConfig) {
            // Case 2: Deep fluent chain with an assignment
            $result = $myConfig->doSomethingElse()
                ->addComponent(\VersionedGridFieldState::create())
                ->addComponent(new \GridFieldSortableHeader());
                
            return $result;
        }

        public function propertyAccess() {
            // Case 3: Calling on a property
            $this->config->addComponent(new \VersionedGridFieldState());
            return $this->config;
        }

        public function inlineInstantiation() {
            // Case 4: Calling directly on an inline instantiation
            (new \MyConfigClass())->addComponent(new \VersionedGridFieldState());
        }

        public function standardVariable() {
            // Case 5: The classic expression that should be reduced to a Nop
            $config = new \Config();
            $config->addComponent(new \VersionedGridFieldState());
        }

        public function stringFallback() {
            // Case 6: Strings or other classes should NOT be touched
            $config = new \Config();
            $config->addComponent('SomeOtherComponent');
            $config->addComponent(new \GridFieldPaginator(20));
        }

        public function importedClassConstant() {
            // Case 7: Using ::class on imported constant
            $config = new \Config();
            $config->addComponent(VersionedGridFieldState::class);
        }

        public function insideCondition($config) {
            // Case 8: Used inside an if condition
            if ($config->addComponent(new \VersionedGridFieldState())) {
                return true;
            }
        }

        public function arrayAssignment($config) {
            // Case 9: Assigned directly to an array
            $arr = [];
            $arr[] = $config->addComponent(\VersionedGridFieldState::create());
        }

        public function ternaryOperator($a, $config) {
            // Case 10: Inside a ternary operator
            return $a ? $config->addComponent(VersionedGridFieldState::class) : null;
        }

        public function nullArgument($config) {
            // Case 11: addComponent called with nothing (should safely ignore)
            $config->addComponent();
        }

        public function doubleChaining($config) {
            // Case 12: Multiple addComponents of the same deprecated class
            $config->addComponent(new \VersionedGridFieldState())
                   ->addComponent(\VersionedGridFieldState::create());
        }

        public function asArgument($config) {
            // Case 13: Used as an argument for another function call
            $this->doSomething($config->addComponent(VersionedGridFieldState::class));
        }

        public function chainAfterTarget($config) {
            // Case 14: Fluent methods called AFTER the target
            $config->addComponent(VersionedGridFieldState::class)->removeComponentsByType('GridFieldPaginator');
        }

        public function simpleVariableAssignment($config) {
            // Case 15: Variable assignment where the method call is the RHS
            $a = $config->addComponent(new \VersionedGridFieldState());
        }

        public function doubleColonClassWithNamespacePrefix($config) {
            // Case 16: Using ::class but with full namespace
            $config->addComponent(\SilverStripe\Versioned\VersionedGridFieldState::class);
        }
    }
}
-----
<?php

namespace App\Tests {
    class ComplexAdmin {
        public function getGridFieldConfig() {
            // Case 1: Method call inside a return statement (using ::class)
            // VersionedGridFieldState is Deprecated, consider Using:
            // $dataColumns = $config->getComponentByType(GridFieldDataColumns::class);
            // Show the flags against a specific column (e.g. if you don't have a Title column)
            // $dataColumns->setColumnsForStatusFlag(['Name']);
            return new \Config();
        }

        public function configureGrid($myConfig) {
            // Case 2: Deep fluent chain with an assignment
            // VersionedGridFieldState is Deprecated, consider Using:
            // $dataColumns = $config->getComponentByType(GridFieldDataColumns::class);
            // Show the flags against a specific column (e.g. if you don't have a Title column)
            // $dataColumns->setColumnsForStatusFlag(['Name']);
            $result = $myConfig->doSomethingElse()
                ->addComponent(new \GridFieldSortableHeader());
                
            return $result;
        }

        public function propertyAccess() {
            // Case 3: Calling on a property
            // VersionedGridFieldState is Deprecated, consider Using:
            // $dataColumns = $config->getComponentByType(GridFieldDataColumns::class);
            // Show the flags against a specific column (e.g. if you don't have a Title column)
            // $dataColumns->setColumnsForStatusFlag(['Name']);
            return $this->config;
        }

        public function inlineInstantiation() {
            // Case 4: Calling directly on an inline instantiation
            // VersionedGridFieldState is Deprecated, consider Using:
            // $dataColumns = $config->getComponentByType(GridFieldDataColumns::class);
            // Show the flags against a specific column (e.g. if you don't have a Title column)
            // $dataColumns->setColumnsForStatusFlag(['Name']);
            new \MyConfigClass();
        }

        public function standardVariable() {
            // Case 5: The classic expression that should be reduced to a Nop
            $config = new \Config();
            // VersionedGridFieldState is Deprecated, consider Using:
            // $dataColumns = $config->getComponentByType(GridFieldDataColumns::class);
            // Show the flags against a specific column (e.g. if you don't have a Title column)
            // $dataColumns->setColumnsForStatusFlag(['Name']);
        }

        public function stringFallback() {
            // Case 6: Strings or other classes should NOT be touched
            $config = new \Config();
            $config->addComponent('SomeOtherComponent');
            $config->addComponent(new \GridFieldPaginator(20));
        }

        public function importedClassConstant() {
            // Case 7: Using ::class on imported constant
            $config = new \Config();
            // VersionedGridFieldState is Deprecated, consider Using:
            // $dataColumns = $config->getComponentByType(GridFieldDataColumns::class);
            // Show the flags against a specific column (e.g. if you don't have a Title column)
            // $dataColumns->setColumnsForStatusFlag(['Name']);
        }

        public function insideCondition($config) {
            // Case 8: Used inside an if condition
            // VersionedGridFieldState is Deprecated, consider Using:
            // $dataColumns = $config->getComponentByType(GridFieldDataColumns::class);
            // Show the flags against a specific column (e.g. if you don't have a Title column)
            // $dataColumns->setColumnsForStatusFlag(['Name']);
            if ($config) {
                return true;
            }
        }

        public function arrayAssignment($config) {
            // Case 9: Assigned directly to an array
            $arr = [];
            // VersionedGridFieldState is Deprecated, consider Using:
            // $dataColumns = $config->getComponentByType(GridFieldDataColumns::class);
            // Show the flags against a specific column (e.g. if you don't have a Title column)
            // $dataColumns->setColumnsForStatusFlag(['Name']);
            $arr[] = $config;
        }

        public function ternaryOperator($a, $config) {
            // Case 10: Inside a ternary operator
            // VersionedGridFieldState is Deprecated, consider Using:
            // $dataColumns = $config->getComponentByType(GridFieldDataColumns::class);
            // Show the flags against a specific column (e.g. if you don't have a Title column)
            // $dataColumns->setColumnsForStatusFlag(['Name']);
            return $a ? $config : null;
        }

        public function nullArgument($config) {
            // Case 11: addComponent called with nothing (should safely ignore)
            $config->addComponent();
        }

        public function doubleChaining($config) {
            // Case 12: Multiple addComponents of the same deprecated class
            // VersionedGridFieldState is Deprecated, consider Using:
            // $dataColumns = $config->getComponentByType(GridFieldDataColumns::class);
            // Show the flags against a specific column (e.g. if you don't have a Title column)
            // $dataColumns->setColumnsForStatusFlag(['Name']);
            $config;
        }

        public function asArgument($config) {
            // Case 13: Used as an argument for another function call
            // VersionedGridFieldState is Deprecated, consider Using:
            // $dataColumns = $config->getComponentByType(GridFieldDataColumns::class);
            // Show the flags against a specific column (e.g. if you don't have a Title column)
            // $dataColumns->setColumnsForStatusFlag(['Name']);
            $this->doSomething($config);
        }

        public function chainAfterTarget($config) {
            // Case 14: Fluent methods called AFTER the target
            // VersionedGridFieldState is Deprecated, consider Using:
            // $dataColumns = $config->getComponentByType(GridFieldDataColumns::class);
            // Show the flags against a specific column (e.g. if you don't have a Title column)
            // $dataColumns->setColumnsForStatusFlag(['Name']);
            $config->removeComponentsByType('GridFieldPaginator');
        }

        public function simpleVariableAssignment($config) {
            // Case 15: Variable assignment where the method call is the RHS
            // VersionedGridFieldState is Deprecated, consider Using:
            // $dataColumns = $config->getComponentByType(GridFieldDataColumns::class);
            // Show the flags against a specific column (e.g. if you don't have a Title column)
            // $dataColumns->setColumnsForStatusFlag(['Name']);
            $a = $config;
        }

        public function doubleColonClassWithNamespacePrefix($config) {
            // Case 16: Using ::class but with full namespace
            // VersionedGridFieldState is Deprecated, consider Using:
            // $dataColumns = $config->getComponentByType(GridFieldDataColumns::class);
            // Show the flags against a specific column (e.g. if you don't have a Title column)
            // $dataColumns->setColumnsForStatusFlag(['Name']);
        }
    }
}
