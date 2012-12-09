<?php

/*
 * @author Rohit Kumar Choudhary
 * @category Database 
 * @copyright No one. You can copy, edit, do anything you want. If you change anything to better, please let me know.
 *
 */

class connect_db {
    /*
     * Host name of the database
     * @var String
     */

    protected $host;
    /*
     * database name 
     * @var String
     */
    protected $db;
    /*
     * database Username 
     * @var String
     */
    protected $user;
    /*
     * database User password
     * @var String
     */
    protected $pass;
    /*
     * database connection link
     * @var record set
     */
    protected $link;

    /*
     * Method to establish the database connection
     */

    public function connect($host, $db, $user, $pass) {
        $this->host = $host; //MySQL Host
        $this->db = $db; //MySQL Database
        $this->user = $user; //MySQL User
        $this->pass = $pass; //MySQL Password
        $this->link = mysql_connect($this->host, $this->user, $this->pass);
        mysql_select_db($this->db);
        register_shutdown_function(array(&$this, 'close'));
    }

    /*
     * Method to run query  which is passed as paramater
     * in the database
     * @param $query type string
     */

    public function query($query) {
        $result = mysql_query($query, $this->link);
        return $result;
    }

    /*
     * Method to close the database coonection
     * @param $query type string
     */

    function close() {
        mysql_close($this->link);
    }

}

?> 