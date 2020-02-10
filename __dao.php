<?php

function newPDO()
{
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=biblio_db;charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $exception) {
        header('Location: index.php');
    }
    return $pdo;
}

function getUserInfo($user_name)
{
    $pdo = newPDO();
    $query = $pdo->query(
        "SELECT * FROM users NATURAL JOIN user_categories WHERE user_id='$user_name';"
    );
    return $query->fetch();
}

function getBookGenres()
{
    $pdo = newPDO();
    $query = $pdo->query("SELECT genre_id, genre FROM `genres`;");
    return $query->fetchAll();
}

function searchBooks($author, $title, $genre)
{
    $sql = "SELECT DISTINCT genre_id, genre, title, concat(first_name, ' ', last_name) AS author 
            FROM genres NATURAL JOIN book_genres NATURAL JOIN books NATURAL JOIN book_authors NATURAL JOIN authors";
            $word = ' WHERE ';          /* premier mot avant les critères de filtrage */

            if ($author != '') {
                $sql .= $word . "concat(first_name, ' ', last_name) LIKE '%$author%'";
                $word = ' AND ';        /* changement du mot avant les potentiels critères supplémentaires */
            }
            if ($title != '') {
                $sql .= $word . "title LIKE '%$title%'";
                $word = ' AND ';
            }
            if ($genre != '') {
                $sql .= $word . "genre_id = '$genre'";
                $word = ' AND ';
            }

            $sql .= " GROUP BY title;";

            $pdo = newPDO();
            $query = $pdo->query($sql);
            return $query->fetchAll();
}