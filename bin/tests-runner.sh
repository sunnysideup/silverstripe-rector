git add . -A
git commit . -m "FIX: cleanup of test"
git push

git clone https://github.com/sunnysideup/silverstripe-rector.git /tmp/ss-rector
git checkout main
cd /tmp/ss-rector
composer install
./vendor/bin/phpunit
rm /tmp/ss-rector -rf


echo "consider --- ../../bin/phpunit/mytest --bootstrap ../../autoload.php"
