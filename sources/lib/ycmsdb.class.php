<?php

define("SAVEQUERIES", TRUE);

define( 'OBJECT', 'OBJECT', true );

define( 'OBJECT_K', 'OBJECT_K' );

define( 'ARRAY_A', 'ARRAY_A' );

define( 'ARRAY_N', 'ARRAY_N' );

class YCMSDB {
	/**
	 * 数据库链接是否就绪
	 * @var unknown_type
	 */
	protected $ready;

	protected $show_errors;

	protected $real_escape = false;

	protected  $suppress_errors = false;

	protected $dbuser;

	protected $dbpassword;

	protected $dbname;

	protected $dbhost;

	protected $dbh;

	protected $collate;

	protected $result;

	/**
	 * 插入行ID
	 * @var int
	 */
	protected $insert_id;

	/**
	 * 受影响的行数
	 * @var int
	 */
	protected $rows_affected;


	/**
	 * 查询次数
	 * @var int
	 */
	private $num_queries = 0;
	private $last_query = null;
	private $func_call = null;
	/**
	 * 查询记录
	 * @var array
	 */
	var $queries = array();
	protected $last_error = null;
	private $last_result = null;
	private $field_types = array();

	/**
	 * @param string $dbuser
	 * @param string $dbpassword
	 * @param string $dbname
	 * @param string $dbhost
	 */
	function __construct($dbuser, $dbpassword, $dbname, $dbhost){
		register_shutdown_function( array( $this, '__destruct' ) );

		if ( YCMS_DEBUG ){
			$this->show_errors();
		}
		$this->init_charset();

		$this->dbuser = $dbuser;
		$this->dbpassword = $dbpassword;
		$this->dbname = $dbname;
		$this->dbhost = $dbhost;

		$this->db_connect();
	}

	function __destruct(){
		return true;
	}

	function init_charset() {
		if(defined("DB_CHARSET")){
			$this->charset = DB_CHARSET;
		}
	}

	function show_errors( $show = true ) {
		$errors = $this->show_errors;
		$this->show_errors = $show;
		return $errors;
	}

	function db_connect() {
		$this->is_mysql = true;
		if($this->ready){
			return ;
		}
		$new_link = defined( 'MYSQL_NEW_LINK' ) ? MYSQL_NEW_LINK : true;
		$client_flags = defined( 'MYSQL_CLIENT_FLAGS' ) ? MYSQL_CLIENT_FLAGS : 0;

		if ( YCMS_DEBUG ) {
			$this->dbh = mysql_connect( $this->dbhost, $this->dbuser, $this->dbpassword, $new_link, $client_flags );
		} else {
			$this->dbh = @mysql_connect( $this->dbhost, $this->dbuser, $this->dbpassword, $new_link, $client_flags );
		}
		if ( !$this->dbh ) {
			errorMsg('db_connect_fail');
			return;
		}
		$this->set_charset( $this->dbh );
		$this->ready = true;
		$this->select( $this->dbname, $this->dbh );
	}



	function query( $query ) {
		if (! $this->ready ){
			return false;
		}

		$return_val = 0;
		$this->flush();

		// Log how the function was called
		$this->func_call = "\$db->query(\"$query\")";

		// Keep track of the last query for debug..
		$this->last_query = $query;

		if ( defined( 'SAVEQUERIES' ) && SAVEQUERIES )
		$this->timer_start();

		$this->result = @mysql_query( $query, $this->dbh );
		$this->num_queries++;

		if ( defined( 'SAVEQUERIES' ) && SAVEQUERIES ){
			$this->queries[] = array( $query, $this->timer_stop());
		}

		// 获取错误
		if ( $this->last_error = mysql_error( $this->dbh ) ) {
			$this->print_error();
			return false;
		}

		if ( preg_match( '/^\s*(create|alter|truncate|drop)\s/i', $query ) ) {
			$return_val = $this->result;
		} elseif ( preg_match( '/^\s*(insert|delete|update|replace)\s/i', $query ) ) {
			$this->rows_affected = mysql_affected_rows( $this->dbh );
			//获取最后插入的ID
			if ( preg_match( '/^\s*(insert|replace)\s/i', $query ) ) {
				$this->insert_id = mysql_insert_id($this->dbh);
			}
			$return_val = $this->rows_affected;
		} else {
			$num_rows = 0;
			while ( $row = @mysql_fetch_object( $this->result) ) {
				$this->last_result[$num_rows] = $row;
				$num_rows++;
			}
			// 查询返回的行数
			// 返回数据个数
			$this->num_rows = $num_rows;
			$return_val     = $num_rows;
		}

		return $return_val;
	}

	/**
	 * 數據插入
	 * <code>
	 * YCMSDB::insert( 'table', array( 'column' => 'foo', 'field' => 'bar' ) )
	 * YCMSDB::insert( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( '%s', '%d' ) )
	 * </code>
	 * @see YCMSDB::__insert_replace_helper
	 * @param string $table
	 * @param array $data
	 * @param array $format
	 */
	function insert( $table, $data, $format = null ) {
		return $this->__insert_replace_helper( $table, $data, $format, 'INSERT' );
	}

	/**
	 * 數據替換
	 * <code>
	 * YCMSDB::replace( 'table', array( 'column' => 'foo', 'field' => 'bar' ) )
	 * YCMSDB::replace( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( '%s', '%d' ) )
	 * </code>
	 * @see YCMSDB::__insert_replace_helper
	 * @param string $table
	 * @param array $data
	 * @param array $format
	 */
	function replace( $table, $data, $format = null ) {
		return $this->__insert_replace_helper( $table, $data, $format, 'REPLACE' );
	}

	/**
	 * 插入或者替换
	 * @param string $table
	 * @param array $data
	 * @param array|string $format
	 * @param string $type
	 * @return int|false The number of rows affected, or false on error.
	 */
	function __insert_replace_helper( $table, $data, $format = null, $type = 'INSERT' ) {
		if ( ! in_array( strtoupper( $type ), array( 'REPLACE', 'INSERT' ) ) ){
			return false;
		}
		$this->insert_id = 0;
		$formats = $format = (array) $format;
		$fields = array_keys( $data );
		$formatted_fields = array();
		foreach ( $fields as $field ) {
			if ( !empty( $format ) ){ //设置数据类型
				$form = ( $form = array_shift($formats) ) ? $form : $format[0];
			}elseif ( isset( $this->field_types[$field] ) ){  //固定数据
				$form = $this->field_types[$field];
			}else{
				$form = '%s'; //字符串
			}
			$formatted_fields[] = $form;
		}
		$sql = "{$type} INTO `$table` (`" . implode( '`,`', $fields ) . "`) VALUES (" . implode( ",", $formatted_fields ) . ")";
		return $this->query($this->prepare( $sql, $data ));
	}


	/**
	 *
	 * Enter description here ...
	 * @param string $table
	 * @param array $data
	 * @param array $where
	 * @param array $format
	 * @param array $where_format
	 */
	function update( $table, $data, $where, $format = null, $where_format = null ) {
		if ( ! is_array( $data ) || ! is_array( $where ) )
		return false;

		$formats = $format = (array) $format;
		$bits = $wheres = array();
		foreach ( (array) array_keys( $data ) as $field ) {
			if ( !empty( $format ) ){
				$form = ( $form = array_shift( $formats ) ) ? $form : $format[0];
			}else if ( isset($this->field_types[$field]) ){
				$form = $this->field_types[$field];
			}else{
				$form = '%s';
			}
			$bits[] = "`$field` = {$form}";
		}

		$where_formats = $where_format = (array) $where_format;
		foreach ( (array) array_keys( $where ) as $field ) {
			if ( !empty( $where_format ) ){
				$form = ( $form = array_shift( $where_formats ) ) ? $form : $where_format[0];
			}elseif ( isset( $this->field_types[$field] ) ){
				$form = $this->field_types[$field];
			}else{
				$form = '%s';
			}
			$wheres[] = "`$field` = {$form}";
		}

		$sql = "UPDATE `$table` SET " . implode( ', ', $bits ) . ' WHERE ' . implode( ' AND ', $wheres );
		return $this->query( $this->prepare( $sql, array_merge( array_values( $data ), array_values( $where ) ) ) );
	}

	function delete( $table, $where, $where_format = null ) {
		if ( ! is_array( $where ) ){
			return false;
		}

		$bits = $wheres = array();

		$where_formats = $where_format = (array) $where_format;

		foreach ( array_keys( $where ) as $field ) {
			if ( !empty( $where_format ) ) {
				$form = ( $form = array_shift( $where_formats ) ) ? $form : $where_format[0];
			} elseif ( isset( $this->field_types[ $field ] ) ) {
				$form = $this->field_types[ $field ];
			} else {
				$form = '%s';
			}

			$wheres[] = "$field = $form";
		}

		$sql = "DELETE FROM $table WHERE " . implode( ' AND ', $wheres );
		return $this->query( $this->prepare( $sql, $where ) );
	}
	/**
	 * 获取表中第x行y列的值
	 * @param string $query
	 * @param int $x
	 * @param int $y
	 */
	function get_var( $query = null, $x = 0, $y = 0 ) {
		$this->func_call = "\$db->get_var(\"$query\", $x, $y)";
		if ( $query ){
			$this->query( $query );
		}

		if ( !empty( $this->last_result[$y] ) ) {
			$values = array_values( get_object_vars( $this->last_result[$y] ) );
		}

		return ( isset( $values[$x] ) && $values[$x] !== '' ) ? $values[$x] : null;
	}

	/**
	 *获取一行
	 * @param string $query
	 * @param int $y
	 */
	function get_row($query = null,$output = ARRAY_A, $y = 0 ) {
		$this->func_call = "\$db->get_row(\"$query\",$output,$y)";

		if ( $query ){
			$this->query( $query );
		}else{
			return null;
		}
		if ( !isset( $this->last_result[$y] ) ){
			return null;
		}
		if ( $output == OBJECT ) {
			return $this->last_result[$y];
		} elseif ( $output == ARRAY_A ) {
			return get_object_vars( $this->last_result[$y] );
		} elseif ( $output == ARRAY_N ) {
			return  array_values( get_object_vars( $this->last_result[$y] ) );
		} else {
			$this->print_error( " \$db->get_row(string query, output type, int offset) -- Output type must be one of: OBJECT, ARRAY_A, ARRAY_N" );
		}
	}

	/**
	 * 获取一列
	 * @param string $query
	 * @param string $x
	 * @return unknown
	 */
	function get_col( $query = null , $x = 0 ) {
		if ( $query ){
			$this->query( $query );
		}

		$new_array = array();

		for ( $i = 0, $j = count( $this->last_result ); $i < $j; $i++ ) {
			$new_array[$i] = $this->get_var( null, $x, $i );
		}
		return $new_array;
	}

	function get_results( $query = null, $output = ARRAY_A ) {
		$this->func_call = "\$db->get_results(\"$query\", $output)";

		if ($query){
			$this->query( $query );
		}else{
			return null;
		}
		$new_array = array();
		if ( $output == OBJECT ) {
			return $this->last_result;
		} elseif ( $output == OBJECT_K ) {
			foreach ( $this->last_result as $row ) {
				$var_by_ref = get_object_vars( $row );
				$key = array_shift( $var_by_ref );
				if ( ! isset( $new_array[ $key ] ) )
				$new_array[ $key ] = $row;
			}
			return $new_array;
		} elseif ( $output == ARRAY_A || $output == ARRAY_N ) {
			if ( $this->last_result ) {
				foreach( (array) $this->last_result as $row ) {
					if ( $output == ARRAY_N ) {
						$new_array[] = array_values( get_object_vars( $row ) );
					} else {
						$new_array[] = get_object_vars( $row );
					}
				}
			}
			return $new_array;
		}
		return null;
	}


	function get_db_name($dbname){
		return DB_PREFIX . $dbname;
	}
	/**
	 * 设置数据库编码
	 * @param resource $dbh
	 * @param string $charset
	 * @param string $collate
	 */
	function set_charset($dbh, $charset = null, $collate = null){
		if ( !isset($charset) ){
			$charset = $this->charset;
		}
		if ( !isset($collate) ){
			$collate = $this->collate;
		}
		if ($this->has_cap( 'collation', $dbh ) && !empty( $charset ) ) {
			if (function_exists( 'mysql_set_charset' ) && $this->has_cap( 'set_charset', $dbh ) ) {
				mysql_set_charset( $charset, $dbh );
				$this->real_escape = true;
			} else {
				$query = $this->prepare( 'SET NAMES %s', $charset );
				if ( ! empty( $collate ) ){
					$query .= $this->prepare( ' COLLATE %s', $collate );
				}
				mysql_query( $query, $dbh );
			}
		}
	}

	/**
	 * 数据库select
	 * @param string $dbname
	 * @param resource $dbh
	 */
	function select($dbname, $dbh = null ) {
		if ( is_null($dbh) ){
			$dbh = $this->dbh;
		}
		if ( !@mysql_select_db( $dbname, $dbh ) ) {
			$this->ready = false;
			errorMsg("db_select_fail");
			return;
		}
	}

	/**
	 * make a safe sql
	 * 
	 * <code>
	 * YCMSDB::prepare( "SELECT * FROM `table` WHERE `column` = %s AND `field` = %d", 'foo', 1337 )
	 * YCMSDB::prepare( "SELECT DATE_FORMAT(`field`, '%%c') FROM `table` WHERE `column` = %s", 'foo' );
	 * </code>
	 * 
	 * @param string $query
	 * @param array|mixed $args
	 * @return null|false|string 替換後的sql 如果没有查询，则返回null 如果有错误和字符串 返回false，
	 */
	function prepare( $query, $args = null ) {
		if ( is_null( $query ) ){
			return;
		}

		if ( func_num_args() < 2 ){
			errorMsg("ycmsdb::prepare() requires at least two arguments.");
		}

		$args = func_get_args();
		array_shift($args); //unset $query;

		if ( isset( $args[0] ) && is_array($args[0]) ){
			$args = $args[0]; //set array to args
		}

		$query = str_replace( "'%s'", '%s', $query );
		$query = str_replace( '"%s"', '%s', $query );
		$query = preg_replace( '|(?<!%)%f|' , '%F', $query ); // Force floats to be locale unaware
		$query = preg_replace( '|(?<!%)%s|', "'%s'", $query ); // quote the strings, avoiding escaped strings like %%s
		array_walk($args, array($this, 'escape_by_ref' ) );
		return @vsprintf($query, $args);
	}

	function escape_by_ref( &$string ) {
		if ( ! is_float( $string ) ){
			$string = $this->_real_escape( $string );
		}
	}

	/**
	 * 用mysql_real_escape_string 或者 addslashes 过滤
	 * @param string $string
	 */
	function _real_escape( $string ) {
		if ( $this->dbh && $this->real_escape ){
			return mysql_real_escape_string( $string, $this->dbh );
		}else{
			return addslashes( $string );
		}
	}

	function flush() {
		$this->last_result = array();
		$this->last_query  = null;
		$this->rows_affected = 0;
		$this->last_error  = '';

		if (is_resource( $this->result )){
			mysql_free_result( $this->result );
		}
	}

	/**
	 * 确定数据库支持某一特性
	 * @param string $db_cap
	 * @return boolean
	 */
	function has_cap( $db_cap ) {
		$version = $this->db_version();

		switch ( strtolower( $db_cap ) ) {
			case 'collation' :    // @since 2.5.0
			case 'group_concat' : // @since 2.7
			case 'subqueries' :   // @since 2.7
				return version_compare($version, '4.1', '>=' );
			case 'set_charset' :
				return version_compare($version, '5.0.7', '>=');
		};

		return false;
	}


	function timer_start() {
		$this->time_start = microtime( true );
		return true;
	}

	function timer_stop() {
		return ( microtime( true ) - $this->time_start );
	}

	function print_error( $str = '' ) {
		global $SQL_ERROR;

		if ( !$str ){
			$str = mysql_error( $this->dbh );
		}
		$SQL_ERROR[] = array( 'query' => $this->last_query, 'error_str' => $str );

		if ( $this->suppress_errors ){
			return false;
		}

		error_log( $str );

		// 是否显示错误
		if ( ! $this->show_errors ){
			return false;
		}
		$str   = htmlspecialchars( $str, ENT_QUOTES );
		$query = htmlspecialchars( $this->last_query, ENT_QUOTES );

		print "<div id='error'>
			<p class='wpdberror'><strong>YCMS database query error:</strong> [$str]<br />
			<code>$query</code></p>
			</div>";
	}

	function db_version() {
		return preg_replace( '/[^0-9.].*/', '', mysql_get_server_info( $this->dbh ) );
	}
}