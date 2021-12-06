<?php
include_once './model/Database.php';

$database = new Database();

$query[] = "SELECT * FROM `users`";
$query[] = "WHERE 1";

echo $query = implode("",$query);

$list = $database->fetchAll($query);
if (!empty($list)) {
    foreach ($list as $item) {
        echo '<pre style="color:red;font-weight:bold">';
        print_r($list);
        echo '</pre>';
        echo $item['Id'];
    }
}

$database->setTable('users');
$data = array(
            'username' => 'A',
            'password' => 'a'
        );
echo $insert = $database->insert($data)

?>