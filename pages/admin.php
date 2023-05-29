<?php
session_start();
require realpath(__DIR__ . '/..') . '/includes/header.php';
require realpath(__DIR__ . '/..') . '/messages.php';
require('../utils/Utils.php');
require_once('../utils/Database.php');
if (isset($_SESSION['logout']) || !isset($_COOKIE['userID'])) {
    setcookie('userID', '', time());
    unset($_SESSION['logout']);
    header('Location: ../utils/setSession.php?page=main');
}
$theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : '';
$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'CZ';

$utils = Utils::getInstance();
$db = $utils -> getDB();

$itemsPerPage = 3;
if (isset($_GET['offset'])) {
    $offset = (int)$_GET['offset'];
} else {
    $offset = 0;
}

$sql = "SELECT COUNT(*) AS a FROM `users`";
$count = intval($db -> execute($sql, [])[0]['a']);

$sql = "SELECT * FROM `users` LEFT JOIN `profiles` USING (user_id) ORDER BY user_id ASC LIMIT " . $itemsPerPage . " OFFSET " . $offset;
$users = $db -> execute($sql, []);
?>

<body id=<?php echo $theme; ?>>
    <a name='up'>
        <?php require realpath(__DIR__ . '/..') . '/includes/navbar.php'; ?>
        <main class='container'>
            <?php require realpath(__DIR__ . '/..') . '/includes/menu.php'; ?>

            <table class = 'admin_table<?php echo $theme; ?>'>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Description</th>
                    <th>Date of birth</th>
                    <th>Post Amount</th>
                    <th>Like Amount</th>
                    <th>Comment Amount</th>
                    <th>Is Admin</th>
                    <th></th>
                </tr>
                <?php 
                    foreach($users as $user): 
                        $userMetrics = $utils -> getUserMetrics($user['user_id'])
                    ?>
                <tr>
                    <td><?php echo $user['user_id']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['phoneNumber']; ?></td>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['surname']; ?></td>
                    <td><?php echo $user['description']; ?></td>
                    <td><?php echo $user['date_birth']; ?></td>
                    <td><?php echo $userMetrics['postAmount']; ?></td>
                    <td><?php echo $userMetrics['likeAmount']; ?></td>
                    <td><?php echo $userMetrics['commentAmount']; ?> </td>
                    <td><?php echo $user['is_admin']; ?></td>
                    <?php if (intval($_COOKIE['userID']) !== intval($user['user_id'])): ?>
                    <td><a href = '../utils/removeUser.php?userID=<?php echo $user['user_id'];?>' class = 'blue'>Remove User</a>
                    <?php else: ?>
                    <td></td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            
            </table>
            <br>
            <table class = 'admin_table<?php echo $theme; ?>'>
                <th>
                <?php for ($i = 1; $i <= ceil($count / $itemsPerPage); $i++) { ?>
                    <a class="<?php echo $offset / $itemsPerPage + 1 == $i ? "active" : ""; ?>" href="admin.php?offset=<?php echo ($i - 1) * $itemsPerPage; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php } ?>
                </th>
            </table>
        </main>
</body>