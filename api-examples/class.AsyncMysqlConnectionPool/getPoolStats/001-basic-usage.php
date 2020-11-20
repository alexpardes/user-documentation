<?hh

namespace Hack\UserDocumentation\API\Examples\AsyncMysql\ConnPool\Stats;

use \Hack\UserDocumentation\API\Examples\AsyncMysql\ConnectionInfo as CI;


function set_connection_pool(
  darray<string, mixed> $options = darray[],
): \AsyncMysqlConnectionPool {
  return new \AsyncMysqlConnectionPool($options);
}

async function connect_with_pool(
  \AsyncMysqlConnectionPool $pool,
): Awaitable<\AsyncMysqlConnection> {
  return await $pool->connect(
    CI::$host,
    CI::$port,
    CI::$db,
    CI::$user,
    CI::$passwd,
  );
}

function get_stats(\AsyncMysqlConnectionPool $pool): mixed {
  return $pool->getPoolStats();
}

<<__EntryPoint>>
function run_it(): void {
  require __DIR__.'/../../__includes/async_mysql_connect.inc.php';
  $pool = set_connection_pool();
  $conn_awaitables = Vector {};
  $conn_awaitables[] = connect_with_pool($pool);
  $conn_awaitables[] = connect_with_pool($pool);
  $conn_awaitables[] = connect_with_pool($pool);
  $conns = \HH\Asio\join(\HH\Asio\v($conn_awaitables));
  // Get pool connection stats, like pool connections created, how many
  // connections were requested, etc.
  \var_dump(get_stats($pool));
}
