<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class HandlerController extends Controller
{
    public function handle()
    {
        // $tables = ['incomes', 'orders', 'sales', 'stocks'];
        // Вместо одного большого дампа получим четыре поменьше с соответствующими таблицами
        if (! isset($_GET['table'])) {
            $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
            echo '<a href="' . $url . '/handle?table=incomes" target="_blank">Создать дамп таблицы "incomes"</a><br><br>';
            echo '<a href="' . $url . '/handle?table=orders" target="_blank">Создать дамп таблицы "orders"</a><br><br>';
            echo '<a href="' . $url . '/handle?table=sales" target="_blank">Создать дамп таблицы "sales"</a><br><br>';
            echo '<a href="' . $url . '/handle?table=stocks" target="_blank">Создать дамп таблицы "stocks"</a><br><br>';
            return;
		} else {
            $table = $_GET['table'];
            $tables = ["$table"];
        }

        // $dumpPath = 'forge.sql';
        $dumpPath = "$table.sql";

        $this->createDump($dumpPath);

        foreach ($tables as $tableName) {
            $this->createTable($dumpPath, $tableName);

            $dateFrom = '1900-01-01';
            $dateTo = '2100-01-01';
            if ($tableName === 'stocks') {
                $dateFrom = date ('Y-m-d');
            }

            $count = 0;
            // Реально из цикла выйдем раньше, сразу как закончатся данные в теле приходящих json
            for ($i = 1; $i <= 1000; $i++) {
                $client = new Client();
                $idStart = $count;
                $arrDate = [];
                $arrJson = [];
                $json = $client->request('GET',
                    // 'https://wb-api-master.polyphyletic-prints.ru/api/'
                    'http://89.108.115.241:6969/api/' . $tableName .
                    '?dateFrom=' . $dateFrom .
                    '&dateTo=' . $dateTo .
                    '&page=' . $i .
                    '&key=E6kUTYrYwZq2tN4QEtyzsbEBk3ie&limit=500');

                $jsonBody = json_decode($json->getBody(), true);
                // Небольшой контроль корректного выполнения циклов
                $time = time();
                if (isset($jsonBody['data']) && $jsonBody['data']) {
                    file_put_contents('log.txt', "1 $time $i\n", FILE_APPEND);
                } else {
                    file_put_contents('log.txt', "0 $time $i\n", FILE_APPEND);
                    break;
                }

                foreach ($jsonBody['data'] as $column) {
                    if ($tableName !== 'stocks') {
                        $arrDate[] = $column['date'];
                        unset($column['date']);
                    }
                    $jsonEncode = json_encode($column, JSON_UNESCAPED_UNICODE);
                    $arrJson[] = $jsonEncode;
                    $count++;
                }

                $this->createInsert($dumpPath, $tableName, $idStart, $arrJson, $arrDate);
                sleep(1);
            }

            $this->createIncrement($dumpPath, $tableName, $count);

            echo 'Из таблицы "' . $tableName . '" было выведено строк: ' . $count . '<br>';
        }

        file_put_contents($dumpPath, "COMMIT;\n", FILE_APPEND);
        echo 'Дамп базы данных сформирован в папке /public в файл ' . $dumpPath;
    }

	function createDump($dumpPath) {
$header = 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- База данных: `forge`
--


DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_general_ci,
  `payload` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
';
        file_put_contents($dumpPath, $header . "\n");
	}

	function createTable($dumpPath, $tableName) {
        $columnDate = '';
        if ($tableName !== 'stocks') {
            $columnDate = "\n  `date` date NOT NULL,";
        }

$table = "
DROP TABLE IF EXISTS `$tableName`;
CREATE TABLE IF NOT EXISTS `$tableName` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,"
. $columnDate . "
  `json` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

";
        file_put_contents($dumpPath, $table, FILE_APPEND);
    }

	function createInsert($dumpPath, $tableName, $idStart, $arrJson, $arrDate) {
        if ($tableName === 'stocks') {
            $sql = "INSERT INTO `$tableName` (`id`, `json`, `created_at`) VALUES \n";

            $id = 0;
            foreach ($arrJson as $key => $json) {
                $id++;
                $idCurrent = $id + $idStart;

                if (isset((json_decode($json))->date)) {
                    $jsonDate = (json_decode($json))->date;
                    $string = "($idCurrent, '$json', '$jsonDate')";
                } else {
                    $string = "($idCurrent, '$json')";
                }

                if ($key === array_key_last($arrJson)) {
                    $sql .= "$string;\n\n";
                } else {
                    $sql .= "$string,\n";
                }
            }
        } else {
            $sql = "INSERT INTO `$tableName` (`id`, `date`, `json`) VALUES \n";

            $id = 0;
            foreach ($arrDate as $key => $date) {
                $json = $arrJson[$id];
                $id++;
                $idCurrent = $id + $idStart;

                $string = "($idCurrent, '$date', '$json')";

                if ($key === array_key_last($arrDate)) {
                    $sql .= "$string;\n\n";
                } else {
                    $sql .= "$string,\n";
                }
            }
        }

        file_put_contents($dumpPath, $sql, FILE_APPEND);
    }

    function createIncrement($dumpPath, $tableName, $count) {
	    $increment = '';
        if ($count > 0) {
            $increment = ', AUTO_INCREMENT=' . ($count + 1);
        }

$sequence = "ALTER TABLE `$tableName`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT$increment;

";
        file_put_contents($dumpPath, $sequence, FILE_APPEND);
    }
}
