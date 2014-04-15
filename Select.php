<?php
/**
 *=============== File Details ===============
 *
 *Name:       Select Class Definition
 *Created By: JV
 *Date:       04/14/2014
 *Function:   A select statement subclass
 *Notes:      Should be able to handle being nested as a subselect
 *
 *============ Revision History ===============
 *
 *Date                   ID            Reason
 *------------------------------------------------------------------------------
 *04/14/2014             JV            File creation
 *
 **/

namespace JoshWillik;

class SelectException extends \Exception
{
  function __construct( $message, $statement ){
    $this->message = $message;
    $this->statement = $statement;

    parent::__construct( $message );
  }
}

class Select
{
  public $table = NULL;
  public $columns = array();
  public $wheres = array();
  public $orderBy = array();
  public $groupBy = array();
  public $joins = array();

  function __construct( $columns ){
    $this->select( $columns );
  }

  public function select( $columns ){
    if( is_array( $columns ) || is_string( $columns ) ){
      $this->columns = array_merge( $this->columns, (array) $columns );
    } else {
      throw new SelectException( "Select::select only accepts columns as strings or arrays", $this );
    }

    return $this;
  }

  public function from( $table ){
    if( !is_string( $table ) ){
      throw new SelectException( "Table name must be a string", $this );
    }
    if( is_null( $this->table ) ){
      $this->table = $table;
    } else {
      throw new SelectException( "Table already given", $this);
    }

    return $this;
  }

  public function right_join( $table ){
    return $this->join( $table, "RIGHT JOIN");
  }
  public function left_join( $table ){
    return $this->join( $table, "LEFT JOIN");
  }
  public function inner_join( $table ){
    return $this->join( $table, "INNER JOIN");
  }
  public function join( $table, $style = "INNER JOIN" ){
    if( !is_string( $table ) ){
      throw new SelectException( "Please provide table as a string", $this );
    }
    array_push( $this->joins, array(
      'table' => $table,
      'style' => $style,
      'ons' => array()
    ));

    return $this;
  }
  public function on( $condition ){
    if( empty( $this->joins ) ){
      throw new SelectException( "Cannot provide join condition when no join has been provided", $this );
    } elseif( !is_string( $condition ) && !is_array( $condition ) ){
      throw new SelectException( "Please provide the on condition in string form", $this );
    }
    $joins = &$this->joins[ count( $this->joins ) - 1 ]['ons'];
    $joins = array_merge( $joins, (array) $condition );

    return $this;
  }

  public function order_by( $orders ){
    if( is_array( $orders ) || is_string( $orders ) ){
      $this->orderBy = array_merge( $this->orderBy, (array) $orders );
    } else {
      throw new SelectException( "SQL::order_by only accepts conditions as arrays or strings", $this );
    }

    return $this;
  }

  public function group_by( $groups ){
    if( is_array( $groups ) || is_string( $groups ) ){
      $this->groupBy = array_merge( $this->groupBy, (array) $groups );
    } else {
      throw new SelectException( "SQL::group_by only accepts conditions as arrays or strings", $this );
    }

    return $this;
  }

  public function where( $condition ){
    if( is_array( $condition ) || is_string( $condition ) ){
      $this->wheres = array_merge( $this->wheres, (array) $condition );
    } else {
      throw new SelectException( "SQL::where only accepts conditions as arrays or strings", $this );
    }

    return $this;
  }

  private function build(){
    if( is_null( $this->table ) ){
      throw new SelectException( "Please give a table using SQL::from()", $this );
    }
    if( empty( $this->columns ) ){
      throw new SelectException( "No columns given", $this );
    }
    $sql = "
      SELECT 
      " . implode( ', ', $this->columns ). "
      FROM {$this->table}
    ";

    foreach( $this->joins as $join ){
      $sql .= "
      {$join['style']} {$join['table']}
      ";
      if( !empty( $join['ons'] ) ){
        $sql .= "
          ON
        ";
        $sql .= implode( ' AND ', $join['ons'] );
      }
    }

    if( !empty( $this->wheres ) ){
      $sql .= "
        WHERE
      ";
      $sql .= implode( ' AND ', $this->wheres );
    }

    if( !empty( $this->orderBy ) ){
      $sql .= "
        ORDER BY
      ";
      $sql .= implode( ', ', $this->orderBy );
    }

    if( !empty( $this->groupBy ) ){
      $sql .= "
        GROUP BY
      ";
      $sql .= implode( ', ', $this->groupBy );
    }

    return $sql;
  }

  public function stringify(){
    return $this->build();
  }

  //'as' is a reserved PHP keyword, this is a workaround
  public function select_as( $name ){
    if( !is_string( $name ) ){
      throw new SelectException( "Select::as() name must be a string", $this );
    }
    return "( " . $this->build() . " ) AS $name";
  }
}
?>