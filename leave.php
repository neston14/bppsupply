<?php\
// builds 2

	include("header.php");
	isset($_REQUEST['action']) ? $action = $_REQUEST['action'] : $action = "";
?>


			<!-- Start Banner Area -->
			<section class="generic-banner relative">
				<div class="overlay overlay-bg"></div>
				<div class="container">
					<div class="row height align-items-center justify-content-center">
						<div class="col-lg-10">
							<div class="banner-content text-center">
								<h2><?php echo $company_name?> Leave Management</h2>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- End Banner Area -->

<?php

/*####################################################################################################################
############################################# INSERT LEAVE RECORD SECTION ############################################
######################################################################################################################*/	
	
	if($action=="create"){
		isset($_POST['staff_id']) ? $staff_id = $_POST['staff_id'] : $staff_id = "";
		isset($_POST['fname']) ? $fname = $_POST['fname'] : $fname = "";
		isset($_POST['sdate']) ? $sdate = $_POST['sdate'] : $sdate = "";
		$dStart = new DateTime($sdate);
		
		$dateText=date_format($dStart,"d F Y");
		
		isset($_POST['stime']) ? $stime = $_POST['stime'] : $stime = "";
		isset($_POST['ltype']) ? $ltype = $_POST['ltype'] : $ltype = "";
		isset($_POST['email']) ? $email = $_POST['email'] : $email = "";
		isset($_POST['note']) ? $note = $_POST['note'] : $note = "";
		
		$stimeText="Full Day";
		if($stime=="H"){
			//$leaveDay=$leaveDay-0.5;
			$stimeText="Half Day";
		}

		$con = mysqli_connect($DB_HOSTNAME,$DB_USERNAME,$DB_PASSWORD);
		if(!$con){echo "Not connect";}
		mysqli_select_db($con,$DB_NAME);
		mysqli_query($con,"SET NAMES tis620");
		
		//$CHECK_EXISTING_SQL1="select * from `LEAVE_INFO` where STAFF_ID like '$staff_id' and LEAVE_DATE like '$sdate' and (LEAVE_TIME like '$stime' or LEAVE_TIME like 'F');";
		
//		if(checkExisting($CHECK_EXISTING_SQL1)){
//			$OUTPUT_TEXT1="<h2 class='text-white'>Your Leave Detail<span> is already exist.</span></h2>";
//			$OUTPUT_TEXT2="<p class='text-white'>For More information how to create leave report. <a href=''>Click here</a></p>";			
//		}else{
			$CHECK_EXISTING_SQL2="select * from `LEAVE_INFO` where STAFF_ID like '$staff_id' and LEAVE_DATE like '$sdate'";
			if(checkExisting($CHECK_EXISTING_SQL2)){
				$OUTPUT_TEXT1="<h2 class='text-white'>Your Leave Detail<span> have been updated</span></h2>";
				$OUTPUT_TEXT2="<p class='text-white'>For go back to create leave record. Please <a href='leave.php'>Click here</a>.</p>";
				
				$GET_LEAVE_RESULT = mysqli_query($con,$CHECK_EXISTING_SQL2);
				$GET_LEAVE_RECORD=mysqli_fetch_array($GET_LEAVE_RESULT);
				$OLD_LEAVE_TIME=$GET_LEAVE_RECORD['LEAVE_TIME'];
				$OLD_LEAVE_DATE=$GET_LEAVE_RECORD['LEAVE_DATE'];
//				if($lang=="th"){
//					if($OLD_LEAVE_TIME=="F") $OLD_LEAVE_TIME_TEXT="ลาเต็มวัน"; else  $OLD_LEAVE_TIME_TEXT="ลาครึ่งวัน";
//				}else{
					if($OLD_LEAVE_TIME=="F") $OLD_LEAVE_TIME_TEXT="Full Day"; else  $OLD_LEAVE_TIME_TEXT="Half Day";
//				}
				$OLD_LEAVE_DATE_TMP = new DateTime($OLD_LEAVE_DATE);
				$OLD_LEAVE_DATE_TEXT=date_format($OLD_LEAVE_DATE_TMP,"d F Y");
				
				
				
				$RECORD_DETAIL="<div class='col-lg-10'><font color='#000000'>Old Leave Date : $OLD_LEAVE_DATE_TEXT ($OLD_LEAVE_TIME_TEXT)</font></div>";
				$RECORD_DETAIL=$RECORD_DETAIL."<div class='col-lg-10'><font color='#000000'>New Leave Date : $dateText ($stimeText)</font></div>";
				
				$UPDATE_LEAVE_INFO="UPDATE `LEAVE_INFO` set LEAVE_DATE='$sdate',LEAVE_TIME='$stime' where STAFF_ID like '$staff_id' and LEAVE_DATE like '$sdate'";
				//echo $UPDATE_LEAVE_INFO;
				mysqli_query($con,$UPDATE_LEAVE_INFO);	
				
			}else{
		
		
				$INSERT_LEAVE_INFO="INSERT INTO `LEAVE_INFO` (`STAFF_ID`, `LEAVE_DATE`, `LEAVE_TYPE`, `LEAVE_TIME`) VALUES ('$staff_id', '$sdate', '$ltype', '$stime');";
				mysqli_query($con,$INSERT_LEAVE_INFO);			
				$OUTPUT_TEXT1=$leaveadd_header;		
				$OUTPUT_TEXT2=$leaveadd_header_detail;
				
				$RECORD_DETAIL="<div class='col-lg-10'><font color='#000000'>Leave Date : $dateText ($stimeText)</font></div>";
				$RECORD_DETAIL=$RECORD_DETAIL."";
			
			}
//		}
?>				
		<section id='contactus' class="contact-area pt-100 pb-100 relative">
			<div class="overlay overlay-bg"></div>
			<div class="container">
				<div class="row justify-content-center text-center">
					<div class="single-contact col-lg-10 col-md-8">
						<?php echo $OUTPUT_TEXT1;?>
						<?php echo $OUTPUT_TEXT2;?>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col-lg-10"><BR></div>
					<?php echo $RECORD_DETAIL;?>
				</div>
			</div>
		</section>			
		
<?php
/*####################################################################################################################
###################################################### ADD EMPOLYEE SECTION ##########################################
######################################################################################################################*/	
	}elseif($action=="addempconfirm"){
		isset($_POST['staff_id']) ? $staff_id = $_POST['staff_id'] : $staff_id = "";
		isset($_POST['fname_en']) ? $fname_en = $_POST['fname_en'] : $fname_en = "";
		isset($_POST['fname_th']) ? $fname_th = $_POST['fname_th'] : $fname_th = "";
		isset($_POST['email']) ? $email = $_POST['email'] : $email = "";
		

		$con = mysqli_connect($DB_HOSTNAME,$DB_USERNAME,$DB_PASSWORD);
		if(!$con){echo "Not connect";}
		mysqli_select_db($con,$DB_NAME);
		mysqli_query($con,"SET NAMES tis620");
		
		$CHECK_EXISTING_SQL="select * from `EMPLOYEE_INFO` where FNAME_EN like '$fname_en' or FNAME_TH like '$fname_th'";
		$CHECK_EXISTING_SQL2="select * from `EMPLOYEE_INFO` where STAFF_ID like '$staff_id'";
		if(checkExisting($CHECK_EXISTING_SQL)){
			$OUTPUT_TEXT1="<h2 class='text-white'>Your employee Detail<span> is already exists.</span></h2>";
			$OUTPUT_TEXT2="<p class='text-white'>Please go back to check employee information. <a href='leave.php?action=addemp'>Click here</a></p>";
			$RECORD_DETAIL="<div class='col-lg-10'><font color='#000000'><u>EXISTING INFORMATION IN DATABASE</u></font></div>";
			$RECORD_DETAIL=$RECORD_DETAIL."<div class='col-lg-10'><font color='#000000'>Employee Thai Name: </font></div>";
			$RECORD_DETAIL=$RECORD_DETAIL."<div class='col-lg-10'><font color='#000000'>Employee English Name: </font></div>";
			$RECORD_DETAIL=$RECORD_DETAIL."<div class='col-lg-10'><font color='#000000'>Employee Email: </font></div>";
			$RECORD_DETAIL=$RECORD_DETAIL."<div class='col-lg-10'><font color='#000000'>Employee Staff ID: </font></div>";
				
		}elseif(checkExisting($CHECK_EXISTING_SQL2)){
			$OUTPUT_TEXT1="<h2 class='text-white'>Your employee 'STAFF ID'<span> is already exists.</span></h2>";
			$OUTPUT_TEXT2="<p class='text-white'>Please go back to check employee information. <a href='leave.php?action=addemp'>Click here</a></p>";
			$RECORD_DETAIL="<div class='col-lg-10'><font color='#000000'><u>EXISTING INFORMATION IN DATABASE</u></font></div>";
			$RECORD_DETAIL=$RECORD_DETAIL."<div class='col-lg-10'><font color='#000000'>Employee Thai Name: </font></div>";
			$RECORD_DETAIL=$RECORD_DETAIL."<div class='col-lg-10'><font color='#000000'>Employee English Name: </font></div>";
			$RECORD_DETAIL=$RECORD_DETAIL."<div class='col-lg-10'><font color='#000000'>Employee Email: </font></div>";
			$RECORD_DETAIL=$RECORD_DETAIL."<div class='col-lg-10'><font color='#000000'>Employee Staff ID: </font></div>";
				
		}else{
			
			if($staff_id==""){
				$staff_id_gen=generateStaffID();
			}else{
				$staff_id_gen=$staff_id;
			}
		
			$INSERT_LEAVE_INFO="INSERT INTO `EMPLOYEE_INFO`(`FNAME_TH`, `EMAIL`, `STAFF_ID`, `FNAME_EN`) VALUES ('$fname_th', '$email', '$staff_id_gen', '$fname_en');";
			echo $INSERT_LEAVE_INFO;
			mysqli_query($con,$INSERT_LEAVE_INFO);			
			
			$OUTPUT_TEXT1="<h2 class='text-white'>Your employee Detail<span> is inserted successfully.</span></h2>";
			$OUTPUT_TEXT2="<p class='text-white'>Please go back to check employee information. <a href='leave.php?action=addemp'>Click here</a></p>";
			$RECORD_DETAIL="<div class='col-lg-10'><font color='#000000'><u>INFORMATION RECORDED</u></font></div>";
			$RECORD_DETAIL=$RECORD_DETAIL."<div class='col-lg-10'><font color='#000000'>Employee Thai Name: $fname_th</font></div>";
			$RECORD_DETAIL=$RECORD_DETAIL."<div class='col-lg-10'><font color='#000000'>Employee English Name: $fname_en</font></div>";
			$RECORD_DETAIL=$RECORD_DETAIL."<div class='col-lg-10'><font color='#000000'>Employee Email: $email</font></div>";
			$RECORD_DETAIL=$RECORD_DETAIL."<div class='col-lg-10'><font color='#000000'>Employee Staff ID: $staff_id_gen</font></div>";
			
		}
?>				
		<section id='contactus' class="contact-area pt-100 pb-100 relative">
			<div class="overlay overlay-bg"></div>
			<div class="container">
				<div class="row justify-content-center text-center">
					<div class="single-contact col-lg-10 col-md-8">
						<?php echo $OUTPUT_TEXT1;?>
						<?php echo $OUTPUT_TEXT2;?>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col-lg-10"><BR></div>
					<?php echo $RECORD_DETAIL;?>
				</div>
			</div>
		</section>			
		
<?php
/*####################################################################################################################
###################################################### ADD EMPOLYEE SECTION ##########################################
######################################################################################################################*/	
	}elseif($action=="addemp"){
?>		
			<section id='contactus' class="contact-area pt-100 pb-100 relative">
				<div class="overlay overlay-bg"></div>
				<div class="container">
					<div class="row justify-content-center text-center">
						<div class="single-contact col-lg-10 col-md-8">
							<h2 class='text-white'>Please enter <span>Employee Detail</span></h2>
							<p class='text-white'>For go back to create leave record. Please <a href='leave.php'>Click here</a>.</p>
						</div>
					</div>
					<form action="leave.php?action=addempconfirm" method="post">
					<div class="row justify-content-center">
						<div class="col-lg-10"><BR></div>
						<div class="col-lg-5">
							<font color='#000000'>Enter Full Name (Thai)</font><br>
							<input name="fname_th" class="common-leave mt-20" required="" type="text">
						</div>
						<div class="col-lg-5">
							<font color='#000000'>Enter Full Name (English)</font><br>
							<input name="fname_en" class="common-leave mt-20" required="" type="text">
						</div>
						<div class="col-lg-10"><BR></div>
						<div class="col-lg-5">
							<font color='#000000'>Staff ID</font><br>
							<input name="staff_id" class="common-leave mt-20" type="text">
						</div>
						<div class="col-lg-5">
							<font color='#000000'>Enter Email:</font><br>
							<input name="email" class="common-leave mt-20" required="" type="text">
						</div>
						<div class="col-lg-10"><BR></div>
						<div class="col-lg-10">
							<button type='submit' value='Submit'>ADD EMPLOYEE</button>
						</div>
					</div>
					</form>
				</div>
			</section>
<?php	
/*####################################################################################################################
###################################################### GEN REPORT SECTION ############################################
######################################################################################################################*/	
	}elseif($action=="genreport"){
			isset($_POST['staff_id']) ? $staff_id = $_POST['staff_id'] : $staff_id = "";
			isset($_POST['month']) ? $month = $_POST['month'] : $month = "";
			$con = mysqli_connect($DB_HOSTNAME,$DB_USERNAME,$DB_PASSWORD);
			if(!$con){echo "Not connect";}
			mysqli_select_db($con,$DB_NAME);
			mysqli_query($con,"SET NAMES tis620");
				
			if($staff_id=="all"){
				$STAFF_ID_DB="%";
				$FNAME_EN_DB="All Employee";
				$FNAME_TH_DB="พนักงานทุกคน";
				$EMAIL_DB="all@bppsupply.com";		
				
				
			}else{
				$GET_EMPLOYEEDETAIL_SQL="SELECT * from EMPLOYEE_INFO where STAFF_ID like '$staff_id'";
				$GET_SQL_RESULT = mysqli_query($con,$GET_EMPLOYEEDETAIL_SQL);
				$GET_SQL_RECORD=mysqli_fetch_array($GET_SQL_RESULT);
				$STAFF_ID_DB=$GET_SQL_RECORD['STAFF_ID'];
				$FNAME_EN_DB=$GET_SQL_RECORD['FNAME_EN'];
				$FNAME_TH_DB=$GET_SQL_RECORD['FNAME_EN'];
				$EMAIL_DB=$GET_SQL_RECORD['EMAIL'];		
			}
			//echo $FNAME_EN_DB;
?>		

			<section id='contactus' class="contact-area pt-100 pb-100 relative">
				<div class="overlay overlay-bg"></div>
				<div class="container">
					<div class="row justify-content-center text-center">
						<div class="single-contact col-lg-10 col-md-8">
							<h2 class='text-white'><?php echo $FNAME_EN_DB;?> Leave Report <span>is generated.</span></h2>
							<p class='text-white'>For go back to change leave report detail. Please <a href='leave.php?action=report'>Click here</a>.</p>
						</div>
					</div>
					<div class="row justify-content-center text-center">
						<div class="single-contact col-lg-10 col-md-8">
							<center><table border=0 width='90%'>
								<tr bgcolor='blue'>
									<td>Employee</td>
									<td>Leave Date</td>
									<td>Leave Time</td>
									<td>Leave Type</td>
									<td>Task</td>
								</tr>
								<?php
									if($month=="all"){
										$SQL_GET_REPORT="SELECT * FROM LEAVE_INFO LI LEFT JOIN LEAVE_TYPE LT on LT.LEAVE_TYPE like LI.LEAVE_TYPE LEFT JOIN EMPLOYEE_INFO EI on EI.STAFF_ID like LI.STAFF_ID where LI.STAFF_ID like '$STAFF_ID_DB'";										
									}else{
										$SQL_GET_REPORT="SELECT * FROM LEAVE_INFO LI LEFT JOIN LEAVE_TYPE LT on LT.LEAVE_TYPE like LI.LEAVE_TYPE LEFT JOIN EMPLOYEE_INFO EI on EI.STAFF_ID like LI.STAFF_ID where LI.STAFF_ID like '$STAFF_ID_DB' and MONTH(LI.LEAVE_DATE) like '".intval($month)."'";																							
									}
									//echo $SQL_GET_REPORT;
									
									$GET_LEAVE_RESULT = mysqli_query($con,$SQL_GET_REPORT);
														
									while($LEAVE_RECORD=mysqli_fetch_array($GET_LEAVE_RESULT)){
										$LEAVE_DATE=$LEAVE_RECORD['LEAVE_DATE'];
										$FNAME_EN_DB=$LEAVE_RECORD['FNAME_EN'];
										$FNAME_TH_DB=$LEAVE_RECORD['FNAME_TH'];
										$TYPE_NAME_EN=$LEAVE_RECORD['TYPE_NAME_EN'];
										$TYPE_NAME_TH=$LEAVE_RECORD['TYPE_NAME_TH'];
										$LEAVE_TIME=$LEAVE_RECORD['LEAVE_TIME'];
										
										if($LEAVE_TIME=="H"){
											if($lang=="en") $LEAVE_TIME_TEXT="Half Day"; else $LEAVE_TIME_TEXT="ลาครึ่งวัน";
											
										}else{
											if($lang=="en") $LEAVE_TIME_TEXT="Full Day"; else $LEAVE_TIME_TEXT="ลาเต็มวัน";
																					
										}
										
										echo "<tr>";
										if($lang=="th"){
											echo "<td>$FNAME_TH_DB</td>";
											echo "<td>".dateFormat($LEAVE_DATE,$lang)."</td>";
											echo "<td>$LEAVE_TIME_TEXT</td>";
											echo "<td>$TYPE_NAME_TH</td>";
										}else{
											echo "<td>$FNAME_EN_DB</td>";
											echo "<td>".dateFormat($LEAVE_DATE,$lang)."</td>";
											echo "<td>$LEAVE_TIME_TEXT</td>";
											echo "<td>$TYPE_NAME_EN</td>";
										}
										echo "<td>&nbsp;</td>";
										echo "</tr>";
									}									
									
								?>
							</table>
							</center>
						</div>

					</div>
				</div>
			</section>
<?php	
/*####################################################################################################################
###################################################### REPORT SECTION ################################################
######################################################################################################################*/	
	}elseif($action=="report"){
?>		
			<section id='contactus' class="contact-area pt-100 pb-100 relative">
				<div class="overlay overlay-bg"></div>
				<div class="container">
					<div class="row justify-content-center text-center">
						<div class="single-contact col-lg-10 col-md-8">
							<h2 class='text-white'>Please select information for <span>Your Leave Report</span></h2>
							<p class='text-white'>For go back to create leave record. Please <a href='leave.php'>Click here</a>.</p>
						</div>
					</div>
					<form action="leave.php?action=genreport" method="post">
						<div class="row justify-content-center">
							<div class="col-lg-10"><BR></div>
							<div class="col-lg-5">
								<font color='#000000'>Employee Name</font><br>
								<select name="staff_id" class="common-leave mt-20">
									<?php

									$con = mysqli_connect($DB_HOSTNAME,$DB_USERNAME,$DB_PASSWORD);
									if(!$con){echo "Not connect";}
									mysqli_select_db($con,$DB_NAME);
									mysqli_query($con,"SET NAMES tis620");	
									$GET_EMPLOYEE_SQL="select * from EMPLOYEE_INFO";
									$GET_EMPLOYEE_RESULT = mysqli_query($con,$GET_EMPLOYEE_SQL);
									
									echo "<option value='all' selected>All Employee</option>";
											
									while($EMPLOYEE_RECORD=mysqli_fetch_array($GET_EMPLOYEE_RESULT)){
										$FNAME_TH_DB=$EMPLOYEE_RECORD['FNAME_TH'];
										$FNAME_EN_DB=$EMPLOYEE_RECORD['FNAME_EN'];
										$STAFF_ID_DB=$EMPLOYEE_RECORD['STAFF_ID'];
										if($lang=="th"){
											echo "<option value='$STAFF_ID_DB'>$FNAME_TH_DB</option>";
										}else{
											echo "<option value='$STAFF_ID_DB'>$FNAME_EN_DB</option>";
										}
									}
									
									?>
								</select>
								
								
								
							</div>
							<div class="col-lg-5">
								<font color='#000000'>Report Month</font><br>
								<select name="month" class="common-leave mt-20">
									<?php
										echo "<option value='all' selected>All Month</option>";
										for ($x = 1; $x <= 12; $x++) {
											if($x<10) $monthno='0'.$x; else $monthno=$x;
											echo "<option value='".$monthno."'>".monthToText($monthno,$lang)."</option><br>";
										} 
									?>
								</select>
							</div>
							<div class="col-lg-10"><BR></div>
							<div class="col-lg-10">
								<button type='submit' value='Submit'>CREATE REPORT</button>
							</div>
						</div>
						
					</form>
				</div>
			</section>
<?php		
		
	}else{
/*####################################################################################################################
###################################################### LEAVE FORM SECTION ############################################
######################################################################################################################*/	
?>



			<section id='contactus' class="contact-area pt-100 pb-100 relative">
				<div class="overlay overlay-bg"></div>
				<div class="container">
					<div class="row justify-content-center text-center">
						<div class="single-contact col-lg-10 col-md-8">
							<?php echo $leave_header?>
							<?php echo $leave_header_detail?>
							<a href='?action=report'>CLICK HERE FOR LEAVE REPORT.</a>
							<BR><a href='?action=addemp'>CLICK HERE FOR ADD NEW EMPLOYEE.</a>
						</div>
					</div>
					<form action="leave.php?action=create" method="post">
						<div class="row justify-content-center">
							<div class="col-lg-10"><BR></div>
							<div class="col-lg-5">
								<font color='#000000'>Enter Your Name</font><br>
								<!--
								<input name="fname" class="common-leave mt-20" required="" type="text">
								-->
								<select name="staff_id" class="common-leave mt-20">
									<?php

									$con = mysqli_connect($DB_HOSTNAME,$DB_USERNAME,$DB_PASSWORD);
									if(!$con){echo "Not connect";}
									mysqli_select_db($con,$DB_NAME);
									mysqli_query($con,"SET NAMES tis620");	
									$GET_EMPLOYEE_SQL="select * from EMPLOYEE_INFO";
									$GET_EMPLOYEE_RESULT = mysqli_query($con,$GET_EMPLOYEE_SQL);
									
									while($EMPLOYEE_RECORD=mysqli_fetch_array($GET_EMPLOYEE_RESULT)){
										$FNAME_TH_DB=$EMPLOYEE_RECORD['FNAME_TH'];
										$FNAME_EN_DB=$EMPLOYEE_RECORD['FNAME_EN'];
										$STAFF_ID_DB=$EMPLOYEE_RECORD['STAFF_ID'];
										if($lang=="th"){
											echo "<option value='$STAFF_ID_DB'>$FNAME_TH_DB</option>";
										}else{
											echo "<option value='$STAFF_ID_DB'>$FNAME_EN_DB</option>";
										}
									}
									
									?>
								</select>
								
								
								
							</div>
							
							<div class="col-lg-3"></div>
							<div class="col-lg-2"></div>
							<!--
							<div class="col-lg-3">
								<font color='#000000'>*** TEST ****</font><br>
								<input class="common-leave mt-20" name="fname" type="text" id="fname" size="20" />
							</div>

							<div class="col-lg-2">
								<font color='#000000'>*** TEST ****</font><br>
								<input class="common-leave mt-20" name="staff_id" type="text" id="staff_id" value="" />
								
								<script type="text/javascript">
									function make_autocom(autoObj,showObj){
										var mkAutoObj=autoObj; 
										var mkSerValObj=showObj; 
										new Autocomplete(mkAutoObj, function() {
											this.setValue = function(id) {      
												document.getElementById(mkSerValObj).value = id;
											}
											if ( this.isModified )
												this.setValue("");
											if ( this.value.length < 1 && this.isNotClick ) 
												return ;    
											//return "lib/getemployee.php?q=" +encodeURIComponent(this.value)+"&lang=<?php echo $lang;?>";
											return "lib/getemployee.php?q=" +escape(this.value)+"&lang=<?php echo $lang;?>";
											
										}); 
									}   
									   
									// การใช้งาน
									// make_autocom(" id ของ input ตัวที่ต้องการกำหนด "," id ของ input ตัวที่ต้องการรับค่า");
									make_autocom("fname","staff_id");
									</script>
							</div>
							-->
							
							
							<!--
							<div class="col-lg-4">
								<font color='#000000'>Enter email address</font><br>
								<input name="email" pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,63}$" class="common-leave mt-20" required="" type="email">
							</div>
							-->
							<div class="col-lg-10"><BR></div>
							<div class="col-lg-10"><BR></div>
							<div class="col-lg-3">
								<font color='#000000'>Enter Leave Date (MM/DD/YYYY)</font><br><input name="sdate" class="common-leave mt-20" required="" type="date">
							</div>
							<div class="col-lg-2">
								<font color='#000000'>Full Date/Half Date?</font><br>
								<select name="stime" class="common-leave mt-20">
									<?php
										if($lang=="th"){
											echo "<option value='F' selected>ลาเต็มวัน</option>";
											echo "<option value='H'>ลาครึ่งวัน</option>";
										}else{
											echo "<option value='F' selected>Full Day</option>";
											echo "<option value='H'>Half Day</option>";
										}
									?>
								</select>
							</div>
							<div class="col-lg-3">
								<font color='#000000'>Type of Leave</font><br>
								<select name="ltype" class="common-leave mt-20">
									<?php
									
										$GET_LEAVETYPE_SQL="select * from LEAVE_TYPE";
										$GET_LEAVETYPE_RESULT = mysqli_query($con,$GET_LEAVETYPE_SQL);
										
										while($LEAVETYPE_RECORD=mysqli_fetch_array($GET_LEAVETYPE_RESULT)){
											$TYPE_NAME_TH=$LEAVETYPE_RECORD['TYPE_NAME_TH'];
											$TYPE_NAME_EN=$LEAVETYPE_RECORD['TYPE_NAME_EN'];
											$LEAVE_TYPE=$LEAVETYPE_RECORD['LEAVE_TYPE'];
											if($lang=="th"){
												echo "<option value='$LEAVE_TYPE'>$TYPE_NAME_TH</option>";
											}else{
												echo "<option value='$LEAVE_TYPE'>$TYPE_NAME_EN</option>";
											}
										}
										
									?>
								</select>
							</div>
							<div class="col-lg-2">&nbsp;</div>							
							
							<div class="col-lg-10"><BR></div>
							<div class="col-lg-10"><BR></div>
							<div class="col-lg-10"><font color='#000000'>Note</font></div>
							<div class="col-lg-10">
								<textarea class="common-leave mt-20" name="note"></textarea>
							</div>
							<div class="col-lg-10"><BR></div>
							<div class="col-lg-10"><BR></div>
							<div class="col-lg-10">
								<button type='submit' value='Submit'>Submit Leave</button>
							</div>
						</div>
						
					</form>
				</div>
			</section>
<?php
	}
	include("footer.php");

?>
