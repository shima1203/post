<?php
session_start();
require('dbconnect.php');

if(isset($_SESSION['id'])) {
    $id = $_REQUEST['id'];
    $messages = $db->prepare('SELECT * FROM info WHERE message_id=?');
    $messages -> execute(array($id));
    $message = $messages->fetch();
    $mes = htmlspecialchars($_POST['remessage'], ENT_QUOTES);
    $params = array(':mes' => $mes, ':message_id' => $id);

    if ($message['created_by'] == $_SESSION['id'] || ($_SESSION['id'] == 1)) {
        $del = $db->prepare('UPDATE info SET message = :mes WHERE message_id=:message_id');
        $del->execute($params);
    }
}
header('Location: posting.php');
exit();
?>