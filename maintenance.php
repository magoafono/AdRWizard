<?php
header("HTTP/1.1 503 Service Temporarily Unavailable");
header("Status: 503 Service Temporarily Unavailable");
header("Retry-After: 3600");
?>
<html>
<head>
<title>Site upgrade in progress</title>
<meta name="robots" content="none" />
</head>
<body>
<h1>Maintenance Mode</h1>
<p><a title="Your site" href="login.php">The site</a> is currently undergoing scheduled maintenance.<br />
Please try later.</p>
<p>Sorry for the inconvenience.</p>
</body>
</html>