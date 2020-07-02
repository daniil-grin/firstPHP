<?php

function userIsLoggedIn()
{
    if (isset($_POST['action']) and $_POST['action'] == 'login') {
        if (!isset($_POST['email']) or $_POST['email'] == '' or
            !isset($_POST['password']) or $_POST['password'] == '') {
            $GLOBALS['loginError'] = 'Пожалуйста, заполните оба поля';
            return false;
        }

        $password = md5($_POST['password'] . 'int_joke');

        if (databaseContainsAuthor($_POST['email'], $password)) {
            session_start();
            $_SESSION['loggedIn'] = true;
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['password'] = $password;
            return true;
        } else {
            session_start();
            unset($_SESSION['loggedIn']);
            unset($_SESSION['email']);
            unset($_SESSION['password']);
            $GLOBALS['loginError'] =
                'Указан неверный адрес электронной почты или пароль.';
            return false;
        }
    }

    if (isset($_POST['action']) and $_POST['action'] == 'logout') {
        session_start();
        unset($_SESSION['loggedIn']);
        unset($_SESSION['email']);
        unset($_SESSION['password']);
        header('Location: ' . $_POST['goto']);
        exit();
    }

    session_start();
    if (isset($_SESSION['loggedIn'])) {
        return databaseContainsAuthor($_SESSION['email'], $_SESSION['password']);
    }
}
// Проверка наличия автора
function databaseContainsAuthor($email, $password)
{
    include 'db.inc.php';

    try
    {
        $sql = 'SELECT COUNT(*) FROM author
        WHERE email = :email AND password = :password';
        $s = $pdo->prepare($sql);
        $s->bindValue(':email', $email);
        $s->bindValue(':password', $password);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Ошибка при поиске автора.';
        include 'error.html.php';
        exit();
    }

    $row = $s->fetch();

    if ($row[0] > 0) {
        return true;
    } else {
        return false;
    }
}

// Проверка наличия роли
function userHasRole($role)
{
    include 'db.inc.php';

    try
    {
        $sql = "SELECT COUNT(*) FROM author
        INNER JOIN authorrole ON author.id = authorid
        INNER JOIN role ON roleid = role.id
        WHERE email = :email AND role.id = :roleId";
        $s = $pdo->prepare($sql);
        $s->bindValue(':email', $_SESSION['email']);
        $s->bindValue(':roleId', $role);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Ошибка поиска ролей автора.';
        include 'error.html.php';
        exit();
    }

    $row = $s->fetch();

    if ($row[0] > 0) {
        return true;
    } else {
        return false;
    }
}
