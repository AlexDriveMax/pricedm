<?

  error_reporting(E_ALL & ~E_WARNING& ~E_DEPRECATED);
class DBAndrey {

  public function __construct($config=array())
  {
  	if (!isset($config['host'])) {$config['host']='localhost';   }
  	if (!isset($config['user'])) {$config['user']="root";}
  	if (!isset($config['pass'])) {$config['pass']="";}
  	if (!isset($config['port'])) {$config['port']="";}

  	if (!isset($config['charset'])) {$config['charset']="utf8";}

    $this->tpf = "";
				if (isset($config['tpf'])) {$this->tpf = $config['tpf'];}
    $this->postfixOn();

    $this->connect($config);
  }


  public function reConnect($config)
  {
    mysqli_close();
    $this->connect($config);
  }

  public function connect($config)
  {
    $this->link = mysqli_connect($config['host'],$config['user'],      $config['pass'],$config['port']);

  	mysqli_select_db($this->link, $config['dbname']);

    mysqli_set_charset($this->link, $config['charset']);

  	//mysqli_query($this->link, "SET NAMES 'utf8mb4'");

  }

  public function postfixOff()
  {
    $this->tablePostfix='';
  }

  public function postfixOn()
  {
    $this->tablePostfix=$this->tpf;
  }

  public function qSelectRow($q)
  {
  	$result = $this->mysqli_query__($q);
  	$row = mysqli_fetch_assoc($result);
  	if (!$row) {$row=array();}
    mysqli_free_result($result);
  	return $row;

  }

  public function qSelectField($q, $fName)
  {
  	$result = $this->mysqli_query__($q);
  	$row = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
  	return $row[$fName];
  }

  public function qSelectList($q)
  {
  	$result = $this->mysqli_query__($q);
  	while ($row = mysqli_fetch_assoc($result)) {
  		$rows[] = $row;
  	}
    if (!$rows) {$rows=array();}
    mysqli_free_result($result);

  	return $rows;
  }

  public function qAssoc($q)
  {
  	$this->result = $this->mysqli_query__($q);
  }

  public function qAssocUseResult($q)
  {
  	$this->result = $this->mysqli_query_use_result($q);
  }

  public function rowAssoc()
  {
   $row = mysqli_fetch_assoc($this->result);
  	return $row;
  }

  public function mysqli_query__($q)
  {

  	$result = mysqli_query($this->link, $q);

  	if (!$result) {
  		echo 'Не могу выполнить запрос: ' . mysqli_error($this->link);
  		exit;
  	}
  	return $result;
  }


  public function mysqli_query_use_result($q){
  	$result = mysqli_query($this->link, $q, MYSQLI_USE_RESULT);

  	if (!$result) {
  		echo 'Не могу выполнить запрос: ' . mysqli_error($this->link);
  		exit;
  	}
  	return $result;
  }

  //////////////////////////////////
  /////////////////Генерация кода sql
  ///////////////////////////////////////

  function genInsert($table, $params)
  {
  	$table = $table.$this->tablePostfix;

  	foreach ($params as $column=>$value) {

      if ($i) {$sep=', ';} else {$sep='';}

     	$cols=$cols.$sep."`".$column."`";
     	$vals=$vals.$sep.$this->qFormatVal($value);
  		$i++;
  	;}

  	$query="INSERT into $table ($cols) VALUES ($vals);";

  	return $query;

  }

  function genUpdate($table, $params, $nameField=false, $id)
  {
  	$table = $table.$this->tablePostfix;

  	foreach ($params as $column=>$value) {

      if ($i) {$sep=', ';} else {$sep='';}

  		$query0.=$sep.$column.'='.$this->qFormatVal($value)  ;
  		$i++;
  	;}

    $query ="UPDATE $table SET $query0 ";
    if ($id==='vse') {
  	;}elseif($nameField){
    	$query.="WHERE $nameField='$id' "
  	;}else{
      $query.="WHERE id='$id' ";
  	}
  	return $query;
  }

  function genUpdate2($table, $params, $id, $nameField=false)
  {
  	$table = $table.$this->tablePostfix;

  	foreach ($params as $column=>$value)
    {
          if ($i) {$sep=', ';} else {$sep='';}
          $column="`$column`";
      		$query0.=$sep.$column.'='.$this->qFormatVal($value)  ;
      		$i++;
  	;}

    $query ="UPDATE $table SET $query0 ";

  	if ($id!=='vse')
    {

  		$where.="WHERE ";

  		if (is_array($id))
      {
  			$operator='AND ';
  			foreach ($id as $column=>$value) {
  				if ($i2) {$sep=$operator;} else {$sep='';}
          $column="`$column`";
  				$value=$this->qFormatVal($value);

  				$where.=$sep.$column.' = '.$value  ;
  				$i2++;
  			;}

  		;} else {
  			if ($nameField) {$where.="$nameField= ";;} else {$where.="id = ";};
  				$where.= $this->qFormatVal($id);
  		;};
  		$query.=$where;
  	;}


  	return $query;
  }

  function whereAdd($column, $value, $znak=false,$operator=false)
  {
    $this->where[]=array(
    'column'=>$column,
    'value'=>$value,
    'znak'=>$znak,
    'operator'=>$operator,
    )  ;
  }

  function where()
  {
    $where['fromFunction']=1;
    $where['where']=$this->where;
    return $where;
  }

  function whereClean(){
    $this->where=false;
  }


  function genDelete($table, $params=false, $operator=false)
  {

  	$table = $table.$this->tablePostfix;

    if (!$operator) {$operator=' = ';}

    if ($params) {
  	foreach ($params as $column=>$value)
    {

      if ($i) {$sep=' AND ';} else {$sep='';}

      $value=$this->qFormatVal($value);

  		$where.=$sep.$column.$operator.$value  ;
  		$i++;
  	}
  }

     $query="DELETE from $table ";
     if ($where) {$query.="where $where";}


  	return $query;

  }





  function qFormatVal($value){

  	$value=trim($value);//сомнительно

  	if ($value!='NOW()' AND  $value!="NULL" AND $value!='UNIX_TIMESTAMP()' AND $value!='SYSDATE()' AND $value!='TIMESTAMP()' )
    {
      $value="'".mysqli_real_escape_string($this->link, $value)."'";
  ;}
  	return $value;
  }


  function genSelectBase($table, $selectParams=false, $whereP=false, $orderBy=false, $orderDesc=false, $limitBegin=false, $limitNumber=false, $operator=false, $operator2=false,$znak=false)
   {

  	$table = $table.$this->tablePostfix;

    if (!$operator) {$operator='AND';}
    if (!$znak) {$znak_='= ';}
  	$operator=" $operator ";

    if ($whereP)
    {

  			foreach ($whereP as $column=>$value) {

  		    if ($i) {$sep=$operator;} else {$sep='';}

        $column=str_replace("*","",$column);
  		    $value=$this->qFormatVal($value);

        if ($znak) {
         if ($znak[$column]) {
          $znak_ = $znak[$column];
         }else{
          $znak_ = "= ";
         }
        }

  				$where.=$sep.$column.$znak_.$value  ;
  				$i++;

  			}

  	 }

  if ($selectParams)
  {

  	if (is_array($selectParams)) {
  		$select=implode(', ',$selectParams);
  	} else {
  		$select=$selectParams;
  	}

  ;} else {

  	$select='*';

  ;} ;

    $query ="SELECT $select FROM $table ";

    if ($where) {$query.="WHERE $where "; }

    if ($orderDesc) {
      $desc="DESC ";
      if (!$orderBy) {$orderBy='id';}
    }

    if ($orderBy) {$query.="ORDER BY $orderBy $desc ";}

    if (is_numeric($limitBegin) OR is_numeric($limitNumber))
    {

      if (!is_numeric($limitBegin))
      {
       $limitBegin=0;
      }

      if (is_numeric($limitNumber)) {$limitNumber=", $limitNumber";}
     $query.="LIMIT $limitBegin $limitNumber ";

    }

  	return $query;

  }



  function genSelect(
  $table,
  $whereParams=false,
  $operator=false,
  $operator2=false,
  $order=false)
  {

  	$query = $this->genSelectBase($table, $selectParams=false, $whereParams, $order, $limitBegin=false, $limitNumber=false, $operator=false, $operator2=false);

  	return $query;

  }


  function genSelect2(
  $table,
  $whereParams=false,
  $order=false,
  $limitBegin=false,
  $limitNumber=false,
  $operator=false,
  $operator2=false
  )
  {

  	$query = $this->genSelectBase($table, $selectParams=false, $whereParams, $order, $limitBegin, $limitNumber, $operator, $operator2);

  	return $query;

  }




  function genSelect3(
  $table,
  $whereParams=false,
  $selectParams=false,
  $params=false
  )
  {

  if ($params AND is_array($params))
  {
  $znak=$params['znak'];
  $orderBy=$params['orderBy'];
  $orderDesc=$params['desc'];
  $limitBegin=$params['limitBegin'];
  $limitNumber=$params['limitNumber'];
  $operator=$params['operator'];
  $operator2=$params['operator2'];
  }

  	$query = $this->genSelectBase($table, $selectParams, $whereParams, $orderBy,$orderDesc, $limitBegin, $limitNumber, $operator, $operator2,$znak);

  	return $query;

  }


  function rows($table, $whereParams=false, $order=false, $limitBegin=false, $limitNumber=10, $operator=false, $operator2=false)
  {
  	$this->callExt=1;

  	$q = $this->rows_($table, $whereParams, $order, $limitBegin, $limitNumber, $operator, $operator2);

  	$list = $this->qSelectList($q);

  	return $list;
  }


  function rows_($table, $whereParams=false, $order=false, $limitBegin=false, $limitNumber=10, $operator=false, $operator2=false)
  {

  	$q = $this->genSelectBase($table, $selectParams=false, $whereParams, $order, $limitBegin, $limitNumber, $operator, $operator2);

  	$callExt=$this->callExt;
  	$this->callExt=0;
  	if ($callExt) {
  		return $q;
  	;} else {
  		print_r($q); print_r('<br/>');
  	;}

  }

  function row($table, $whereParams=false, $operator=false, $operator2=false)
  {

  	$this->callExt=1;

  	$q = $this->row_($table, $whereParams, $operator, $operator2);

  	$row = $this->qSelectRow($q);

  	return $row;

  }

  function row_($table, $whereParams=false, $operator=false, $operator2=false)
  {


  	$q = $this->genSelectBase($table, $selectParams=false, $whereParams, false, false, false, $operator, $operator2);


  	$callExt=$this->callExt;
  	$this->callExt=0;
  	if ($callExt) {
  		return $q;
  	;} else {
  		print_r($q); print_r('<br/>');
  	;} ;


  }

  function field($table, $whereParams=false, $fName, $operator=false, $operator2=false){

  	$this->callExt=1;

  	$q = $this->field_($table, $whereParams, $fName, $operator, $operator2);

  	$field = $this->qSelectField($q, $fName);

  	return $field;

  }

  function field_($table, $whereParams=false, $fName, $operator=false, $operator2=false){

  	$q = $this->genSelectBase($table, $selectParams=false, $whereParams, false, false, false, $operator, $operator2);


  	$callExt=$this->callExt;
  	$this->callExt=0;
  	if ($callExt) {
  		return $q;
  	;} else {
  		print_r($q); print_r('<br/>');
  	;} ;

  }

  function insert($table, $params)
  {
    $q = $this->genInsert($table, $params);
    $this->mysqli_query__($q);
    return mysqli_insert_id($this->link);
  }

  function count($table, $whereParams=false, $operator=false, $operator2=false)
  {
  	$query = $this->genSelectBase($table, 'COUNT(*)', $whereParams, $order, $limitBegin=false, $limitNumber=10, $operator=false, $operator2=false);
  	$count = $this->qSelectField($query, 'COUNT(*)');
  	return $count;
  }


  function qFormat($q)
  {
  	$q=preg_replace('/\s##([0-9a-zA-Z_-]{1,35})\s/su'," $1{$this->tablePostfix} ",$q);
  	return $q;
  }



  function fv($value)
  {
  	$value=$this->qFormatVal($value);
  	return $value;
  }


  public function q($q)
  {
  	return $this->mysqli_query__($q);
  }

  public function insertedID()
  {
   $id = mysqli_insert_id($this->link);
   return $id;
  }


}

/*
  $db = new dbAndrey(['dbname'=>'wp52']);

  все примеры в библиотеке RapidPHP

*/











?>