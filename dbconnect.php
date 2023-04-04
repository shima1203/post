<?php
try {
    $db = new PDO ('mysql:dbname=keijiban; host=localhost; charset=utf8', 'root', 'Aquos123');
} catch (PDOException $e) {
    echo 'DB接続エラー' . $e->getMessage;
}
?>