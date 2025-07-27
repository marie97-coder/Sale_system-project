<?php
session_start();
session_unset();
session_destroy();
header("Location: index.php"); // badilisha jina la homepage yako kama si hili
exit();
?>
