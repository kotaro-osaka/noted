<?php
session_start();
session_destroy();

// Prevent browser from caching pages
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Location: /');
exit;