<?php

$databases['default']['default'] = array(
  'driver' => 'mysql',
  'database' => getenv('MYSQL_DATABASE'),
  'username' => getenv('MYSQL_USER'),
  'password' => getenv('MYSQL_PASSWORD'),
  'host' => '127.0.0.1',
);

$settings['install_profile'] = 'standard';

$settings['trusted_host_patterns'][] = ".*";
