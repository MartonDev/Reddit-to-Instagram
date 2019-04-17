<?php

  class Functions {

    /*
    Returns reddit posts by the given arguments
    */

    public function getPosts($redditName, $sorting, $postsLimit, $removePinned) {

      $result = json_decode(file_get_contents("https://www.reddit.com/r/" . $redditName . "/" . $sorting . ".json?limit=" . $postsLimit), true);
      $returnedPosts = $result["data"]["children"];

      $postsToReturn = array();

      for($i = 0; $i < count($returnedPosts); $i++) {

        if(!($sorting == "hot" && $removePinned && $returnedPosts[$i]["data"]["stickied"])) {

          array_push($postsToReturn, $returnedPosts[$i]["data"]);

        }

      }

      return $postsToReturn;

    }

    /*
    Resizes images and puts a black background behind them to fit in Instagram posts
    Returns a temporary location on the server. You will have to delete it afterwards (we provided a deleting example)
    */

    public function cutImageToInstagramResolution($imageURL) {

      $background = imagecreatetruecolor(1080, 1080);
      $imageLayer = $this->imagecreatefromfile($imageURL);
      $tmpFilename = $this->generateRandomString(10);

      $copyWidth = getimagesize($imageURL)[0] > getimagesize($imageURL)[1] ? 980 : getimagesize($imageURL)[0] / 100 * (980 / getimagesize($imageURL)[1] * 100);
      $copyHeight = getimagesize($imageURL)[1] > getimagesize($imageURL)[0] ? 980 : getimagesize($imageURL)[1] / 100 * (980 / getimagesize($imageURL)[0] * 100);

      imagecopyresized($background, $imageLayer, (1080 / 2) - ($copyWidth / 2), (1080 / 2) - ($copyHeight / 2), 0, 0, $copyWidth, $copyHeight, getimagesize($imageURL)[0], getimagesize($imageURL)[1]);

      imagejpeg($background, "tmpImgs/" . $tmpFilename . ".jpg", 100);

      return $tmpFilename;

    }

    /*
    Returns all comments for a post
    */

    public function getCommentsForPost($redditName, $postID) {

      $result = json_decode(file_get_contents("https://www.reddit.com/r/" . $redditName . "/comments/" . $postID . ".json"), true);

      return $result[1]["data"]["children"];

    }

    /*
    Uses imagecreate functions based on the file extension
    */

    public function imagecreatefromfile($filename) {

      switch(pathinfo($filename, PATHINFO_EXTENSION)) {

        case 'jpeg':
        case 'jpg':
          return imagecreatefromjpeg($filename);
        break;

        case 'png':
          return imagecreatefrompng($filename);
        break;

        case 'gif':
          return imagecreatefromgif($filename);
        break;

        default:
          throw new InvalidArgumentException('File "'.$filename.'" is not valid jpg, png or gif image.');
        break;

      }

    }

    /*
    Generates a random string
    */

    public function generateRandomString($length) {

      $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
      $charactersLength = strlen($characters);
      $randomString = "";

      for ($i = 0; $i < $length; $i++) {

        $randomString .= $characters[rand(0, $charactersLength - 1)];

      }

      return $randomString;

    }

  }

 ?>
