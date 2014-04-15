#SQLerator

Making statement preparation easier to build.
Only designed to return stirngs.

*It is your responsibility to actually execute the returned SQL string*

##Installation

1. throw files in a folder
2. `require_once( 'path/to/folder/SQL.php');`
3. ???
4. Profit!

##Code examples

```PHP
SQL::select( "EMP_PK")
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
```

```PHP
SQL::insert( 'TIC_PK' )
  ->into( 'Tickets' )
  ->values( 44 )
  ->stringify();
```

```PHP
SQL::update( "Tickets" )
  ->set( 'TIC_Closed = 1' )
  ->set( 'TIC_CloseDate = CURRENT_TIMESTAMP' )
  ->where( 'TIC_PK < 55' )
  ->where( 'TIC_PK = 660' )
  ->stringify();
```

```PHP
SQL::delete()
  ->from( 'Employees' )
  ->where( 'EMP_Admin <> 1' )
  ->stringify();
```
