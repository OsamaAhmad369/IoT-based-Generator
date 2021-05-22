<?php
    include_once('esp-database.php');
	$last_reading = getReadings();
    $last_reading_runtime = $last_reading["Run_Time"];
    $last_reading_Volt = $last_reading["Volt"];
    $last_reading_Genstatus = $last_reading["GEN_status"];
	$last_reading_start = $last_reading["m_start"];
	$last_reading_config= $last_reading["config"];
    $last_reading_Start_but = $last_reading["Start_but"];
	$last_reading_timer_but = $last_reading["timer_but"];
	$last_reading_Timer_ON = $last_reading["Timer_ON"];
	$last_reading_Timer_OFF = $last_reading["Timer_OFF"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Generator Monitoring System</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<style type="text/css">
body {
    width: 60%;
    margin: auto;
    text-align: center;
    font-family: Arial;
    top: 50%;
    left: 50%;
}

@media screen and (max-width: 800px) {
    body {
        width: 100%;
    }
}

table {
    margin-left: auto;
    margin-right: auto;
}

div {
    margin-left: auto;
    margin-right: auto;
}

h2 { font-size: 2.5rem; }

.header {
	 padding: 1rem;
	 margin: 0 0 2rem 0;
	 background: #f2f2f2;
}

h1 {
    font-size: 2rem;
    font-family: Arial, sans-serif;
    text-align: center;
    text-transform: uppercase;
}

.content {
    display: flex;
}

@media screen and (max-width: 500px) /* Mobile */ {
    .content {
        flex-direction: column;
    }
}

.mask {
    position: relative;
    overflow: hidden;
    display: block;
    width: 12.5rem;
    height: 6.25rem;
    margin: 1.25rem;
}

.semi-circle {
    position: relative;
    display: block;
    width: 12.5rem;
    height: 6.25rem;
    background: linear-gradient(to right, #3498db 0%, #05b027 33%, #f1c40f 70%, #c0392b 100%);
    border-radius: 50% 50% 50% 50% / 100% 100% 0% 0%;
}

.semi-circle::before {
    content: "";
    position: absolute;
    bottom: 0;
    left: 50%;
    z-index: 2;
    display: block;
    width: 8.75rem;
    height: 4.375rem;
    margin-left: -4.375rem;
    background: #fff;
    border-radius: 50% 50% 50% 50% / 100% 100% 0% 0%;
}

.semi-circle--mask {
    position: absolute;
    top: 0;
    left: 0;
    width: 12.5rem;
    height: 12.5rem;
    background: transparent;
    transform: rotate(120deg) translate3d(0, 0, 0);
    transform-origin: center center;
    backface-visibility: hidden;
    transition: all 0.3s ease-in-out;
}

.semi-circle--mask::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0%;
    z-index: 2;
    display: block;
    width: 12.625rem;
    height: 6.375rem;
    margin: -1px 0 0 -1px;
    background: #f2f2f2;
    border-radius: 50% 50% 50% 50% / 100% 100% 0% 0%;
}

.gauge--2 .semi-circle { background: #3498db; }

.gauge--2 .semi-circle--mask { transform: rotate(20deg) translate3d(0, 0, 0); }

#tableReadings { border-collapse: collapse; }

#tableReadings td, #tableReadings th {
    border: 1px solid #ddd;
    padding: 10px;
}

#tableReadings tr:nth-child(even){ background-color: #f2f2f2; }

#tableReadings tr:hover { background-color: #ddd; }

#tableReadings th {
    padding: 10px;
    background-color: #2f4468;
    color: white;
}
  </style>
</head>
  <header class="header">
        <h1>Generator System</h1>
    </header>
    <body >
    	<div id="Page1">
    		<h1>Main Page</h1>
    <h3 style="margin:auto;width:50%;border: 3px solid green;padding:10px;" id="p1">Current Time:</h3>
    <br>
    <p style="margin:auto;width:50%;border: 3px solid black;padding:10px;"><strong>Generator Run Time</strong>
    	<br> <?php echo $last_reading_runtime; ?> Hours</p>
    	<button class="btn btn-primary btn-lg" onclick="buttonReset()" style="display: inline-block;background-color:#73735E; border:none; color:white;border-radius: 4px;text-decoration: none;font-size: 22px;margin: 2px; cursor: pointer;">RESET</button>

    	<p><button class="btn btn-primary btn-lg" onclick="buttonManual()" style="display: inline-block;background-color:#008CBA; border:none; color:white;border-radius: 4px;text-decoration: none;font-size: 30px;margin: 2px; cursor: pointer;">Manual</button><button class="btn btn-primary btn-lg" onclick="buttonAuto()" style="display: inline-block;background-color:#a233ff; border:none; color:white;border-radius: 4px; text-decoration: none;font-size: 30px;margin: 20px; cursor: pointer;">Auto</button></p>
    	   <section class="content">
      <div class="box gauge--1">
       <h3>Generator Battery Voltage</h3>
              <div class="mask">
        <div class="semi-circle"></div>
        <div class="semi-circle--mask"></div>
      </div>
       <p style="font-size: 30px;" id="LEVEL">--</p>
        <table cellspacing="5" cellpadding="5">
            <tr>
                <th colspan="3">Battery Voltage</th>
              </tr>
            </table>
        </div>
         </section>
		 <h3>Status</h3>
		        <p style="font-size: 30px;" id="Genstatus">--</p>
         <button class="btn btn-primary btn-lg" onclick="buttonStart()" style="display: inline-block;background-color:#ff3333; border:none; color:white;border-radius: 4px;padding: 16px 40px; text-decoration: none;font-size: 30px;margin: 20px; cursor: pointer;">START</button></p>
		 <button class="btn btn-primary btn-lg" onclick="buttonOFF()" style="display: inline-block;background-color:#2945a2; border:none; color:white;border-radius: 4px;padding: 16px 40px; text-decoration: none;font-size: 30px;margin: 20px; cursor: pointer;">OFF</button></p>
          <p><button class="btn btn-primary btn-lg" onclick="return show('Page2','Page1');" style="display: inline-block;background-color:#ffbb33; border:none; color:white;border-radius: 4px;padding: 16px 40px; text-decoration: none;font-size: 30px;margin: 20px; cursor: pointer;">Setting</button></p>
     </div>
     <div style="display:none;" id="Page2">
     	<h1>Settings</h1>
     	<h3 style="margin:auto;width:50%;border: 3px solid green;padding:10px;" id="p2">Current Time:</h3>
    <br>
    <p>
    <button class="btn btn-primary btn-lg" onclick="buttonSwitch()" style="display: inline-block;background-color:#008CBA; border:none; color:white;border-radius: 4px;text-decoration: none;font-size: 30px;margin: 2px; cursor: pointer;">Switch</button>
    <button class="btn btn-primary btn-lg" onclick="buttonTimer()" style="display: inline-block;background-color:#a233ff; border:none; color:white;border-radius: 4px; text-decoration: none;font-size: 30px;margin: 20px; cursor: pointer;">Timer</button></p>
    <h3> Timer Program 1</h3>
      <table cellspacing="3" cellpadding="3" id="tableReadings">
                <tr>
                    <th>ON</th>
                    <th>OFF</th>
                  </tr>
                 <tr>
                 <td><?php echo $last_reading_Timer_ON; ?></td>
                 <td><?php echo $last_reading_Timer_OFF; ?></td>
                </tr>
                  <tr>
                 <td> <button onclick="return show('Page3','Page2');">change</button></td>
                 <td> <button onclick="return show('Page4','Page2');">change</button></td>
                </tr>
                </table>
                <br>
         <h3>Only for generator 1</h3>
         <table cellspacing="3" cellpadding="3"  style="border-collapse: collapse;">
                <tr style="background-color: #ddd;">
                    <th style="padding: 10px;background-color: #33ff42;color: black;">Starter Time</th>
                    <th ><button onclick="buttonUP()" >UP</button></th>
                  </tr>
                 <tr style="background-color: #ddd;">
                 <td style="padding: 10px;background-color: #000000;color: white;"><p id="num">--</p></td>
                 <td> <button onclick="buttonDown()">Down</button></td>
                </tr>
          </table>

          <p><button class="btn btn-primary btn-lg" onclick="buttonsave()" style="display: inline-block;background-color:#29a294; border:none; color:white;border-radius: 4px;text-decoration: none;font-size: 30px;margin: 20px; cursor: pointer;">Save</button></p>

    <p><button class="btn btn-primary btn-lg" onclick="return show('Page1','Page2');" style="display: inline-block;background-color:#ffbb33; border:none; color:white;border-radius: 4px;padding: 16px 40px; text-decoration: none;font-size: 30px;margin: 20px; cursor: pointer;">Home Page</button></p>
     </div>
     <div style="display:none;" id="Page3">
     	<h1>Timer</h1>
     	<table cellspacing="3" cellpadding="3" id="tableReadings">
                <tr>
                    <th><button onclick="buttonON_UP1()">UP</button></th>
                    <th><button onclick="buttonON_UP2()">UP</button></th>
					<th><button onclick="buttonON_UP3()">UP</button></th>
                  </tr>
                 <tr>
                 <td id="t1_h"></td>
                 <td id="t1_m"></td>
				 <td id="t1d"></td>
                </tr>
                  <tr>
                 <td> <button onclick="buttonON_DOWN1()">Down</button></td>
                 <td> <button onclick="buttonON_DOWN2()">Down</button></td>
				 <td> <button onclick="buttonON_DOWN3()">Down</button></td>
                </tr>
                </table>
                <p><button class="btn btn-primary btn-lg" onclick="savedateON()" style="display: inline-block;background-color:#ffbb33; border:none; color:white;border-radius: 4px;padding: 16px 40px; text-decoration: none;font-size: 30px;margin: 20px; cursor: pointer;">Save</button></p>
                   
	</div>
	 <div style="display:none;" id="Page4">
     	<h1>Timer</h1>
     	<table cellspacing="3" cellpadding="3" id="tableReadings">
                <tr>
                    <th><button onclick="buttonON_UP1()">UP</button></th>
                    <th><button onclick="buttonON_UP2()">UP</button></th>
					<th><button onclick="buttonON_UP3()">UP</button></th>
                  </tr>
                 <tr>
                 <td id="t2_h"></td>
                 <td id="t2_m"></td>
				 <td id="t2d"></td>
                </tr>
                  <tr>
                 <td> <button onclick="buttonON_DOWN1()">Down</button></td>
                 <td> <button onclick="buttonON_DOWN2()">Down</button></td>
				 <td> <button onclick="buttonON_DOWN3()">Down</button></td>
                </tr>
                </table>
                <p><button class="btn btn-primary btn-lg" onclick="savedateOFF()" style="display: inline-block;background-color:#ffbb33; border:none; color:white;border-radius: 4px;padding: 16px 40px; text-decoration: none;font-size: 30px;margin: 20px; cursor: pointer;">Save</button></p>
     </div>
 <script>
var today = new Date();
var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
var st=<?php echo $last_reading_start; ?>;
var value1=<?php echo $last_reading_Volt; ?>;
var t1_hour=0;
var t1_minute=0;
var t1_d="AM";
setLevel(value1);
document.getElementById("t1_h").innerHTML=t1_hour;
document.getElementById("t1_m").innerHTML=t1_minute;
document.getElementById("t1d").innerHTML=t1_d; 
document.getElementById("t2_h").innerHTML=t1_hour;
document.getElementById("t2_m").innerHTML=t1_minute;
document.getElementById("t2d").innerHTML=t1_d; 

document.getElementById("p1").innerHTML="Currrent Time: "+time;
document.getElementById("p2").innerHTML="Currrent Time: "+time;
document.getElementById("num").innerHTML=st+' s'; 
setInterval(function ( ) {
	var CurVal=<?php echo $last_reading_Genstatus; ?>;
  if(CurVal==0)
	 {
		$("#Genstatus").text('STOP');  
	 }
	 else if(CurVal==1)
	 {
		$("#Genstatus").text('Starting');    
	 }
	 	 else if(CurVal==2)
	 {
		$("#Genstatus").text('Running');    
	 }
	 else if(CurVal==3)
	 {
		$("#Genstatus").text('Warning');    
	 }
}, 3000) ;
      function buttonON_UP1()
	  {
            if(t1_hour<12)
			{
				t1_hour=t1_hour+1;	
			}
			document.getElementById("t1_h").innerHTML=t1_hour;
			document.getElementById("t2_h").innerHTML=t1_hour;
        }
		  function buttonON_UP2()
	  {
            if(t1_minute<55)
			{
				t1_minute=t1_minute+5;	
			}
			document.getElementById("t1_m").innerHTML=t1_minute;
			document.getElementById("t2_m").innerHTML=t1_minute;
        }
		function buttonON_UP3()
	  {
            if((t1_d.localeCompare("AM"))==0)
			{
				t1_d="PM";	
			}
			else
			{
				t1_d="AM";
			}
			document.getElementById("t1d").innerHTML=t1_d;
			document.getElementById("t2d").innerHTML=t1_d;
        }
		function buttonON_DOWN3()
	  {
            if((t1_d.localeCompare("AM"))==0)
			{
				t1_d="PM";	
			}
			else
			{
				t1_d="AM";
			}
			document.getElementById("t1d").innerHTML=t1_d;
			document.getElementById("t2d").innerHTML=t1_d;
        }
		function buttonON_DOWN1()
	  {
            if(t1_hour>0)
			{
				t1_hour=t1_hour-1;	
			}
			document.getElementById("t1_h").innerHTML=t1_hour;
			document.getElementById("t2_h").innerHTML=t1_hour;
        }
		function buttonON_DOWN2()
	  {
            if(t1_minute>0)
			{
				t1_minute=t1_minute-5;	
			}
			document.getElementById("t1_m").innerHTML=t1_minute;
			document.getElementById("t2_m").innerHTML=t1_minute;
        }
	 
		function savedateON()
	  { 
	        if(t1_d == "PM" && t1_hour<12) t1_hour = t1_hour+12;
            if(t1_d == "AM" && t1_hour==12) t1_hour = t1_hour-12;
			var t_save=t1_hour*10000+t1_minute*100;
	        var xhr = new XMLHttpRequest();
            xhr.open("GET", "esp-outputs-action.php?action=output_update_timer_ON&id=1&Timer_ON="+t_save, true);
            xhr.send();
			return show('Page2','Page3');
        }
		function savedateOFF()
	  { 
	        if(t1_d == "PM" && t1_hour<12) t1_hour = t1_hour+12;
            if(t1_d == "AM" && t1_hour==12) t1_hour = t1_hour-12;
			var t_save=t1_hour*10000+t1_minute*100;
	        var xhr = new XMLHttpRequest();
            xhr.open("GET", "esp-outputs-action.php?action=output_update_timer_OFF&id=1&Timer_OFF="+t_save, true);
            xhr.send();
			return show('Page2','Page4');
        }
      function buttonReset() {
            var xhr = new XMLHttpRequest();
             xhr.open("GET", "esp-outputs-action.php?action=output_update&id=1&Run_Time=0", true);
            xhr.send();
        }
      function buttonsave() {
            var xhr = new XMLHttpRequest();
             xhr.open("GET", "esp-outputs-action.php?action=output_update_m&id=1&m_start="+st, true);
            xhr.send();
        }
       function buttonAuto() {
            var xhr = new XMLHttpRequest();
             xhr.open("GET", "esp-outputs-action.php?action=output_update_c&id=1&config=1", true);
            xhr.send();
        }
       function buttonManual() {
            var xhr = new XMLHttpRequest();
             xhr.open("GET", "esp-outputs-action.php?action=output_update_c&id=1&config=0", true);
            xhr.send();
        }
       function buttonStart() {
            var xhr = new XMLHttpRequest();
             xhr.open("GET", "esp-outputs-action.php?action=output_update_start&id=1&Start_but=1", true);
            xhr.send();
        } 
       function buttonOFF() {
            var xhr = new XMLHttpRequest();
             xhr.open("GET", "esp-outputs-action.php?action=output_update_start&id=1&Start_but=0", true);
            xhr.send();
        }		
        function buttonTimer() {
            var xhr = new XMLHttpRequest();
             xhr.open("GET", "esp-outputs-action.php?action=output_update_timer&id=1&timer_but=1", true);
            xhr.send();
        } 
        function buttonSwitch() {
            var xhr = new XMLHttpRequest();
             xhr.open("GET", "esp-outputs-action.php?action=output_update_timer&id=1&timer_but=0", true);
            xhr.send();
        } 

     function setLevel(curVal){
    	var minTemp = 0.0;
    	var maxTemp = 12.8;
    	var newVal = scaleValue(curVal, [minTemp, maxTemp], [0, 180]);
    	 $('.gauge--1 .semi-circle--mask').attr({
            style: '-webkit-transform: rotate(' + newVal + 'deg);' +
            '-moz-transform: rotate(' + newVal + 'deg);' +
            'transform: rotate(' + newVal + 'deg);'
        });
    	$("#LEVEL").text(curVal + ' V');
    }
  function scaleValue(value, from, to) {
        var scale = (to[1] - to[0]) / (from[1] - from[0]);
        var capped = Math.min(from[1], Math.max(from[0], value)) - from[0];
        return ~~(capped * scale + to[0]);
    }
    function show(shown, hidden) {
      document.getElementById(shown).style.display='block';
      document.getElementById(hidden).style.display='none';
      return false;
    }
    function buttonUP(){
    	if(st<30){
    	st=st+0.5;}
    	document.getElementById("num").innerHTML=st+' s'; 	
    }
    function buttonDown(){
     if(st>0.5){
     st=st-0.5;}
     document.getElementById("num").innerHTML=st+' s'; 
    }
</script>
</body>
</html>