<?php
require_once '../utils/Utils.php';
$db = Utils::getInstance() -> getDB();
$sql = "DELETE FROM `users` WHERE `user_id` = :userID";
$db -> execute(
    $sql,
    ['userID' => $_GET['userID']]
);
$sql = "DELETE FROM `posts` WHERE `author_id` = :userID";
$db -> execute($sql, ['userID' => $_GET['userID']]);
$sql = "DELETE FROM `messages` WHERE `author_id` = :userID";
$db -> execute($sql, ['userID' => $_GET['userID']]);
$sql = "DELETE FROM `comments` WHERE `author_id` = :userID";
$db -> execute($sql, ['userID' => $_GET['userID']]);
$sql = "DELETE FROM `friends` WHERE `user1_id` = :userID OR `user2_id` = :userID";
$db -> execute($sql, ['userID' => $_GET['userID']]);
$sql = "DELETE FROM `likes` WHERE `user_id` = :userID";
$db -> execute($sql, ['userID' => $_GET['userID']]);
$sql = "DELETE FROM `profiles` WHERE `user_id` = :userID";
$db -> execute($sql, ['userID' => $_GET['userID']]);
header('Location: ../pages/admin.php');
?>