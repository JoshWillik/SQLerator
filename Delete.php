<?php
/**
 *=============== File Details ===============
 *
 *Name:       Delete Statement
 *Created By: JV
 *Date:       04/14/2014
 *Function:   Builds an Delete statement programatically
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

class DeleteException extends \Exception
{
  function __construct( $message, $statement ){
    $this->message = $message;
    $this->statement = $statement;

    parent::__construct( $message );
  }
}

class Delete
{
  public $table = NULL;
  public $wheres = array();

  function from( $table ){
    if( !is_string( $table ) ){
      throw new DeleteException( "Delete::__construct requires a string table name", $this );
    }
    $this->table = $table;

    return $this;
  }

  public function where( $wheres ){
    if( is_string( $wheres ) || is_array( $wheres ) ){
      $this->wheres = array_merge( $this->wheres, (array) $wheres );
    } else {
      throw new DeleteException( "Delete::where must be stings or arrays", $this );
    }

    return $this;
  }

  private function buildSQL(){
    if( is_null( $this->table ) ){
      throw new DeleteException( "Please give a target table", $this );
    }
    $sql = "
      DELETE FROM {$this->table}
    ";
    if( !empty( $this->wheres ) ){
      $sql .= "
        WHERE " . implode( ", ", $this->wheres ) . "
      ";
    } else {
      throw new DeleteException( "This is a dumb query, run it manually if you really want to", $this );
      
    }

    return $sql;
  }

  public function stringify(){
    return $this->buildSQL();
  }
}
?>
