<?php

/**
 * author:
 * date:
 * description: A root class for all manage classes. This class communicates with DB
 */

define("FTP_SERVER", 'cs.ashesi.edu.gh');
define("FTP_USERNAME", 'fredrick-abayie@cs.ashesi.edu.gh');
define("FTP_PASSWORD", '16!hEi2As');
define("FTP_PORT", 21);


/*
 * Ftp class
 */
class ftp {
    
    
    /*
     * Constructor
     */
    function ftp ( ) {
        $this->conn = false;
        $this->result = false;
    }

    
    /*
     * Function to connect to server
     */
    function connect ( ) {
        $this->conn = ftp_connect ( FTP_SERVER );
        if ( $this->conn ) {
            if ( ftp_login ( $this->conn , FTP_USERNAME, FTP_PASSWORD ) ) {
                return $this->conn;                
            }
        } else {
            return false;
        }
    }
    
    
    function getFolderNames ( $directory ) {
        if ( is_array ( $children = ftp_nlist ( $this->conn, $directory ) ) ) {
            $items = array ( );
            foreach ( $children as $key => $child ) {
                $items[$key] = $child;
            }
            return $items;
        }
    }
    
    
    function folders ( $directory ) {
        
       if ( is_array ( $children = ftp_rawlist ( $this->conn, $directory ) ) ) { 
            $items = array ( ); 

            foreach ( $children as $child ) { 
                
                $chunks = preg_split ( "/\s+/", $child ); 
                
                list ( $item['rights'], $item['number'], $item['user'], $item['group'], $item['size'], 
                        $item['month'], $item['day'], $item['time'], $item['name'] ) = $chunks; 
                
                $item['type'] = $chunks[0]{0} === 'd' ? 'directory' : 'file'; 
                array_splice($chunks, 0, 8); 
                $items[implode(" ", $chunks)] = $item; 
            }
            return $items; 
        }
    }
    
    
    function currentDirectory ( ) {
        if ( $this->conn ) {
            return ftp_pwd ( $this->conn );
        }
        return false;
    }
    
    
    
    
    /*
     * Function to close the FTP connection
     */
    function close ( ) {
        return ftp_close ( $this->conn );
    }//end of close()
    
}