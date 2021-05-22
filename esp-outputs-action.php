<?php
    include_once('esp-database.php');

    $action =$myArr =$id = $Run_Time = $Volt = $m_start= $GEN_status=$config=$Start_but=$timer_but=$Timer_ON=$Timer_OFF = "";
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $action = test_input($_GET["action"]);
        if ($action == "outputs_state") {
			$last_reading = getReadings();
			$last_reading_start  = $last_reading["m_start"];
			$last_reading_config= $last_reading["config"];
			$last_reading_timer_but = $last_reading["timer_but"];
			$last_reading_Start_but = $last_reading["Start_but"];
			$last_reading_runtime = $last_reading["Run_Time"];
			$last_reading_Timer_ON = $last_reading["Timer_ON"];
	        $last_reading_Timer_OFF = $last_reading["Timer_OFF"];
			$myArr=array($last_reading_config,$last_reading_Start_but,$last_reading_timer_but,$last_reading_start,$last_reading_Timer_ON,$last_reading_Timer_OFF,$last_reading_runtime);
            echo json_encode($myArr);
        }
        else if ($action == "output_update") {
            $id = test_input($_GET["id"]);
            $Run_Time = test_input($_GET["Run_Time"]);
            $result = updateOutput($id, $Run_Time);
            echo $result;
        }
		else if ($action == "output_update_m") {
            $id = test_input($_GET["id"]);
            $m_start = test_input($_GET["m_start"]);
            $result = m_updateOutput($id, $m_start);
            echo $result;
        }
		else if ($action == "output_update_timer_ON") {
            $id = test_input($_GET["id"]);
            $Timer_ON = test_input($_GET["Timer_ON"]);
            $result = Timer_ON_updateOutput($id, $Timer_ON);
            echo $result;
        }
		else if ($action == "output_update_timer_OFF") {
            $id = test_input($_GET["id"]);
            $Timer_OFF = test_input($_GET["Timer_OFF"]);
            $result = Timer_OFF_updateOutput($id, $Timer_OFF);
            echo $result;
        }
        else if ($action == "output_update_c") {
            $id = test_input($_GET["id"]);
            $config = test_input($_GET["config"]);
            $result = c_updateOutput($id, $config);
            echo $result;
        }
		else if ($action == "output_update_start") {
            $id = test_input($_GET["id"]);
            $Start_but = test_input($_GET["Start_but"]);
            $result = s_updateOutput($id, $Start_but);
            echo $result;
        }
		else if ($action == "output_update_battery") {
            $id = test_input($_GET["id"]);
            $Volt = test_input($_GET["Volt"]);
            $result = volt_updateOutput($id, $Volt);
            echo $result;
        }
		else if ($action == "output_update_Runtime") {
            $id = test_input($_GET["id"]);
            $Run_Time = test_input($_GET["Run_Time"]);
            $result = Run_Time_updateOutput($id, $Run_Time);
            echo $result;
        }
		else if ($action == "output_update_Genstatus") {
            $id = test_input($_GET["id"]);
            $GEN_status = test_input($_GET["GEN_status"]);
            $result = GEN_status_updateOutput($id, $GEN_status);
            echo $result;
        }
		else if ($action == "output_update_timer") {
            $id = test_input($_GET["id"]);
            $timer_but = test_input($_GET["timer_but"]);
            $result = timer_updateOutput($id, $timer_but);
            echo $result;
        }
        else {
            echo "Invalid HTTP request.";
        }
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>
