# forewarn-me
Forewarn me

#DB
vendor/bin/doctrine-module orm:schema-tool:drop --force
vendor/bin/doctrine-module orm:schema-tool:create
vendor/bin/doctrine-module data-fixture:import