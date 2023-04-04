<?php
session_start();
require('dbconnect.php');
 
if(isset($_SESSION['id'])) {
    $id = $_REQUEST['id'];
    $messages = $db->prepare('SELECT * FROM info WHERE message_id=?');
    $messages -> execute(array($id));
    $message = $messages->fetch();
    if (($message['created_by'] == $_SESSION['id']) || ($_SESSION['id'] == 1)) {
        $del = $db->prepare('DELETE FROM info WHERE message_id=?');
        $del->execute(array($id));
    }
}
header('Location: posting.php');
exit();
?>