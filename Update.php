<?php
/**
 *=============== File Details ===============
 *
 *Name:       Update Statement
 *Created By: JV
 *Date:       04/14/2014
 *Function:   Builds an update statement programatically
 *Notes:      chips
 *
 *============ Revision History ===============
 *
 *Date                   ID            Reason
 *------------------------------------------------------------------------------
 *04/14/2014             JV            File creation
 *
 **/

namespace JoshWillik;

class UpdateException extends \Exception
{
  function __construct( $message, $statement ){
    $this->message = $message;
    $this->statement = $statement;

    parent::__construct( $message );
  }
}

class Update
{
  public $table = NULL;
  public $newValues = array();
  public $wheres = array();

  function __construct( $table ){
    if( !is_string( $table ) ){
      throw new UpdateException( "Update::__construct requires a string table name", $this );
    }
    $this->table = $table;
  }

  public function set( $newValues ){
    if( is_string( $newValues ) || is_array( $newValues ) ){
      $this->newValues = array_merge( $this->newValues, (array) $newValues );
    } else {
      throw new UpdateException( "Update::where arguments must be stings or arrays", $this );
    }

    return $this;
  }

  public function where( $wheres ){
    if( is_string( $wheres ) || is_array( $wheres ) ){
      $this->wheres = array_merge( $this->wheres, (array) $wheres );
    } else {
      throw new UpdateException( "Update::where must be stings or arrays", $this );
    }

    return $this;
  }

  private function buildSQL(){
    if( is_null( $this->table ) ){
      throw new UpdateException( "Please give a table using", $this );
    }
    if( empty( $this->newValues ) ){
      throw new UpdateException( "No columns given to Update, please provide them", $this );
    }
    $sql = "
      UPDATE {$this->table}
      SET
        " . implode( ', ', $this->newValues ) . "
    ";
    if( !empty( $this->wheres ) ){
      $sql .= "
        WHERE " . implode( " AND ", $this->wheres ) . "
      ";
    }

    return $sql;
  }

  public function stringify(){
    return $this->buildSQL();
  }
}
?>
