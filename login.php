<?php
session_start();
require('dbconnect.php');
 
//MySQLのデータと照らし合わせる
if(!empty($_POST)) {
    if(($_POST['email'] != '') && ($_POST['password'] != '')) {
        $login = $db->prepare('SELECT * FROM logindata WHERE email=?');
        $login->execute(array($_POST['email']));
        $member=$login->fetch();
        
        if(password_verify($_POST['password'],$member['password'])) {
            $_SESSION['id'] = $member['id'];
            $_SESSION['time'] =time();
            header('Location: /post/posting.php');
            exit();
        } else {
            $error['login']='failed';
        } 
    } else {
        $error['login'] ='blank';
    }
}
 
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>login</title>
        <!-- BootstrapのCSS読み込み -->
        <link href="/survey/css/bootstrap.min.css" rel="stylesheet">
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
    </head>

    <body class="text-center">
    <div class="card">
    <article class="card-body">
        <h1 class="card-title text-center mb-4 mt-1">ログイン</h1>
        <form class="w-25 mx-auto" action='' method="post">
            <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                </div>
                <input class="form-control" type="name" name="name" value="<?php echo htmlspecialchars($_POST['name']??"", ENT_QUOTES); ?>" placeholder="ユーザーID" autofocus required/>
            </div></div>
            <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
                </div>
                <input class="form-control" type="name" name="email" value="<?php echo htmlspecialchars($_POST['email']??"", ENT_QUOTES); ?>" placeholder="email" required/>
            </div></div>
            <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                </div>
                <input class="form-control" type="password" name="password" value="<?php echo htmlspecialchars($_POST['password']??"", ENT_QUOTES); ?>"placeholder="パスワード" required/>
            </div></div>
            
            <script>var errormessage = []</script>
            <?php if (isset($error['login']) &&  ($error['login'] =='blank')): ?>
                <p class="error" role="alert">name,email,passwordを入力してください</p>
                <script>errormessage.push('name,email,password')</script>
                <script src="/survey/error.js"></script>
            <?php endif; ?>
        
            <?php if( isset($error['login']) &&  $error['login'] =='failed'): ?>
                <p class="alert alert-danger" role="alert">emailかpasswordが間違っています</p>
                <script>errormessage.push('正しいemail,password')</script>
                <script src="/post/error.js"></script>
            <?php endif; ?>
        
            <div class="login2"><input type="submit" value="login" class="btn btn-outline-primary my-1"></div>
        
        </form>
        <p class="mt-2 mb-3 text-muted">&copy; 2022</p>
    </article>
    </div>
    </body>
</html>