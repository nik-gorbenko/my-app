<?php
require_once "ClassUser.php";
require_once "ClassComment.php";
session_start();


        if ($_POST["type"] == "edit") {
            $id = $_POST["id"];
            $text = trim($_POST["text"]);
            $comment = Comment::update($id, $text);

            echo json_encode($comment);
        }

        if ($_POST["type"] == "remove") {
            $id = $_POST["id"];
            $comment = Comment::delete($id);

            echo json_encode($comment);
        }

        if (isset($_POST["form-comment__button"])) {
            $text = $_POST["form-comment__text"];
            $user_id = $_POST["form-comment__user-id"];
            $element_id = $_POST["form-comment__element-id"];
            $date = new DateTime('NOW');
            $comment = new Comment($user_id, $text, $date->format("Y-m-d h:i:s"), $element_id);
            $comment->create();
            header("Location:/index.php");
        }

        if (isset($_POST["form-login__button"])) {
            $login = $_POST["form-login__login"];
            $pass = md5($_POST["form-login__pass"]."kB123JBSD");
            $user = User::issetUser($login, $pass);

            if ($user) {
                $_SESSION["user"] = ["name"=> $user["name"], "id" => $user["id"], "isAdmin" => $user["type"]];
                if (isset($_COOKIE["user_id"])) {
                    $_COOKIE["user_id"] = $user["id"];
                } else {
                    setcookie("user_id", $user["id"], 0);
                }
            } else {
                setcookie("LOGINERROR", true, time()+10);
            }
            header("Location:/index.php");
        }

        if (isset($_POST["form-register__button"])) {
            $login = $_POST["form-register__login"];
            $pass = $_POST["form-register__pass"];
            $pass_confirm = $_POST["form-register__pass-confirm"];
            $email = $_POST["form-register__email"];
            $name = $_POST["form-register__name"];
            $family = $_POST["form-register__family"];
            if ($pass != $pass_confirm) {
                setcookie("PASSERROR", true, time()+3);
            } else {
                if (isset($_COOKIE["PASSERROR"])) {
                    setcookie("PASSERROR", false, time() - 3600);
                }
                if (!User::loginExist($login)) {
                    $user = new User($name, $family, $email, $login, md5($pass . "kB123JBSD"));
                    if ($user->create()) {
                        setcookie("ACCESSREGISTRATION", true, time() + 3);
                    };
                    if (isset($_COOKIE["LOGINEXIST"])) {
                        setcookie("LOGINEXIST", false, time() - 3600);
                    }
                } else {
                    setcookie("LOGINEXIST", true, time() + 3);
                }
            }
            header("Location:/index.php");
        }