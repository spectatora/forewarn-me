<?php

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => 'localhost',
                    'port' => '0',
                    'dbname' => 'forewarnme',
                    'user' => 'root',
                    'password' => '',
                    'charset' => 'utf8'
                ),
            ),
        )
    )
);