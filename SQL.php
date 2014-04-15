<?php
/**
 *=============== File Details ===============
 *
 *Name:       SQL
 *Created By: JV
 *Date:       04/14/14
 *Function:   Object oriented SQL
 *Notes:      Add some elegance back to SQL building
 *
 *============ Revision History ===============
 *
 *Date                   ID            Reason
 *------------------------------------------------------------------------------
 *04/14/14             JV            File creation
 *
 **/

namespace JoshWillik;

require "Select.php";
require "Update.php";
require "Delete.php";
require "Insert.php";

class SQLException extends \Exception
{
  function __construct( $message, $statement ){
    $this->message = $message;
    $this->statement = $statement;

    parent::__construct( $message );
  }
}

class SQL
{

  public static function select( $columns = "*" ){
    return new Select( $columns );
  }

  public static function insert( $columns ){
    return new Insert( $columns );
  }

  public static function update( $table ){
    return new Update( $table );
  }

  public static function delete(){
    return new Delete;
  }
}
