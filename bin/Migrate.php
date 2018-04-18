<?php declare(strict_types=1);

use Doctrine\DBAL\Connection;
use Migrations\Migration201804151206;
use App\Framework\Dbal\DatabaseUrl;

define('ROOT_DIR', dirname(__DIR__));

require(ROOT_DIR . '/vendor/autoload.php');

$injector = include(ROOT_DIR . '/src/dependencies.php');

$connection = $injector->make(Connection::class);

$migration = new Migration201804151206($connection);
$migration->migrate();

echo 'Finished running migrations' . PHP_EOL;
