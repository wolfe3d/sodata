<?php
require_once ("php/functions.php");
userCheckPrivilege(1);

$zipFile = 'decks/RocksMinerals.apkg';
$zipFile2 = 'RocksMinerals.apkg';
$dbNameInZip = 'collection.anki21';

$zip = new ZipArchive();
if ($zip->open($zipFile) === TRUE) {
    // 1. Extract to a temporary file
    $tempDbPath = sys_get_temp_dir() . '/' . uniqid('sqlite_', true);
    copy("zip://$zipFile#$dbNameInZip", $tempDbPath);
	echo($zipFile);
    $zip->close();

    try {
        // 2. Connect and Query
        $pdo = new PDO("sqlite:$tempDbPath");
        $stmt = $pdo->query("SELECT * FROM notes LIMIT 5");
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            print_r($row);
        }
    } finally {
        // 3. Clean up the temp file
        if (file_exists($tempDbPath)) unlink($tempDbPath);
    }
} else {
    echo "Failed to open zip file.";
}

$extraction_path = 'path/to/extract/to/'; // Define the destination folder

// Ensure the destination directory exists and has appropriate permissions
if (!is_dir($extraction_path)) {
    mkdir($extraction_path, 0755, true);
}

if ($zip->open($zipFile) === TRUE) {
    $zip->extractTo($extraction_path);
    $zip->close();
    echo 'Unzipped process successful!';
} else {
    echo 'Unzipped process failed, code: ' . $zip->status; // Provides an error code
}
?>
<div>
	Heads up
</div>
<script defer>
	$(document).ready(function() {
		startHeadsUp();
		console.log("Finished loading!");
	});
	async function startHeadsUp() {
  // Check for iOS 13+ permission requirement
  if (typeof DeviceOrientationEvent !== 'undefined' && 
      typeof DeviceOrientationEvent.requestPermission === 'function') {
    try {
      const permission = await DeviceOrientationEvent.requestPermission();
      if (permission === 'granted') {
        initTiltTracking();
      }
    } catch (error) {
      console.error("Permission denied by user");
    }
  } else {
    // Non-iOS or older devices
    initTiltTracking();
  }
  function initTiltTracking() {
  window.addEventListener('deviceorientation', (event) => {
    const tilt = event.beta;
    if (tilt > 75) {
      console.log("Correct!"); // Trigger win logic
    } else if (tilt < -75) {
      console.log("Pass!");    // Trigger pass logic
    }
  });
}
}
	</script>