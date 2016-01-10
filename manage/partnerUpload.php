<?php

if ( !empty( $_FILES ) ) {
	$DIRECTORY_SEPARATOR = "/";
	
	$partner_name = $_GET['partner_name'];
	$current_file = $_GET['current_file'];
	$position = $_GET['position'];
	
	$tempPath = $_FILES[ 'file' ][ 'tmp_name' ];
	$partnerFolderPath = $DIRECTORY_SEPARATOR . 'db' . $DIRECTORY_SEPARATOR . $partner_name;
	$positionFolderPath = $partnerFolderPath . $DIRECTORY_SEPARATOR . $position;
	$filePath = $positionFolderPath . $DIRECTORY_SEPARATOR . $_FILES[ 'file' ][ 'name' ];
	
	$fullPartnerFolderPath = dirname( __FILE__ ) . $partnerFolderPath;
	if (!is_dir($fullPartnerFolderPath)) {
		mkdir($fullPartnerFolderPath, 0755, true);
	}
	$fullPositionFolderPath = $fullPartnerFolderPath . $DIRECTORY_SEPARATOR . $position;
	if (!is_dir($fullPositionFolderPath)) {
		mkdir($fullPositionFolderPath, 0755, true);
	}
	$fullFilePath = $fullPositionFolderPath . $DIRECTORY_SEPARATOR . $_FILES[ 'file' ][ 'name' ];
	
	$oldFilePath = dirname( __FILE__ ) . $current_file;
	if (is_file($oldFilePath)) {
		unlink($oldFilePath);
		$oldFileDeleteStatus = "Delete success: " . $oldFilePath;
	} else {
		$oldFileDeleteStatus = "Failed to delete: " . $oldFilePath;
	}

	move_uploaded_file( $tempPath, $fullFilePath );

	$answer = array( 'answer' => 'File transfer completed', 'fileName' => $_FILES[ 'file' ][ 'name' ], 'fileSavedPath' => $filePath, 'oldFileDeleteStatus' => $oldFileDeleteStatus);
	$json = json_encode( $answer );

	echo $json;

} else {
	echo 'No files';

}

?>