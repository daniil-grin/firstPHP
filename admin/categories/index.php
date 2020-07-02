<?php

// Волшебные ковычки
include_once $_SERVER['DOCUMENT_ROOT'] .
    '/includes/magicquotes.inc.php';

include_once $_SERVER['DOCUMENT_ROOT'] .
    '/includes/access.inc.php';

if (!userIsLoggedIn()) {
    include '../login.html.php';
    exit();
}
if (!userHasRole('Администратор сайта')) {
    $error = 'Доступ к этой странице имеет только администратор сайта';
    include '../accessdenied.html.php';
    exit();
}
// Форма добавления категории
if (isset($_GET['add'])) {
    $pageTitle = 'Новая категория';
    $action = 'addform';
    $name = '';
    $id = '';
    $button = 'Добавить категорию';

    include 'form.html.php';
    exit();
}
// Добавление категории
if (isset($_GET['addform'])) {
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
    try
    {
        $sql = 'INSERT INTO category SET
        name = :name';
        $s = $pdo->prepare($sql);
        $s->bindValue(':name', $_POST['name']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Ошибка добавления категории.';
        include 'error.html.php';
        exit();
    }
    header('Location: .');
    exit();
}
// Форма редактирования категории
if (isset($_POST['action']) and $_POST['action'] == 'Редактировать') {
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
    try
    {
        $sql = 'SELECT id, name FROM category WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Ошибка редактирования категории.';
        include 'error.html.php';
        exit();
    }

    $row = $s->fetch();

    $pageTitle = 'Редактирование категории';
    $action = 'editform';
    $name = $row['name'];
    $id = $row['id'];
    $button = 'Обновить информацию о категории';

    include 'form.html.php';
    exit();
}
// Редактирование категории
if (isset($_GET['editform'])) {
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
    try
    {
        $sql = 'UPDATE category SET
        name = :name
        WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->bindValue(':name', $_POST['name']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Ошибка при обновлении записи о категории.';
        include 'error.html.php';
        exit();
    }
    header('Location: .');
    exit();
}
// Удаление категории
if (isset($_POST['action']) and $_POST['action'] == 'Удалить') {
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';

    // Удаление связей с шутками этой категории
    try
    {
        $sql = 'DELETE FROM jokecategory WHERE categoryid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Error removing jokes from category.';
        include 'error.html.php';
        exit();
    }
    // Удаление категории
    try
    {
        $sql = 'DELETE FROM category WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Error deleting category.';
        include 'error.html.php';
        exit();
    }
    header('Location: .');
    exit();
}

// Отображение всех категорий
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
try
{
    $result = $pdo->query('SELECT id, name FROM category');
} catch (PDOException $e) {
    $error = 'Ошибка получения информации о категориях';
    include 'error.html.php';
    exit();
}
foreach ($result as $row) {
    $categories[] = array('id' => $row['id'], 'name' => $row['name']);
}
include 'categories.html.php';
