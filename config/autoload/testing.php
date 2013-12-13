<?php
/**
 * Testing Configuration Override
 */

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOSqlite\Driver',
                'params' => array(
                    'path' => __DIR__ . '/../../module/Application/data/testing.sqlite',
                ),
            )
        ),
    ),
);