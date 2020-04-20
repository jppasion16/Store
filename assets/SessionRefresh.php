<?php

session_start();
$_SESSION['last_refreshed_time'] = time();
echo date('Y-m-d H:i a', $_SESSION['last_refreshed_time']).' - '.session_id();

?>