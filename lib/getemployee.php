<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
	
header("Content-type:text/html; charset=utf-8");        
header("Cache-Control: no-store, no-cache, must-revalidate");       
header("Cache-Control: post-check=0, pre-check=0", false);     
require_once('configure.php');

$mysqli = new mysqli($DB_HOSTNAME,$DB_USERNAME,$DB_PASSWORD);  
/* check connection */
if ($mysqli->connect_errno) {  
    printf("Connect failed: %s\n", $mysqli->connect_error);  
    exit();  
}  
if(!$mysqli->set_charset("tis620")) {  
    printf("Error loading character set tis620: %s\n", $mysqli->error);  
    exit();  
}
$mysqli->query("SET NAMES tis620");
$mysqli->select_db($DB_NAME);
			
if(isset($_GET['q']) && $_GET['q']!=""){
	isset($_REQUEST['lang']) ? $lang = $_REQUEST['lang'] : $lang = "th";	
	isset($_REQUEST['q']) ? $q =$_REQUEST['q'] : $q = "%";	
    
    $pagesize = 50; // จำนวนรายการที่ต้องการแสดง
	

	
    $sql = "SELECT FNAME_EN,FNAME_TH,STAFF_ID FROM EMPLOYEE_INFO WHERE LOCATE('$q', FNAME_EN) > 0 OR LOCATE('$q', FNAME_TH) > 0 ORDER BY LOCATE('$q', FNAME_TH) DESC LIMIT $pagesize"; 

	/*
	$a=iconv("tis-620", "utf-8",urldecode($q));;
	$b=urldecode(iconv("tis-620", "utf-8",$q));
	$c=urldecode(iconv("utf-8", "tis-620",$q));
	$d=urldecode($q);
	echo "<BR>a=".$a."<BR>b=".$b."<BR>q=".$q."<BR>c=".$c."<BR>d=".$d."<BR>";
	echo $sql;
	*/	
	
    $result = $mysqli->query($sql);
    if($result && $result->num_rows>0){
        while($row = $result->fetch_assoc()){
			
			$id = $row["STAFF_ID"];
            if($lang=="en") $display_name=$row["FNAME_EN"]; else $display_name=$row["FNAME_TH"];
            echo "
                <li class='common-leave mt-20' onselect=\"this.setText('".iconv("tis-620", "utf-8",$display_name)."').setValue('$id')\">
                    ".iconv("tis-620", "utf-8",$display_name)."
                </li>
            ";
        }
    }
    $mysqli->close();
}
?>