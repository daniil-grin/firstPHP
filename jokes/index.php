<?php
// Волшебные ковычки
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/magicquotes.inc.php';

// Форма добавления шутки
if (isset($_GET['addjoke'])) {
    include 'form.html.php';
    exit();
}
// Подключение к БД
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';

// Добавление шутки
if (isset($_POST['joketext'])) {
    try {
        $sql = 'INSERT INTO joke SET
      joketext = :joketext,
      jokedate = CURDATE()';
        $s = $pdo->prepare($sql);
        $s->bindValue(':joketext', $_POST['joketext']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Ошибка при добалении шутки: ' . $e->getMessage();
        include 'error.html.php';
        exit();
    }
    header('Location: .');
    exit();
}
// Удаление шутки
if (isset($_GET['deletejoke'])) {
    try {
        $sql = 'DELETE FROM joke WHERE id=:id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Ошибка при удалении шутки: ' . $e->getMessage();
        include 'error.html.php';
        exit();
    }
    header('Location: .');
    exit();
}
// Извлечение шуток из БД
try {
    $sql = 'SELECT joke.id, joketext,name, email from joke INNER JOIN author ON joke.authorid=author.id';

    $result = $pdo->query($sql);
} catch (PDOException $e) {
    $error = 'Ошибка при извлечении шуток: ' . $e->getMessage();
    include 'error.html.php';
    exit();
}
while ($row = $result->fetch()) {
    $jokes[] = array(
        'id' => $row['id'],
        'text' => $row['joketext'],
        'name' => $row['name'],
        'email' => $row['email'],
    );
}
// Страница для отображения шуток
include 'jokes.html.php';
