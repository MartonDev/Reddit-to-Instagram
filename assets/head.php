<?php

  set_time_limit(240);
  date_default_timezone_set('UTC');

  require 'vendor/autoload.php';
  require 'config.php';
  require 'Functions.class.php';

  $functions = new Functions();
  $ig = new \InstagramAPI\Instagram(true, false);

  try {

    $ig->login(USERNAME, PASSWORD);

  }catch(\Exception $e) {

    echo 'Something went wrong: ' . $e->getMessage() . "\n";
    exit(0);

  }

 ?>
