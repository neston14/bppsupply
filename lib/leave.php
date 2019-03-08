<?php

	include("header.php");
	isset($_REQUEST['action']) ? $action = $_REQUEST['action'] : $action = "";
	
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
?>
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
<?php

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
				$OUTPUT_TEXT2="<p class='text-white'>For More information how to create leave report. <a href=''>Click here</a></p>";
				
				$GET_LEAVE_RESULT = mysqli_query($con,$CHECK_EXISTING_SQL2);
				$GET_LEAVE_RECORD=mysqli_fetch_array($GET_LEAVE_RESULT);
				$OLD_LEAVE_TIME=$GET_LEAVE_RECORD['LEAVE_TIME'];
				$OLD_LEAVE_DATE=$GET_LEAVE_RECORD['LEAVE_DATE'];
				if($lang=="th"){
					if($OLD_LEAVE_TIME=="F") $OLD_LEAVE_TIME_TEXT="ลาเต็มวัน"; else  $OLD_LEAVE_TIME_TEXT="Full Day";
				}else{
					if($OLD_LEAVE_TIME=="F") $OLD_LEAVE_TIME_TEXT="ลาครึ่งวัน"; else  $OLD_LEAVE_TIME_TEXT="Half Day";
				}
				$OLD_LEAVE_DATE_TMP = new DateTime($OLD_LEAVE_DATE);
				$OLD_LEAVE_DATE_TEXT=date_format($OLD_LEAVE_DATE_TMP,"d F Y");
				
				
				
				$RECORD_DETAIL="<div class='col-lg-10'><font color='#000000'>Old Leave Date : $OLD_LEAVE_DATE_TEXT ($OLD_LEAVE_TIME_TEXT)</font></div>";
				$RECORD_DETAIL="<div class='col-lg-10'><font color='#000000'>New Leave Date : $dateText ($stimeText)</font></div>";
				
				
				
			}else{
		
		
				$INSERT_LEAVE_INFO="INSERT INTO `LEAVE_INFO` (`STAFF_ID`, `LEAVE_DATE`, `LEAVE_TYPE`, `LEAVE_TIME`) VALUES ('$staff_id', '$sdate', '$ltype', '$stime');";
				//mysqli_query($con,$INSERT_LEAVE_INFO);			
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
	}else{
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
			<!-- Start Video Area -->
			<section id='contactus' class="contact-area pt-100 pb-100 relative">
				<div class="overlay overlay-bg"></div>
				<div class="container">
					<div class="row justify-content-center text-center">
						<div class="single-contact col-lg-10 col-md-8">
							<?php echo $leave_header?>
							<?php echo $leave_header_detail?>
							<a href='leave_report.php>'>CLICK HERE FOR LEAVE REPORT.</a>
						</div>
					</div>
					<form action="leave.php?action=create" method="post">
						<div class="row justify-content-center">
							<div class="col-lg-10"><BR></div>
							<div class="col-lg-6">
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
							<div class="col-lg-4">
								<font color='#000000'><br><br><br><a href='?action=addemp'>[Add new Employee]</a></font>
							</div>
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
