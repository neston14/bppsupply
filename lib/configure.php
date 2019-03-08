<?php
	$title="BPP SUPPLY CO.,LTD.";
	
	$DB_HOSTNAME="localhost";
	$DB_PORT=3306;
	$DB_USERNAME="safeourb_bppl";
	$DB_PASSWORD="tweety285083";
	$DB_NAME="safeourb_bppleave";
	$ADMIN_USERNAME="admin";
	$ADMIN_PASSWORD="=ypptadmin";

	function checkExisting($sql_check){
		$DB_HOSTNAME="localhost";
		$DB_PORT=3306;
		$DB_USERNAME="safeourb_bppl";
		$DB_PASSWORD="tweety285083";
		$DB_NAME="safeourb_bppleave";
		$temp=false;
		$con = mysqli_connect($DB_HOSTNAME,$DB_USERNAME,$DB_PASSWORD);
		if(!$con){echo "Not connect";}
		mysqli_select_db($con,$DB_NAME);
		mysqli_query($con,"SET NAMES tis620");	
		$resultExisting = mysqli_query($con,$sql_check);
		$numExisting = mysqli_num_rows($resultExisting);
		if ($numExisting > 0) {
			$temp=true;
		}else{
			$temp=false;		
		}
		return $temp;
	}

	function convertToThaiYear($temp){
		if($temp<2500){
			$return_text=$temp+543;
		}else{
			$return_text=$temp;
		}
		return $return_text;
	}
	
	function generateStaffID(){
		$DB_HOSTNAME="localhost";
		$DB_PORT=3306;
		$DB_USERNAME="safeourb_bppl";
		$DB_PASSWORD="tweety285083";
		$DB_NAME="safeourb_bppleave";
		$LAST_STAFF_ID="0001";
		$con = mysqli_connect($DB_HOSTNAME,$DB_USERNAME,$DB_PASSWORD);
		if(!$con){echo "Not connect";}
		mysqli_select_db($con,$DB_NAME);
		mysqli_query($con,"SET NAMES tis620");	
		$SQL_GET_LASTID="SELECT CAST(STAFF_ID AS UNSIGNED) AS LAST_STAFF_ID FROM `EMPLOYEE_INFO` ORDER BY CAST(STAFF_ID AS UNSIGNED) DESC LIMIT 1";
		
		$GET_SQL_RESULT = mysqli_query($con,$SQL_GET_LASTID);
		$GET_SQL_RECORD=mysqli_fetch_array($GET_SQL_RESULT);
		$LAST_STAFF_ID=sprintf("%04d", $GET_SQL_RECORD['LAST_STAFF_ID']+1);

		
		return $LAST_STAFF_ID;
			
	}
	
	function monthToText($temp,$lang){
		switch($temp) { 
			case '01': 
    		$return_month_th = 'มกราคม';
    		$return_month_en = 'January';
			break; 
			case '02': 
    		$return_month_th = 'กุมภาพันธ์';
    		$return_month_en = 'Febuary';
			break;  
			case '03': 
    		$return_month_th = 'มีนาคม';
    		$return_month_en = 'March';
			break;  
			case '04': 
    		$return_month_th = 'เมษายน';
    		$return_month_en = 'April';
			break;  
			case '05': 
    		$return_month_th = 'พฤษภาคม';
    		$return_month_en = 'May';
			break;  
			case '06': 
    		$return_month_th = 'มิถุนายน';
    		$return_month_en = 'June';
			break;  
			case '07': 
    		$return_month_th = 'กรกฏาคม';
    		$return_month_en = 'July';
			break;  
			case '08': 
    		$return_month_th = 'สิงหาคม';
    		$return_month_en = 'August';
			break;  
			case '09': 
    		$return_month_th = 'กันยายน';
    		$return_month_en = 'September';
			break;  
			case '10': 
    		$return_month_th = 'ตุลาคม';
    		$return_month_en = 'October';
			break;  
			case '11': 
    		$return_month_th = 'พฤศจิกายน';
    		$return_month_en = 'November';
			break;  
			case '12': 
    		$return_month_th = 'ธันวาคม';
    		$return_month_en = 'December';
			break; 
		}
		if($lang=="th"){
			return $return_month_th; 
		}else{
			return $return_month_en; 
		}
			
			
	}
	
	function dateFormat($temp,$lang){
		$dateText="";
		if($lang=="en"){
			$dTemp = new DateTime($temp);
			$dateText=date_format($dTemp,"d F Y");
		}else{
			$dTemp = new DateTime($temp);
			$dayTemp=date_format($dTemp,"d");
			$monthTemp=date_format($dTemp,"m");
			$yearTemp=date_format($dTemp,"Y");
			
			if(intval($yearTemp)<=2500) $year=intval($yearTemp)+543; else $year=$yearTemp;
			
			if(intval($monthTemp)<10) $month="0".$monthTemp; else $month=$monthTemp;
			
			$dateText=$dayTemp." ".monthToText($month,$lang)." ".$year;
		}
		
		return $dateText;
	}
	
?>