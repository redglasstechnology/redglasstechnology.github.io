<?php
 
include('../lib/config.php');
/** Switch Case to Get Action from controller **/
switch($_GET['action']) {
	case 'get_item' :
		get_item($con);
		break;
	 
	case 'update_partner_image' :
		update_partner_image($con);
		break;
	 
	case 'delete_partner_image' :
		delete_partner_image($con);
		break;

	default:
		print_r("No match-able function found...");
}

function get_item($con) {
	$qry = mysqli_query($con, 'SELECT * from partner WHERE partner_name="'.$_GET['partner_name'].'"');
	$data = array();
	while($rows = mysqli_fetch_array($qry)) {
		$data[] = array(
		"partner_id" => $rows['partner_id'],
		"partner_name" => $rows['partner_name'],
		"partner_image_1" => $rows['partner_image_1'],
		"partner_image_2" => $rows['partner_image_2'],
		"partner_image_3" => $rows['partner_image_3'],
		"partner_image_4" => $rows['partner_image_4'],
		"partner_image_5" => $rows['partner_image_5']
		);
	}
	print_r(json_encode($data));
	return json_encode($data); 
}

function update_partner_image($con) {
	$data = json_decode(file_get_contents("php://input"));
	$partner_id = $data->partner_id;
	$currentChangingItemPosition = $data->currentChangingItemPosition;
	$newFileValue = $data->newFileValue;
	 
	$qry = "UPDATE partner set partner_image_".$currentChangingItemPosition."='".$newFileValue."' WHERE partner_id=".$partner_id;
	 
	$qry_res = mysqli_query($con, $qry);
	if ($qry_res) {
		$arr = array('msg' => "adv Updated Successfully!!!", 'error' => '', 'param' => $data);
		$jsn = json_encode($arr);
		print_r($jsn);
	} else {
		$arr = array('msg' => "", 'error' => 'Error In Updating record', 'param' => $data, 'query' => $qry);
		$jsn = json_encode($arr);
		print_r($jsn);
	}
	return json_encode($jsn); 
}

function delete_partner_image($con) {
	$data = json_decode(file_get_contents("php://input"));
	$partner_id = $data->partner_id;
	$targetDeletePosition = $data->targetDeletePosition;
	$currentFile = $data->currentFile;
	 
	$qry = "UPDATE partner set partner_image_".$targetDeletePosition."='' WHERE partner_id=".$partner_id;
	 
	$qry_res = mysqli_query($con, $qry);
	if ($qry_res) {
		$oldFilePath = dirname( __FILE__ ) . $currentFile;
		if (is_file($oldFilePath)) {
			unlink($oldFilePath);
			$oldFileDeleteStatus = "Delete success: " . $oldFilePath;
		} else {
			$oldFileDeleteStatus = "Failed to delete: " . $oldFilePath;
		}

		$arr = array('msg' => "adv delete Successfully!!!", 'error' => '', 'param' => $data, 'oldFileDeleteStatus' => $oldFileDeleteStatus);
		$jsn = json_encode($arr);
		print_r($jsn);
	} else {
		$arr = array('msg' => "", 'error' => 'Error In deleting record', 'param' => $data, 'query' => $qry);
		$jsn = json_encode($arr);
		print_r($jsn);
	}
	return json_encode($jsn); 
}
?>