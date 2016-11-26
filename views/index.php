<?php
require_once(dirname(dirname(__FILE__)) . '/config/autoloader.php');

use Lib\Database_Admin\Database_Admin as Database_Admin;
use Lib\Logger\Logger as Logger;

$databaseAdmin = new Database_Admin();

$databaseAdmin->set_log(new Logger(LOGS_LOG_QUERY));
$databaseAdmin->set_log(new Logger(LOGS_LOG_ERROR));
$databaseAdmin->set_log(new Logger(LOGS_LOG_HISTORY_QUERY));
$databaseAdmin->_turn_transaction = isset($_POST['commit']) ? true : false;

if (!empty($_POST['go'])) {
    if (!empty($_POST['db'])) {
        $databaseAdmin->_db_choosen = $_POST['db'];
        $databaseAdmin->_sql_text = trim($_POST['sql']);
        $databaseAdmin->db_update();
    } else {
        $msg = "<b><span class='negative'>Don't select any base!</span><b>";
        $databaseAdmin->_output_message = $msg;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Database admin</title>
        <link rel="stylesheet" href="../css/style.css" type="text/css">  
        <script src="../js/jquery.min.js"></script>
        <script src="../js/script.js"></script>
    </head>
    <body>
        <form method="POST" name="form" action="">
            <div class="menu">
                <b><a href="index.php">Database admin</a></b>
                <a href="log_query.php" class="margin-left-20">Log query</a>
                <a href="log_error.php" class="margin-left-20">Log error</a>
                <a href="log_history_query.php" class="margin-left-20">Log history query</a>
            </div>

            <h1>SQL</h1>

            <div class="error">
                <?php echo $databaseAdmin->_db->_error_message; ?>
            </div>

            <div class="form">
                <table>
                    <tr>
                        <th>Query</th>
                        <th>Bases</th>
                        <th>History query</th>
                    </tr>
                    <tr>
                        <td>
                            <textarea id="sql" name="sql" cols="70" rows="10"><?php echo $databaseAdmin->_sql_text; ?></textarea>
                        </td>
                        <td>
                            <select multiple="multiple" name="db[]" class="margin-left-20 select_base">
                                <?php echo $databaseAdmin->get_databases(); ?>
                            </select>
                        </td>
                        <td>
                            <select multiple="multiple" class="margin-left-20 select_history">
                                <?php echo $databaseAdmin->get_select_history(); ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <div>
                    <input type="submit" name="go" value="Go">
                    <input type="checkbox" name="commit" value="1"
                    <?php
                    if ($databaseAdmin->_turn_transaction)
                        echo "checked='checked'";
                    ?> /> <b> COMMIT </b>
                </div>
            </div>
        </form> 

        <div class="form">
            <b> <?php echo $databaseAdmin->_output_message; ?> </b>

            <?php if ($databaseAdmin->_updated || $databaseAdmin->_not_updated) { ?>
                <div class="info">
                    <b> Update complete on: </b> <br/>                
                    <?php
                    if ($databaseAdmin->_updated) {
                        foreach ($databaseAdmin->_updated as $db) {
                            echo "- <span class='positive'>" . $db . "</span> <br/>";
                        }
                    }
                    ?>
                </div>

                <div class="info">
                    <b> Not update on: </b> <br/>
                    <?php
                    if ($databaseAdmin->_not_updated) {
                        foreach ($databaseAdmin->_not_updated as $db) {
                            echo "- <span class='negative'>" . $db . "</span> <br/>";
                        }
                    }
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
    </body>
</html>