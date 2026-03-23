git clone https://github.com/sunnysideup/silverstripe-rector.git /tmp/ss-rector
git checkout main
cd /tmp/ss-rector
composer install
./vendor/bin/phpunit
rm /tmp/ss-rector -rf
