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

echo "<pre>";
echo SQL::select( "EMP_PK")
  ->select( 'EMP_Admin' )
  ->select( SQL::select( 'COUNT( EML_PK )' )
    ->from( 'Emails' )
    ->where( 'EMP_PK = EML_EMP_FK' )
    ->select_as( 'NEW_EMAIL' )
  )
  ->from( "Employees" )
  ->join( 'Tickets' )
    ->on( 'TIC_EMP_FK = TIC_PK' )
  ->left_join( 'Emails' )
    ->on( 'EML_EMP_FK = EMP_PK' )
    ->on( 'EML_Sent <> 1' )
  ->where( 'EMP_PK = 44' )
  ->where( 'PRI_PK = 1' )
  ->where( 'TIC_Closed <> 1' )
  ->order_by( 'TIC_PK DESC' )
  ->order_by( 'TIC_PRI_FK ASC' )
  ->group_by( 'EMP_Admin' )
  ->group_by( 'EMP_PasswordHash' )
  ->stringify();

echo "\n\n";

echo SQL::insert( 'TIC_PK' )
  ->into( 'Tickets' )
  ->values( 44 )
  ->stringify();

echo "\n\n";

echo SQL::update( "Tickets" )
  ->set( 'TIC_Closed = 1' )
  ->set( 'TIC_CloseDate = CURRENT_TIMESTAMP' )
  ->where( 'TIC_PK < 55' )
  ->where( 'TIC_PK = 660' )
  ->stringify();

echo "\n\n";

echo SQL::delete()
  ->from( 'Employees' )
  ->where( 'EMP_Admin <> 1' )
  ->stringify();
?>