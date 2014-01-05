<?php
date_default_timezone_set('UTC');
require_once('../lib/DatabaseManager.php');

$sql = "SELECT * FROM curioususer ORDER BY created_at DESC";

$emails = DatabaseConnector::get_results($sql);
?>

<table>
    <tr>
        <th>email</th>
        <th>ip</th>
        <th>created_at</th>
    </tr>
    <?php foreach($emails as $e) { ?>
    <tr>
        <td><?php echo $e->email?></td>
        <td><?php echo $e->ip?></td>
        <td><?php echo $e->created_at?></td>
    </tr>
    <?php } ?>
</table>
