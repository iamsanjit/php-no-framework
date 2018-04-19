<?php declare(strict_types=1);

use Doctrine\DBAL\Connection;
use Migrations\Migration201804151206;
use App\Framework\Dbal\DatabaseUrl;

define('ROOT_DIR', dirname(__DIR__));

require(ROOT_DIR . '/vendor/autoload.php');

$injector = include(ROOT_DIR . '/src/dependencies.php');

$connection = $injector->make(Connection::class);


$migrations = getAvailableMigrations();
$selected = selectMigration($migrations);

foreach ($migrations as $key => $migration) {
    if ($selected !== 0 && $selected !== $key + 1) {
        continue;
    }   
    $class = "Migrations\\{$migration}";
    (new $class($connection))->migrate();
    echo "Running {$migration}.." . PHP_EOL;
}
echo "Migration completed.";

echo $selected;
exit();


function selectMigration(array $migrations) : int
{
    echo '[0] All' . PHP_EOL;
    foreach ($migrations as $key => $name) {
        $index = $key + 1;
        echo "[{$index}] {$name}" . PHP_EOL;
    }
    $selected = readline('Select the migration that you want to run: ');
    $selectedKey = $selected - 1;

    if ($selected !== '0' && !array_key_exists($selectedKey, $migrations)) {
        exit('Invalid selection' . PHP_EOL);
    }
    return (int) $selected;
}

/** @return string[] */
function getAvailableMigrations() : array
{
    $migrations = [];
    foreach (new FilesystemIterator(ROOT_DIR . '/migrations') as $file) {
        $migrations[] = $file->getBaseName('.php');
    }
    return $migrations;
}

// $migration = new Migration201804151206($connection);
// $migration->migrate();

// echo 'Finished running migrations' . PHP_EOL;
