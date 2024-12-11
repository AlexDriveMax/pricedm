<?php
class CsvReader
{
    private $file;
    private $delimiter; 
    private $length;
    private $handle; 
    private $csvArray; 
    
    public function __construct($file, $delimiter=";", $length = 80000)
    {
       $this->file = $file; 
       $this->length = $length;
       $this->delimiter = $delimiter; 
       $this->FileOpen(); 
    } 
    public function __destruct() 
    { 
       $this->FileClose(); 
    } 
    public function GetCsv()
    {
        $this->SetCsv();
        if(is_array($this->csvArray)) 
         return $this->csvArray;
    }
    
    private function SetCsv()
    {
        if($this->GetSize())
        {
            while (($data = @fgetcsv($this->handle, $this->length, $this->delimiter)) !== FALSE)
            {
                $this->csvArray[] = $data;
            }
        }
    }
    private function FileOpen()
    {
        $this->handle=($this->IsFile())?fopen($this->file, 'r'):null;
    }
    private function FileClose()
    {
        if($this->handle) 
         @fclose($this->handle); 
    }
    private function GetSize()
    {
        if($this->IsFile())
            return (filesize($this->file));
        else
            return false;
    }
    private function IsFile()
    {
        if(is_file($this->file) && file_exists($this->file))
            return true;
        else
            return false;
    }
} 

class CsvWriter
{
    private $file;
    private $delimiter;
    private $array;
    private $handle;
    public function __construct($file, $array, $delimiter=";")
    {
        $this->file = $file; 
        $this->array = $array; 
        $this->delimiter = $delimiter;
        $this->FileOpen();
    }
    public function __destruct()
    {
        $this->FileClose();
    }
    public function GetCsv()
    {
        $this->SetCsv();
    }
    
    private function IsWritable()
    {
        if(is_writable($this->file))
            return true;
        else
            return false;
    }
    private function SetCsv() 
    { 
      if($this->IsWritable())
      {
          $content = ""; 
          foreach($this->array as $ar) 
          { 
             $content .= implode($this->delimiter, $ar);
             $content .= "\r\n"; 
          } 
          if (fwrite($this->handle, $content) === FALSE) 
                 exit;
      }
    }
    private function FileOpen()
    {
        $this->handle=fopen($this->file, 'w+');
    }
    private function FileClose()
    {
        if($this->handle) 
         @fclose($this->handle); 
    } 
}


//проблема с кодировкой, решение
function getWords(){

 $read = new CsvReader('lang.csv');
 $langWords = $read->GetCsv();

 foreach($langWords as $i=>$wordLine){
    foreach($wordLine as $i2=>$word){
       $word = mb_convert_encoding($word, "utf-8", "windows-1251");
       $langWords2[$i][$i2]=$word ;
    }

 ;}
    return $langWords2;
;}



/*

Создание
$array = array(array('1','1','1'), array('2','2','2'), array('3','3','3'));
$dd = new CsvWriter('test.txt',$array);
$dd->GetCsv();

 Чтение

     $read = new CsvReader('21.csv',',');
		$rows = $read->GetCsv();
		foreach($rows as $i=>$row){
      print_r($row); print_r('<br/>');
		}

*/




?> 