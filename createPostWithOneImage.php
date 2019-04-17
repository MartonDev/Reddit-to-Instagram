<?php

  /*
  Loggin in to your account and defining the $ig variable
  You can configure the login details in the assets/config.php file
  */
  require 'assets/head.php';

  $redditName = "pewdiepiesubmissions"; //r name here. for e.g.: reddit.com/r/pewdiepiesubmissions => pewdiepiesubmissions
  $sorting = "hot"; //the way of sorting posts. can be: hot | new | top | controversial | rising
  $postsLimit = 1; //how many posts you want to make, max. 10, but in this case we have to get 1
  $removePinned = true; //remove pinned posts if sorting by hot

  $posts = $functions->getPosts($redditName, $sorting, $postsLimit, $removePinned); //getting an array of posts. by default reddit returns pinned/stickied posts on top of the requested amount of posts, if the sorting is set to hot. we remove those posts if the $removePinned is true

  $postImage = "tmpImgs/" . $functions->cutImageToInstagramResolution($posts[0]["url"]) . ".jpg"; //temporary image, we'll delete it later
  $credits = "u/" . $posts[0]["author"] . "\n"; //we strongly suggest you crediting the original author
  $topComment = $functions->getCommentsForPost($redditName, $posts[0]["id"])[0]["data"]["body"]; //we can use the top comment to this reddit post as a caption for instagram

  try {

    $photo = new \InstagramAPI\Media\Photo\InstagramPhoto($postImage);
    $ig->timeline->uploadPhoto($photo->getFile(), ['caption' => $topComment . "\n-\nCredits:\n" . $credits . "\n#hastag"]);

  }catch (\Exception $e) {

    echo 'Something went wrong: ' . $e->getMessage() . "\n";

  }

  foreach(glob("tmpImgs/*") as $file) { //removing the temporary images

    if(is_file($file)) {

      unlink($file);

    }

  }

 ?>
