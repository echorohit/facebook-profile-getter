<?php

ob_start();
include('conn.php');
$db = new connect_db();
$db->connect(DB_HOST, DB_NAME, DB_USER, DB_PASS);
if (isset($_POST)) {
    $profileurl = $_POST['profileUrl'];
    $profile = new getProfile();
    //Check for Valid url
    if ($profile->isValidURL($profileurl)) {
        $TEXT_ID = mysql_real_escape_string($profile->getTextID($profileurl));
        $profile->profileUrl();
        $profile->imageUrl();
        $res = $profile->getResponse();
        //Check response From Graph.facebook.com
        if ($profile->checkResponse() == true) {
            $response = $profile->getResponse();
            $IMAGE_URL = mysql_real_escape_string($profile->redirectedImageUrl(DUMMY_IMAGE_BOY_URL, DUMMY_IMAGE_GIRL_URL));
            if ($IMAGE_URL != '') {//Check whether image exists or not
                $query = "INSERT INTO " . PROFILE_TABLE_NAME . " (ID, TEXT_ID,IMAGE_URL) VALUES('NULL','$TEXT_ID','$IMAGE_URL')
        ON DUPLICATE KEY UPDATE IMAGE_URL= '$IMAGE_URL'";
                $db->query($query);
                $db->close();
                header('location:success.php?TEXT_ID=' . $TEXT_ID . "&message=" . base64_encode($response));
            } else {
                $msg = "Profile Image URL not found or Profile Image does not exists for this profile";
                header('location:failure.php?message=' . base64_encode($msg));
            }
        }//If for check response ends
        else {
            $response = $profile->getResponse();
            header('location:failure.php?message=' . base64_encode($response));
        }
    }//Check for valid facebook profile url ends
    else {
        $msg = "Please enter a  valid Facebook profile URL";
        header('location:failure.php?message=' . base64_encode($msg));
    }
}
ob_end_flush();
?>