<?php
// Волшебные ковычки
include_once $_SERVER['DOCUMENT_ROOT'] .
    '/includes/magicquotes.inc.php';

// Форма добавления шутки
if (isset($_GET['add'])) {
    $pageTitle = 'Новая шутка';
    $action = 'addform';
    $text = '';
    $authorid = '';
    $id = '';
    $button = 'Добавить шутку';

    include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';

    // Формирование списка авторов
    try
    {
        $result = $pdo->query('SELECT id, name FROM author');
    } catch (PDOException $e) {
        $error = 'Ошибка при извлечении списка авторов.';
        include 'error.html.php';
        exit();
    }

    foreach ($result as $row) {
        $authors[] = array('id' => $row['id'], 'name' => $row['name']);
    }

    // Формирование списка категорий
    try
    {
        $result = $pdo->query('SELECT id, name FROM category');
    } catch (PDOException $e) {
        $error = 'Error fetching list of categories.';
        include 'error.html.php';
        exit();
    }

    foreach ($result as $row) {
        $categories[] = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'selected' => false);
    }

    include 'form.html.php';
    exit();
}

// Добавление шутки
if (isset($_GET['addform'])) {
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';

    if ($_POST['author'] == '') {
        $error = 'Вы должны выбрать автора для этой шутки. Вернитесь назад и попробуйте еще раз.';
        include 'error.html.php';
        exit();
    }

    try
    {
        $sql = 'INSERT INTO joke SET
        joketext = :joketext,
        jokedate = CURDATE(),
        authorid = :authorid';
        $s = $pdo->prepare($sql);
        $s->bindValue(':joketext', $_POST['text']);
        $s->bindValue(':authorid', $_POST['author']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Ошибка при добавлении шутки.';
        include 'error.html.php';
        exit();
    }

    $jokeid = $pdo->lastInsertId();

    if (isset($_POST['categories'])) {
        try
        {
            $sql = 'INSERT INTO jokecategory SET
          jokeid = :jokeid,
          categoryid = :categoryid';
            $s = $pdo->prepare($sql);

            foreach ($_POST['categories'] as $categoryid) {
                $s->bindValue(':jokeid', $jokeid);
                $s->bindValue(':categoryid', $categoryid);
                $s->execute();
            }
        } catch (PDOException $e) {
            $error = 'Ошибка при добавлении шутки в выбранные категории.';
            include 'error.html.php';
            exit();
        }
    }

    header('Location: .');
    exit();
}
// Форма редактирования шутки
if (isset($_POST['action']) and $_POST['action'] == 'Редактировать') {
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';

    try
    {
        $sql = 'SELECT id, joketext, authorid FROM joke WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Error fetching joke details.';
        include 'error.html.php';
        exit();
    }
    $row = $s->fetch();

    $pageTitle = 'Редактировать шутку';
    $action = 'editform';
    $text = $row['joketext'];
    $authorid = $row['authorid'];
    $id = $row['id'];
    $button = 'Обновить шутку';

    // Формируем список авторов
    try
    {
        $result = $pdo->query('SELECT id, name FROM author');
    } catch (PDOException $e) {
        $error = 'Ошибка при извлечении списка авторов.';
        include 'error.html.php';
        exit();
    }

    foreach ($result as $row) {
        $authors[] = array('id' => $row['id'], 'name' => $row['name']);
    }

    // Получаем список категорий, к которым принадлежит шутка
    try
    {
        $sql = 'SELECT categoryid FROM jokecategory WHERE jokeid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $id);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Ошибка при извлечении списка выбранных категорий.';
        include 'error.html.php';
        exit();
    }

    foreach ($s as $row) {
        $selectedCategories[] = $row['categoryid'];
    }

    // Формируем список всех категорий
    try
    {
        $result = $pdo->query('SELECT id, name FROM category');
    } catch (PDOException $e) {
        $error = 'Ошибка при извлечении всех категорий.';
        include 'error.html.php';
        exit();
    }

    foreach ($result as $row) {
        $categories[] = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'selected' => in_array($row['id'], $selectedCategories));
    }

    include 'form.html.php';
    exit();
}

// Редактирование шутки
if (isset($_GET['editform'])) {
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';

    if ($_POST['author'] == '') {
        $error = 'You must choose an author for this joke.
        Click &lsquo;back&rsquo; and try again.';
        include 'error.html.php';
        exit();
    }

    try
    {
        $sql = 'UPDATE joke SET
        joketext = :joketext,
        authorid = :authorid
        WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->bindValue(':joketext', $_POST['text']);
        $s->bindValue(':authorid', $_POST['author']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Error updating submitted joke.';
        include 'error.html.php';
        exit();
    }

    try
    {
        $sql = 'DELETE FROM jokecategory WHERE jokeid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Error removing obsolete joke category entries.';
        include 'error.html.php';
        exit();
    }

    if (isset($_POST['categories'])) {
        try
        {
            $sql = 'INSERT INTO jokecategory SET
          jokeid = :jokeid,
          categoryid = :categoryid';
            $s = $pdo->prepare($sql);

            foreach ($_POST['categories'] as $categoryid) {
                $s->bindValue(':jokeid', $_POST['id']);
                $s->bindValue(':categoryid', $categoryid);
                $s->execute();
            }
        } catch (PDOException $e) {
            $error = 'Error inserting joke into selected categories.';
            include 'error.html.php';
            exit();
        }
    }

    header('Location: .');
    exit();
}

if (isset($_POST['action']) and $_POST['action'] == 'Удалить') {
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';

    // Удаляем записи о  категориях для этой шутки
    try
    {
        $sql = 'DELETE FROM jokecategory WHERE jokeid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Ошибка при удаление шутки из категории.';
        include 'error.html.php';
        exit();
    }

    // Удаляем шутку
    try
    {
        $sql = 'DELETE FROM joke WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Ошибка при удалении шутки.';
        include 'error.html.php';
        exit();
    }

    header('Location: .');
    exit();
}

// Поиск шутки

if (isset($_GET['action']) and $_GET['action'] == 'search') {

    //Подключение к БД
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';

    // Базовое выражение SELECT
    $select = 'SELECT id, joketext';
    $from = ' FROM joke';
    $where = ' WHERE TRUE';

    $placeholders = array();

    if ($_GET['author'] != '') // Автор выбран
    {
        $where .= " AND authorid = :authorid";
        $placeholders[':authorid'] = $_GET['author'];
    }

    if ($_GET['category'] != '') // Категория выбрана
    {
        $from .= ' INNER JOIN jokecategory ON id = jokeid';
        $where .= " AND categoryid = :categoryid";
        $placeholders[':categoryid'] = $_GET['category'];
    }

    if ($_GET['text'] != '') // Была указана искомая строка
    {
        $where .= " AND joketext LIKE :joketext";
        $placeholders[':joketext'] = '%' . $_GET['text'] . '%';
    }

    try
    {
        $sql = $select . $from . $where;
        $s = $pdo->prepare($sql);
        $s->execute($placeholders);
    } catch (PDOException $e) {
        $error = 'Ошибка при извлечении шуток.';
        include 'error.html.php';
        exit();
    }

    foreach ($s as $row) {
        $jokes[] = array('id' => $row['id'], 'text' => $row['joketext']);
    }

    include 'jokes.html.php';
    exit();
}

// Выводим форму поиска

// Подключение к БД
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';

try
{
    $result = $pdo->query('SELECT id, name FROM author');
} catch (PDOException $e) {
    $error = 'Ошибка при извлечении записей об авторах';
    include 'error.html.php';
    exit();
}

foreach ($result as $row) {
    $authors[] = array('id' => $row['id'], 'name' => $row['name']);
}

try
{
    $result = $pdo->query('SELECT id, name FROM category');
} catch (PDOException $e) {
    $error = 'Ошибка при извлечении категорий';
    include 'error.html.php';
    exit();
}

foreach ($result as $row) {
    $categories[] = array('id' => $row['id'], 'name' => $row['name']);
}

include 'searchform.html.php';
