<?php

namespace SilverStripe\Security {
    class PasswordValidator {
        public function validate($password, $member) {}
    }
}

namespace App {
    use SilverStripe\Security\PasswordValidator;

    class MyValidator extends PasswordValidator {
        // Test 1: Standard replacement - should auto-fix
        public function testStandard($pwd, $member) {
            $result = $this->validate($pwd, $member);
        }

        // Test 2: Unknown variable - fallback not allowed
        public function testUnknown($validator, $pwd, $member) {
            $result = $validator->validate($pwd, $member);
        }

        // Test 3: Override in subclass - should work
        public function validate($password, $member) {
            return $this->checkPassword($password);
        }
    }
}
-----
<?php

namespace SilverStripe\Security {
    class PasswordValidator {
        // @TODO SSU RECTOR UPGRADE TASK - SilverStripe\Security\PasswordValidator::validate: use checkPassword instead. FQCN: (SilverStripe\Security\PasswordValidator)
        public function validate($password, $member) {}
    }
}

namespace App {
    use SilverStripe\Security\PasswordValidator;

    class MyValidator extends PasswordValidator {
        // Test 1: Standard replacement - should auto-fix
        public function testStandard($pwd, $member) {
            // @TODO SSU RECTOR UPGRADE TASK - SilverStripe\Security\PasswordValidator::validate: use checkPassword instead. FQCN: (SilverStripe\Security\PasswordValidator)
            $result = $this->checkPassword($pwd, $member);
        }

        // Test 2: Unknown variable - fallback not allowed
        public function testUnknown($validator, $pwd, $member) {
            // @TODO SSU RECTOR UPGRADE TASK - SilverStripe\Security\PasswordValidator::validate: use checkPassword instead. FQCN: (SilverStripe\Security\PasswordValidator)
            $result = $validator->validate($pwd, $member);
        }

        // Test 3: Override in subclass - should work
        // @TODO SSU RECTOR UPGRADE TASK - SilverStripe\Security\PasswordValidator::validate: use checkPassword instead. FQCN: (SilverStripe\Security\PasswordValidator)
        public function validate($password, $member) {
            return $this->checkPassword($password);
        }
    }
}