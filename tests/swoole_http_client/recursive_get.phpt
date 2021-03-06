--TEST--
swoole_http_client: recursive_get

--SKIPIF--
<?php require  __DIR__ . '/../include/skipif.inc'; ?>
--FILE--
<?php
require __DIR__ . '/../include/bootstrap.php';

$simple_http_server = __DIR__ . "/../include/api/swoole_http_server/simple_http_server.php";
$closeServer = start_server($simple_http_server, HTTP_SERVER_HOST, $port = get_one_free_port());

$cli = new \swoole_http_client("127.0.0.1", $port);
$cli->on("error", function() { /*echo "ERROR";*/ swoole_event_exit(); });
$cli->on("close", function() { /*echo "CLOSE";*/ swoole_event_exit(); });
$i = 0;
function get()
{
    global $cli, $i, $closeServer;
    ++$i;
    if ($i > 10)
    {
        echo "SUCCESS\n";
        $cli->close();
        $closeServer();
    }
    else
    {
        $cli->get("/", __FUNCTION__);
    }
}
get();
?>
--EXPECT--
SUCCESS
