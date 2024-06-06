# execute this in oxid root directory
pushd /var/www/html

vendor/bin/oe-console oe:module:reset-configurations 
vendor/bin/oe-console oe:cache:clear 
vendor/bin/oe-console oe:module:install-configuration ./source/modules/axytos/kaufaufrechnung

chown -R :www-data .
chown -R www-data .
