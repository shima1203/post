<?php
session_start();
require('dbconnect.php');
 
//ログイン状態をチェック
if (isset($_SESSION['id']) && ($_SESSION['time'] + 3600 > time())) {
    $_SESSION['time'] = time();
 
    $members=$db->prepare('SELECT * FROM logindata WHERE id=?');
    $members->execute(array($_SESSION['id']));
    $member=$members->fetch();
    } else {
    header('Location: login.php');
    exit();
}
 
if (!empty($_POST)){
    if (isset($_POST['token']) && $_POST['token'] === $_SESSION['token']) {
        $message=$db->prepare('INSERT INTO info SET created_by=?, message=?, created=NOW()');
        $message->execute(array($member['id'] , $_POST['message']));
        header('Location: posting.php');
        exit();
    }else {
        header('Location: login.php');
        exit();
    }
}
 
$posts=$db->query('SELECT m.name, p.* FROM logindata m, info p WHERE m.id=p.created_by ORDER BY p.Created DESC');
 
 
$TOKEN_LENGTH = 16;
$tokenByte = openssl_random_pseudo_bytes($TOKEN_LENGTH);
$token = bin2hex($tokenByte);
$_SESSION['token'] = $token;
 
?>
 
<!DOCTYPE html>
<html lang="ja">
 
<body>
<!-- ログアウト -->
<header>
<div class="head">
<h1>しましま掲示板</h1>
<span class="logout"><a href="login.php">ログアウト</a></span>
 
</div>
</header>

 <!-- 投稿 -->
<form action='' method="post">
<input type="hidden" name="token" value="<?=$token?>">
<?php if (isset($error['login']) &&  ($error['login'] =='token')): ?>
    <p class="error">不正なアクセスです。</p>
<?php endif; ?>
<div class="edit">
<p><?php echo htmlspecialchars($member['name'], ENT_QUOTES); ?>さん、ようこそ</p>
<textarea name="message" cols='50' rows='10'><?php echo htmlspecialchars($message??"", ENT_QUOTES); ?></textarea>
</div>
 

<input type="submit" value="投稿する" class="button02">
</form>
<!-- 表示 -->
<?php foreach($posts as $post): ?>

<?php if (htmlspecialchars($post['message_id'], ENT_QUOTES) !== $_REQUEST['id']): ?>
    <div class="message">
    <?php echo htmlspecialchars($post['message'], ENT_QUOTES); ?>
    <span class="name">        　　<?php echo htmlspecialchars($post['name'], ENT_QUOTES); ?> | <?php echo htmlspecialchars($post['Created'], ENT_QUOTES); ?> | 
<?php endif; ?>


<?php if (htmlspecialchars($post['message_id'], ENT_QUOTES) == $_REQUEST['id']):
    if(($_SESSION['id'] != $post['created_by']) && ($_SESSION['id'] != 1)  ){
        header('Location: login.php');
    } ?>
<!-- 編集フォーム -->
<form action='edit2.php?id=<?php echo htmlspecialchars($post['message_id'], ENT_QUOTES);?>' method="post">
<div class="message">
<textarea name="remessage" cols='50' rows='1'><?php echo htmlspecialchars($post['message'], ENT_QUOTES); ?></textarea>

<!-- 編集 -->
<?php if(($_SESSION['id'] == $post['created_by']) || ($_SESSION['id'] == 1)  ): ?>
<input type="submit" value="更新する" class="button03">
</form>
<?php endif; ?>


<?php endif; ?>
</p>

<?php endforeach; ?>
</body>
</html>
