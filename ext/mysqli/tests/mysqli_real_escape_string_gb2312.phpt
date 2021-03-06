--TEST--
mysqli_real_escape_string() - gb2312
--SKIPIF--
<?php
if (ini_get('unicode.semantics'))
	die("skip Test cannot be run in unicode mode");

require_once('skipif.inc');
require_once('skipifemb.inc');
require_once('skipifconnectfailure.inc');
require_once('connect.inc');

if (!$link = mysqli_connect($host, $user, $passwd, $db, $port, $socket)) {
	die(sprintf("skip Cannot connect to MySQL, [%d] %s\n",
		mysqli_connect_errno(), mysqli_connect_error()));
}
if (!mysqli_set_charset($link, 'gb2312'))
	die(sprintf("skip Cannot set charset 'gb2312'"));
mysqli_close($link);
?>
--FILE--
<?php

	require_once("connect.inc");
	require_once('table.inc');

	var_dump(mysqli_set_charset($link, "gb2312"));

	if ('首先\\\\首先' !== ($tmp = mysqli_real_escape_string($link, '首先\\首先')))
		printf("[004] Expecting \\\\, got %s\n", $tmp);

	if ('首先\"首先' !== ($tmp = mysqli_real_escape_string($link, '首先"首先')))
		printf("[005] Expecting \", got %s\n", $tmp);

	if ("首先\'首先" !== ($tmp = mysqli_real_escape_string($link, "首先'首先")))
		printf("[006] Expecting ', got %s\n", $tmp);

	if ("首先\\n首先" !== ($tmp = mysqli_real_escape_string($link, "首先\n首先")))
		printf("[007] Expecting \\n, got %s\n", $tmp);

	if ("首先\\r首先" !== ($tmp = mysqli_real_escape_string($link, "首先\r首先")))
		printf("[008] Expecting \\r, got %s\n", $tmp);

	if ("首先\\0首先" !== ($tmp = mysqli_real_escape_string($link, "首先" . chr(0) . "首先")))
		printf("[009] Expecting %s, got %s\n", "首先\\0首先", $tmp);

	var_dump(mysqli_query($link, "INSERT INTO test(id, label) VALUES (100, '首')"));

	mysqli_close($link);
	print "done!";
?>
--EXPECTF--
bool(true)
bool(true)
done!
