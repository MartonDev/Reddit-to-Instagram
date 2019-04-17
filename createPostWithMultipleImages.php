<?php

  /*
  Loggin in to your account and defining the $ig variable
  You can configure the login details in the assets/config.php file
  */
  require 'assets/head.php';

  $redditName = "pewdiepiesubmissions"; //r name here. for e.g.: reddit.com/r/pewdiepiesubmissions => pewdiepiesubmissions
  $sorting = "hot"; //the way of sorting posts. can be: hot | new | top | controversial | rising
  $postsLimit = 5; //how many posts you want to make, max. 10
  $removePinned = true; //remove pinned posts if sorting by hot

  $posts = $functions->getPosts($redditName, $sorting, $postsLimit, $removePinned); //getting an array of posts. by default reddit returns pinned/stickied posts on top of the requested amount of posts, if the sorting is set to hot. we remove those posts if the $removePinned is true

  $credits = ""; //we strongly suggest you crediting the original authors
  $imagesResource = array();

  for($i = 0; $i < count($posts); $i++) {

    $credits .= $posts[$i]["author"] . "\n";
    $file = array();
    $file["type"] = "photo"; //for instagram API
    $file["file"] = "tmpImgs/" . $functions->cutImageToInstagramResolution($posts[$i]["url"]) . ".jpg"; //temporary image, we'll delete it later

    array_push($imagesResource, $file);

  }

  try {

    $ig->timeline->uploadAlbum($imagesResource, ['caption' => "The caption of the post\n-\n-\nCredits:\n" . $credits . "#hastag"]); //upload with caption etc

  }catch (\Exception $e) {

    echo 'Something went wrong: ' . $e->getMessage() . "\n";

  }

  foreach(glob("tmpImgs/*") as $file) { //removing the temporary images

    if(is_file($file)) {

      unlink($file);

    }

  }

 ?>
