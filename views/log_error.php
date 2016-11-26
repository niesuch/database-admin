<?php
require_once(dirname(dirname(__FILE__)) . '/config/autoloader.php');

use Lib\Paginator\Paginator as Paginator;
use Lib\Logger\Logger as Logger;

$logger_error = new Logger(LOGS_LOG_ERROR);

$settings = array(
    'limit' => isset($_GET['limit']) ? $_GET['limit'] : 20,
    'page' => isset($_GET['page']) ? $_GET['page'] : 1,
    'links' => isset($_GET['links']) ? $_GET['links'] : 4,
    'sort_column' => isset($_GET['sort']) ? $_GET['sort'] : 'date',
    'sort_order' => isset($_GET['order']) ? $_GET['order'] : 'DESC'
);

$paginator = new Paginator(array(
    'base' => $logger_error->_log['base'],
    'table' => $logger_error->_log['table'],
    'data' => $logger_error->_log['fields']
        )
);

$results = $paginator->getData($settings);
?>

<!DOCTYPE html>
<head>
    <title>Database admin</title>
    <link rel="stylesheet" href="../css/style.css" type="text/css">    
</head>
<body>
    <form method="POST" name="form" action="">
        <div class="menu">
            <a href="index.php">Database admin</a>
            <a href="log_query.php" class="margin-left-20">Log query</a>
            <b><a href="log_error.php" class="margin-left-20">Log error</a></b>
            <a href="log_history_query.php" class="margin-left-20">Log history query</a>
        </div>
    </form>

    <h1>Log error</h1>

    <div class="container">
        <div>
            <table class="logs_table">
                <thead>
                    <tr>
                        <th>
                            <a href="?sort=id&order=ASC">&#x25B2;</a>
                            Id
                            <a href="?sort=id&order=DESC">&#x25BC;</a>
                        </th>
                        <th>
                            <a href="?sort=query&order=ASC">&#x25B2;</a>
                            Query
                            <a href="?sort=query&order=DESC">&#x25BC;</a>
                        </th>
                        <th>
                            <a href="?sort=error&order=ASC">&#x25B2;</a>
                            Error
                            <a href="?sort=error&order=DESC">&#x25BC;</a>
                        </th>
                        <th>
                            <a href="?sort=date&order=ASC">&#x25B2;</a>
                            Date
                            <a href="?sort=date&order=DESC">&#x25BC;</a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($results); $i++) { ?>
                        <tr>
                            <td><?php echo $results[$i]['id']; ?></td>
                            <td><?php echo $results[$i]['query']; ?></td>
                            <td><?php echo $results[$i]['error']; ?></td>
                            <td><?php echo $results[$i]['date']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <?php echo $paginator->pagination($settings['links'], 'pagination'); ?> 
        </div>
    </div>
</body>
</html>