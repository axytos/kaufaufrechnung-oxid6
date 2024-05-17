# execute this in oxid root directory
pushd /var/www/html

vendor/bin/oe-console oe:module:deactivate axytos_kaufaufrechnung # remove subscribers
vendor/bin/oe-console oe:module:reset-configurations 
vendor/bin/oe-console oe:cache:clear 
vendor/bin/oe-console oe:module:install-configuration ./source/modules/axytos/kaufaufrechnung
vendor/bin/oe-console oe:module:activate axytos_kaufaufrechnung # add subscribers

chown -R :www-data .
chown -R www-data .
