<?php

  /*
  Loggin in to your account and defining the $ig variable
  You can configure the login details in the assets/config.php file
  */
  require 'assets/head.php';

  $redditName = "pewdiepiesubmissions"; //r name here. for e.g.: reddit.com/r/pewdiepiesubmissions => pewdiepiesubmissions
  $sorting = "rising"; //the way of sorting posts. can be: hot | new | top | controversial | rising
  $postsLimit = 5; //how many images you want to post, max. 10
  $removePinned = true; //remove pinned posts if sorting by hot

  $posts = $functions->getPosts($redditName, $sorting, $postsLimit, $removePinned); //getting an array of posts. by default reddit returns pinned/stickied posts on top of the requested amount of posts, if the sorting is set to hot. we remove those posts if the $removePinned is true

  try {

    for($i = 0; $i < count($posts); $i++) {

      $imgResource = "tmpImgs/" . $functions->cutImageToInstagramStoryResolution($posts[$i]["url"]) . ".jpg";
      $photo = new \InstagramAPI\Media\Photo\InstagramPhoto($imgResource, ['targetFeed' => \InstagramAPI\Constants::FEED_STORY]); //temporary image, we'll delete it later

      $ig->story->uploadPhoto($photo->getFile());
      unlink($imgResource);

    }

  }catch (\Exception $e) {

    echo 'Something went wrong: ' . $e->getMessage() . "\n";

  }

 ?>
