<?php

/*
 * @author Rohit Kumar Choudhary
 * @category General
 * @copyright No one. You can copy, edit, do anything you want. If you change anything to better, please let us know.
 *
 */

class getProfile {
    /*
     * Sets the profile url That can be used for getting User information from graph.facebook.com
     * @var String
     */

    protected $profileUrl;
    /*
     * Set the TEXT_ID that will be stored in database
     * @var String
     */
    protected $TEXT_ID;
    /*
     * Sets the profile url That can be used for getting information about Image from graph.facebook.com
     * @var String
     */
    protected $imgUrl;
    /*
     * Set the IMAGE_ID that will be stored in database
     * @var String
     */
    protected $IMAGE_URL;
    protected $RESPONSE;

    ///////////////////////
    /////        Methods       ///
    //////////////////////


    /*
     * Method to check whether the submitted url is a valid facebook profile url or not
     * @return boolean
     */
    public function isValidURL($url) {
        $parsedUrl = parse_url($url);
        if ($parsedUrl['host'] == 'www.facebook.com')
            return true;
        else if ($parsedUrl['host'] == 'facebook.com') {
            return true;
        } else {
            return false;
        }
    }

    /*
     * 
     * Sets the TEXT_ID property and return the TEXT_ID
     * Uses URL passed by user and Extract the User name      
     * User name can be like profile.php?id=13652131
     * or
     * simply sktnetwork 
     * Function will parse url given and finds if any query string exits if yes then
     * TEXT_ID will be that query string concatenated by "profile.php?id=" 
     * Else TEXT_ID will be path part of the URL after '/'
     * @return  String
     *       
     */

    public function getTextID($url) {
        $parsedUrl = parse_url($url);
        if (!empty($parsedUrl['query'])) {
            $text = $parsedUrl['query'];
            $this->TEXT_ID = substr($text, strrpos($text, '=') + 1);
            return 'profile.php?id=' . $this->TEXT_ID;
        } else {
            $text = $parsedUrl['path'];
            $this->TEXT_ID = substr($text, strrpos($text, '/') + 1);
            return $this->TEXT_ID;
        }
    }

    /*
     * 
     * Set the @profileUrl property of the class
     * It just concat the TEXT_ID property with http://graph.facebook.com/
     * @Return string 
     * 
     */

    public function profileUrl() {
        $this->profileUrl = 'http://graph.facebook.com/' . $this->TEXT_ID;
        return $this->profileUrl;
    }

    /*
     * Method to get the response of ths graph.facebook.com
     * @return json Object
     */

    public function getGraphResponse() {
        $response = file_get_contents($this->profileUrl);
        $res = json_decode($response);
        return $res;
    }

    /*
     * Method to get the RESPONSE property 
     * @return string
     */

    public function getResponse() {
        return $this->RESPONSE;
    }

    /*
     * Method to check response whether it consist any error message or not
     * Sets the RESPONSE property
     * @return boolean
     */

    public function checkResponse() {
        $response = $this->getGraphResponse();
        $resString = "";
        if ($response->error->message != '') {
            $resString.='Error: ' . $response->error->message . "<br />";
            $resString.='Type: ' . $response->error->type . "<br />";
            $resString.='Code: ' . $response->error->code . "<br />";
            $this->RESPONSE = $resString;
            return false;
        } else {
            $resString.="ID: " . $response->id . "<br />";
            $resString.="Name: " . $response->name . "<br />";
            $resString.="Link: " . $response->link . "<br />";
            if ($response->user)
                $resString.="Username: " . $response->user . "<br />";
            $resString.="Gender: " . $response->gender . "<br />";
            $this->RESPONSE = $resString;
            return true;
        }
    }

    /*
     * 
     * Set the @imgUrl property of the class
     * It just concat the TEXT_ID property with http://graph.facebook.com/ preceded by /picture too
     * @Return string 
     * 
     */

    public function imageUrl() {
        $this->imgUrl = 'http://graph.facebook.com/' . $this->TEXT_ID . "/picture";
        return $this->imgUrl;
    }

    /*
     * Returns the Image URL of the User that is redirected by the Graph.facebook.com/username/picture
     * @return string
     *     
     */

    public function getRedirectedUrl($url) {
        $redirect_url = null;
        if (function_exists("curl_init")) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
        } else {
            $url_parts = parse_url($url);
            $sock = fsockopen($url_parts['host'], (isset($url_parts['port']) ? (int) $url_parts['port'] : 80));
            $request = "HEAD " . $url_parts['path'] . (isset($url_parts['query']) ? '?' . $url_parts['query'] : '') . " HTTP/1.1\r\n";
            $request .= 'Host: ' . $url_parts['host'] . "\r\n";
            $request .= "Connection: Close\r\n\r\n";
            fwrite($sock, $request);
            $response = fread($sock, 2048);
            fclose($sock);
        }
        $header = "Location: ";
        $pos = strpos($response, $header);
        if ($pos === false) {
            return false;
        } else {
            $pos += strlen($header);
            $redirect_url = substr($response, $pos, strpos($response, "\r\n", $pos) - $pos);
            return $redirect_url;
        }
    }

    /*
     * 
     * Set the @IMAGE_URLproperty of the class
     * It sets the IMAGE_URL property after getting the redirected url from the function getRedirectedUrl($this->imgUrl);    
     * @Return string 
     * 
     */

    public function redirectedImageUrl($dummyImageBoy, $dummyImageGirl) {
        if (strcmp($this->getRedirectedUrl($this->imgUrl), $dummyImageBoy) == 0)
            return $this->IMAGE_URL = '';
        else if (strcmp($this->getRedirectedUrl($this->imgUrl), $dummyImageGirl) == 0)
            return $this->IMAGE_URL = '';
        else
            $url = $this->getRedirectedUrl($this->imgUrl);
        $url = str_replace('_q.jpg', '_o.jpg', $url); //replacing with original image url            
        return $this->IMAGE_URL = $url;
    }

}

?>