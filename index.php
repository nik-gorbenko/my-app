<?
    require_once "FormController.php";
    session_start();
    if (isset($_GET["do"]) && ($_GET["do"] == "exit")) {
        session_unset();
        setcookie("user_id", $user["id"], time()-3600);
        setcookie("LOGINERROR", false, time() - 3600);
        header("Location:/index.php");
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://kit-pro.fontawesome.com/releases/v5.11.2/css/pro.min.css" rel="stylesheet">
    <link rel="stylesheet" href="app.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/">
        <?php if (isset($_SESSION["user"])):?>
            <?=$_SESSION["user"]["name"];?>
        <?else: ?>
            My App
        <?php endif;?>
    </a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <?php if (isset($_SESSION["user"])):?>
                    <a class="nav-link" href="/?do=exit">Выйти</a>
                <?php else: ?>
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#modal-login">Войти</a>
                <?php endif;?>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#modal-register">Зарегистрироваться</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container">
    <?php if (isset($_COOKIE["LOGINERROR"])):?>
        <?php if ($_COOKIE["LOGINERROR"] == true): ?>
            <div class="alert alert-danger attention" role="alert">
                Неверный логин или пароль!!!
            </div>
        <?php endif;?>
    <?php endif;?>
    <?php if (isset($_COOKIE["LOGINEXIST"])):?>
        <?php if ($_COOKIE["LOGINEXIST"] == true): ?>
            <div class="alert alert-danger attention" role="alert">
                Логин уже существует!!!
            </div>
        <?php endif;?>
    <?php endif;?>
    <?php if (isset($_COOKIE["PASSERROR"])):?>
        <?php if ($_COOKIE["PASSERROR"] == true): ?>
            <div class="alert alert-danger attention" role="alert">
                Введенные пароли не совпадают!!!
            </div>
        <?php endif;?>
    <?php endif;?>
    <?php if (isset($_COOKIE["ACCESSREGISTRATION"])): ?>
        <?php if ($_COOKIE["ACCESSREGISTRATION"] == true): ?>
            <div class="alert alert-success attention" role="alert">
                Вы успешно зарегистрировались!!!
            </div>
        <?php endif;?>
    <?php endif;?>
</div>
<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <div id="banner" class="banner d-flex m-3 justify-content-center">
                <img src="https://www.google.ru/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png" alt="Google">
            </div>
            <hr>
            <?php if (isset($_SESSION["user"])):?>
            <div class="comment">
                <button class="btn btn-primary comment__add" href="#" data-toggle="modal" data-element-id="banner" data-target="#modal-comment">
                    <i class="fas fa-keyboard"></i>
                    Добавить комментарий
                </button>
            </div>
            <?php endif;?>
            <?php
            $banner_comments = Comment::fetch("banner");
            if ($banner_comments):?>
                <?php foreach ($banner_comments as $comment):?>
                    <div class="comment border rounded p-3" data-id="<?=$comment["id"];?>">
                        <div class="comment__header">
                            <label class="comment__user" for="comment_text_<?=$comment["id"];?>">
                                    <span class="user__name">
                                        <?=$comment["name"];?>
                                    </span>
                                <span class="user__family">
                                        <?=$comment["family"];?>
                                    </span>
                            </label>
                            <span class="comment__date">
                                <?=$comment["date"];?>
                            </span>
                        </div>
                        <div class="comment__body">
                            <?php if (isset($_SESSION["user"])):?>
                                <?php if ($_SESSION["user"]["id"] === $comment["user_id"] ):?>
                                    <textarea class="form-control comment__text" id="comment_text_<?=$comment["id"];?>" data-id="<?=$comment["id"];?>" rows="3"><?=$comment["text"];?>
                                    </textarea>
                                    <div class="comment__buttons-wrapper">
                                        <button class="comment__edit btn btn-primary" data-type="wait" data-id="<?=$comment["id"];?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="comment__remove btn btn-danger" data-id="<?=$comment["id"];?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                <?php elseif(($_SESSION["user"]["isAdmin"] === "1")):?>
                                    <textarea class="form-control comment__text" id="comment_text_<?=$comment["id"];?>" data-id="<?=$comment["id"];?>" rows="3"><?=$comment["text"];?>
                                    </textarea>
                                    <div class="comment__buttons-wrapper">
                                        <button class="comment__edit btn btn-primary" data-type="wait" data-id="<?=$comment["id"];?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="comment__remove btn btn-danger" data-id="<?=$comment["id"];?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                <?php else:?>
                                    <?php echo $_SESSION["user"]["type"]; ?>
                                    <p>
                                        <?=$comment["text"];?>
                                    </p>
                                <?php endif;?>
                            <?php else:?>
                                <p>
                                    <?=$comment["text"];?>
                                </p>
                            <?php endif;?>
                        </div>
                    </div>
                <?php endforeach;?>
            <?php endif;?>
        </div>
        <div class="col-lg-6">
            <div id="banner_apple" class="banner d-flex m-3 justify-content-center">
                <img class="w-100" src="https://www.wallpaperflare.com/static/135/224/983/apple-company-gray-white-apple-wallpaper.jpg" alt="apple">
            </div>
            <hr>
            <?php if (isset($_SESSION["user"])):?>
                <div class="comment">
                    <button class="btn btn-primary comment__add" href="#" data-toggle="modal" data-element-id="banner_apple" data-target="#modal-comment">
                        <i class="fas fa-keyboard"></i>
                        Добавить комментарий
                    </button>
                </div>
            <?php endif;?>
            <?php
            $banner_comments = Comment::fetch("banner_apple");
            if ($banner_comments):?>
                <?php foreach ($banner_comments as $comment):?>
                    <div class="comment border rounded p-3" data-id="<?=$comment["id"];?>">
                        <div class="comment__header">
                            <label class="comment__user" for="comment_text_<?=$comment["id"];?>">
                                    <span class="user__name">
                                        <?=$comment["name"];?>
                                    </span>
                                    <span class="user__family">
                                        <?=$comment["family"];?>
                                    </span>
                            </label>
                            <span class="comment__date">
                                <?=$comment["date"];?>
                            </span>
                        </div>
                        <div class="comment__body">
                            <?php if (isset($_SESSION["user"])):?>
                                <?php if ($_SESSION["user"]["id"] === $comment["user_id"] ):?>
                                    <textarea class="form-control comment__text" id="comment_text_<?=$comment["id"];?>" data-id="<?=$comment["id"];?>" rows="3"><?=$comment["text"];?>
                                    </textarea>
                                    <div class="comment__buttons-wrapper">
                                        <button class="comment__edit btn btn-primary" data-type="wait" data-id="<?=$comment["id"];?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="comment__remove btn btn-danger" data-id="<?=$comment["id"];?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                <?php elseif(($_SESSION["user"]["isAdmin"] === "1")):?>
                                    <textarea class="form-control comment__text" id="comment_text_<?=$comment["id"];?>" data-id="<?=$comment["id"];?>" rows="3"><?=$comment["text"];?>
                                    </textarea>
                                    <div class="comment__buttons-wrapper">
                                            <button class="comment__edit btn btn-primary" data-type="wait" data-id="<?=$comment["id"];?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="comment__remove btn btn-danger" data-id="<?=$comment["id"];?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                    </div>
                                <?php else:?>
                                    <?php echo $_SESSION["user"]["type"]; ?>
                                    <p>
                                        <?=$comment["text"];?>
                                    </p>
                                <?php endif;?>
                            <?php else:?>
                                <p>
                                    <?=$comment["text"];?>
                                </p>
                            <?php endif;?>
                        </div>
                    </div>
                <?php endforeach;?>
            <?php endif;?>
        </div>
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modal-comment">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Комментарий</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="FormController.php" id="form-comment" method="post" class="form">
                    <div class="form-group">
                        <label for="form-comment__text">Ваш комментарий:</label>
                        <textarea type="text" id="form-comment__text" name="form-comment__text" rows="5" cols="20" class="form-control"></textarea>
                    </div>
                    <input type="hidden" value="<?=$_COOKIE['user_id'];?>" name="form-comment__user-id">
                    <input type="hidden" value="" name="form-comment__element-id">
                    <div class="form-group text-center">
                        <button type="submit" name="form-comment__button" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modal-register">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Регистрация</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="FormController.php" id="form-register" method="post" class="form">
                    <div class="form-group">
                        <label for="form-register__name">Имя:</label>
                        <input type="text" required class="form-control" name="form-register__name" id="form-register__name" placeholder="Имя" class="class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="form-register__family">Фамилия:</label>
                        <input type="text" required class="form-control" name="form-register__family" id="form-register__family" placeholder="Фамилия" class="class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="form-register__email">Email:</label>
                        <input type="email" required class="form-control" name="form-register__email" id="form-register__email" placeholder="Email" class="class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="form-register__login">Логин:</label>
                        <input type="text" required class="form-control" name="form-register__login" id="form-register__login" placeholder="Login" class="class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="form-register__pass">Пароль:</label>
                     <input type="password" required class="form-control" name="form-register__pass" id="form-register__pass" placeholder="Введите пароль" class="class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="form-register__pass-confirm">Подтверждение пароля:</label>
                        <input type="password" required class="form-control" name="form-register__pass-confirm" id="form-register__pass-confirm" placeholder="Подтвердите пароль" class="class="form-control">
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" name="form-register__button" class="btn btn-primary">Зарегистрироваться</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modal-login">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Войти</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="FormController.php" id="form-login" method="post" class="">
                    <div class="form-group">
                        <label for="form-login__login">Логин:</label>
                        <input type="text" required class="form-control" name="form-login__login" id="form-login__login">
                    </div>
                    <div class="form-group">
                        <label for="form-login__pass">Пароль:</label>
                        <input type="password" required class="form-control" name="form-login__pass" id="form-login__pass">
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary" name="form-login__button">Войти</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="app.js"></script>
</body>
</html>