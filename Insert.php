<?php
/**
 *=============== File Details ===============
 *
 *Name:       Insert Statement
 *Created By: JV
 *Date:       04/14/2014
 *Function:   Builds an insert statement programatically
 *Notes:      Cake
 *
 *============ Revision History ===============
 *
 *Date                   ID            Reason
 *------------------------------------------------------------------------------
 *04/14/2014             JV            File creation
 *
 **/

namespace JoshWillik;

class InsertException extends \Exception
{
  function __construct( $message, $statement ){
    $this->message = $message;
    $this->statement = $statement;

    parent::__construct( $message );
  }
}

class Insert
{
  public $table = NULL;
  public $columns = array();
  public $values = array();

  function __construct( $columns ){
    $this->insert( $columns );
  }

  public function insert( $columns ){
    if( is_array( $columns ) || is_string( $columns ) ){
      $this->columns = array_merge( $this->columns, (array) $columns );
    } else {
      throw new InsertException( "Insert::insert only accepts columns as strings or arrays", $this );
    }

    return $this;
  }

  public function into( $table ){
    if( !is_string( $table ) ){
      throw new InsertException( "Insert::into only accepts table name as string", $this );
    }
    $this->table = $table;

    return $this;
  }

  public function values( $values ){
    if( is_integer( $values ) || is_string( $values ) || is_array( $values ) ){
      $this->values = array_merge( $this->values, (array) $values );
    } else {
      throw new InsertException( "Insert::values must be stings, ints, or arrays of those", $this );
    }

    return $this;
  }

  private function buildSQL(){
    if( is_null( $this->table ) ){
      throw new InsertException( "Please give a table using Insert::into()", $this );
    }
    if( empty( $this->columns ) ){
      throw new InsertException( "No columns given to insert, please do so with Insert::insert", $this );
    }

    $values = array_map( function( $item ){
      return is_integer( $item )? $item: "'$item'";
    }, $this->values );

    $sql = "
      INSERT INTO {$this->table}(
      " . implode( ', ', $this->columns ) . "
      ) VALUES (
      " . implode( ', ', $values ) . "
      )";

    return $sql;
  }

  public function stringify(){
    return $this->buildSQL();
  }
}
?>
