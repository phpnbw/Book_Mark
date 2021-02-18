<?php
class Mysqls{
	private $host = DB_HOST;//数据库地址 localhost:/tmp/mysql3314.sock   192.168.1.114:3314
	private $username = DB_USERNAME;//用户名
	private $password = DB_PASSWORD;//密码
	private $dbname = DB_NAME;//数据库名
	private $dblink;//数据库连接
	//配置slave数据库，没有则留空
	private $slave_host = DB_SLAVE_HOST;//slave数据库地址192.168.1.116:3314
	private $slave_username = DB_SLAVE_USERNAME;//slave用户名
	private $slave_password = DB_SLAVE_PASSWORD;//slave密码
	private $slave_dbname = DB_SLAVE_NAME;//slave数据库名
	function __construct($is_slave=false,$p=array()){
		if(!$this->dblink){//只有执行sql的时候才有数据库链接
			if($p['host'])
			{
				$this->host=$p['host'];
				$this->username=$p['username'];
				$this->password=$p['password'];
				$this->dbname=$p['dbname'];	
			}	
			$this->dblink = mysqli_connect($this->host,$this->username,$this->password) or die('连接失败:' .$this->host. mysqli_error());
			mysqli_select_db($this->dblink,$this->dbname) or die('连接失败:'.mysqli_error());
			mysqli_query($this->dblink,"set names utf8");

		}
		if(!$this->dblink2){//只有执行sql的时候才有数据库链接
			$this->dblink2 = mysqli_connect($this->slave_host,$this->slave_username,$this->slave_password) or die('连接失败:' .$this->host. mysqli_error());
			mysqli_select_db($this->dblink2,$this->slave_dbname) or die('连接失败:'.$this->host.mysqli_error());
			mysqli_query($this->dblink2,"set names utf8");
		}
	}
	
	function query($sql){
		if(!$this->dblink){//只有执行sql的时候才有数据库链接
			$this->dblink = mysql_connect($this->host,$this->username,$this->password) or die('连接失败:' . mysql_error());
			mysqli_select_db($this->dblink,$this->dbname) or die('连接失败:'.mysqli_error());
			mysqli_query($this->dblink,"set names utf8");
		}
		return mysqli_query($this->dblink,$sql);
	}
	function query2($sql){
		if(!$this->dblink2){//只有执行sql的时候才有数据库链接
			$this->dblink2 = mysqli_connect($this->slave_host,$this->slave_username,$this->slave_password) or die('连接失败:' . mysql_error());
			mysqli_select_db($this->dblink,$this->dbname) or die('连接失败:'.mysqli_error());
			mysqli_query($this->dblink,"set names utf8");
		}
		$a=mysqli_query($this->dblink2,$sql);
		return $a;
	}
	function insertId($sql=false){
		return mysqli_insert_id($this->dblink);
	}
	function getRow($sql){	//取出一条数据
		$query = $this->query2($sql);
		if($query){
			$data = mysqli_fetch_assoc($query);
		}
		return $data;
	}
	function getRows($sql){		//取出多条数据
		$query = $this->query2($sql);
		$i=0;
		$data = array();
		while($row=mysqli_fetch_array($query,MYSQLI_ASSOC)){
			$data[$i] = $row;
			$i++;
		}
		return $data;
	}
	function insert($table, $data, $return = false,$debug=false) {//插入数据,debug为真返回sql
		if(!$table) {
			return false;
		}
		$fields = array();
		$values = array();
		foreach ($data as $field => $value){
			$fields[] = '`'.$field.'`';
			$values[] = "'".addslashes($value)."'";
		}
		if(empty($fields) || empty($values)) {
			return false;
		}
		$sql = 'INSERT INTO `'.$table.'` 
				('.join(',',$fields).') 
				VALUES ('.join(',',$values).')';
		if($debug){
			return $sql;
		}
		$query = $this->query($sql);
	        return $return ? $this->insertId() : $query;
	}
	function update($table, $condition, $data, $limit = 1) {//更新数据
		if(!$table) {
			return false;
		}
		$set = array();
		foreach ($data as $field => $value) {
			if(strpos($value, '`')!==false)
			{//表示表的字段的增长
			$set[] = '`'.$field.'`='.addslashes($value);	
			}else
			{
			$set[] = '`'.$field.'`='."'".addslashes($value)."'";
			}
		}
		if(empty($set)) {
			return false;
		}
		$sql = 'UPDATE `'.$table.'` 
				SET '.join(',',$set).' 
				WHERE '.$condition.' '.
				($limit ? 'LIMIT '.$limit : '');
		return $this->query($sql);
	}
}
