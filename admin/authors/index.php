<?php

// Волшебные ковычки
include_once $_SERVER['DOCUMENT_ROOT'] .
    '/includes/magicquotes.inc.php';

include_once $_SERVER['DOCUMENT_ROOT'] .
    '/includes/access.inc.php';

if(!userIsLoggedIn()){
    include '../login.html.php';
    exit();
}
if(!userHasRole('Администратор учетных записей')){
    $error = 'Доступ к этой странице имеет только администратор учетных записей';
    include '../accessdenied.html.php';
    exit();
}
// Форма добавления автора
if (isset($_GET['add'])) {
    $pageTitle = 'Новый автор';
    $action = 'addform';
    $name = '';
    $email = '';
    $id = '';
    $button = 'Добавить автора';

    include 'form.html.php';
    exit();
}
// Добавление автора
if (isset($_GET['addform'])) {
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';

    try
    {
        $sql = 'INSERT INTO author SET
        name = :name,
        email = :email';
        $s = $pdo->prepare($sql);
        $s->bindValue(':name', $_POST['name']);
        $s->bindValue(':email', $_POST['email']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Ошибка при добавлении автора.';
        include 'error.html.php';
        exit();
    }

    header('Location: .');
    exit();
}
// Форма редактирование автора
if (isset($_POST['action']) and $_POST['action'] == 'Редактировать') {
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';

    try
    {
        $sql = 'SELECT id, name, email FROM author WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Ошибка при извлечении информации об авторе.';
        include 'error.html.php';
        exit();
    }

    $row = $s->fetch();

    $pageTitle = 'Редактирование автора';
    $action = 'editform';
    $name = $row['name'];
    $email = $row['email'];
    $id = $row['id'];
    $button = 'Обновить информацию об авторе';

    include 'form.html.php';
    exit();
}
// Редактирование автора
if (isset($_GET['editform'])) {
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';

    try
    {
        $sql = 'UPDATE author SET
        name = :name,
        email = :email
        WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->bindValue(':name', $_POST['name']);
        $s->bindValue(':email', $_POST['email']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Ошибка при обновлении записи об авторе.';
        include 'error.html.php';
        exit();
    }

    header('Location: .');
    exit();
}
// Удаление автора и его шуток
if (isset($_POST['action']) and $_POST['action'] == 'Удалить') {
    // Подключаемся к БД
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';

    // Получаем шутки автора
    try
    {
        $sql = 'SELECT id FROM joke WHERE authorid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Ошибка при получении списка шуток на удаление.' . $e - getMessage();
        include 'error.html.php';
        exit();
    }

    $result = $s->fetchAll();

    // Удаление запией о категорях шуток
    try
    {
        $sql = 'DELETE FROM jokecategory WHERE jokeid = :id';
        $s = $pdo->prepare($sql);

        // Для каждой шутки
        foreach ($result as $row) {
            $jokeId = $row['id'];
            $s->bindValue(':id', $jokeId);
            $s->execute();
        }
    } catch (PDOException $e) {
        $error = 'Ошибка при удалении записей о категориях шуток.';
        include 'error.html.php';
        exit();
    }

    // Удаляем шутки автора
    try
    {
        $sql = 'DELETE FROM joke WHERE authorid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Ошибка при удалении нуток автора.';
        include 'error.html.php';
        exit();
    }

    // Удаление автора
    try
    {
        $sql = 'DELETE FROM author WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Ошибка при удалении автора.';
        include 'error.html.php';
        exit();
    }

    header('Location: .');
    exit();
}

// Выводис список авторов
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
try {
    $result = $pdo->query('SELECT id, name from author');
} catch (PDOException $e) {
    $error = 'Ошибка при извлечении авторов из базы данных!' . $e->getMessage();
    include 'error.html.php';
    exit();
}
foreach ($result as $row) {
    $authors[] = array('id' => $row['id'], 'name' => $row['name']);
}
include 'authors.html.php';
