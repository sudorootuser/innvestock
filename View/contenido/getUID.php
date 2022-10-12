<?php
$UIDresult = $_POST["UIDresult"];
$Write = "<?php $" . "UIDresult='" . $UIDresult . "'; ". " ?>";

file_put_contents('UIDContainer.php', $Write);
?>