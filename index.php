<!-- Code by Brave Coder - https://youtube.com/BraveCoder -->

<?php
    session_start();
    if (isset($_SESSION['SESSION_EMAIL'])) {
        header("Location: home.php");
        die();
    }

    include 'db.php';
    $msg = "";

    if (isset($_GET['verification'])) {
        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE code='{$_GET['verification']}'")) > 0) {
            $query = mysqli_query($conn, "UPDATE users SET code='' WHERE code='{$_GET['verification']}'");
            
            if ($query) {
                $msg = "<div'>Аккаунт потвержден</div>";
            }
        } else {
            header("Location: index.php");
        }
    }

    if (isset($_POST['submit'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, md5($_POST['password']));

        $sql = "SELECT * FROM users WHERE email='{$email}' AND password='{$password}'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);

            if (empty($row['code'])) {
                $_SESSION['SESSION_EMAIL'] = $email;
                header("Location: home.php");
            } else {
                $msg = "<div>Для начал потвердите свой аккаунт</div>";
            }
        } else {
            $msg = "<div>Не правильный пароль или почта</div>";
        }
    }
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>Логин</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />
</head>
<body>
    <h2>Логин</h2>
    <?php echo $msg; ?>
    <form action="" method="post">
        <input type="email" class="email" name="email" placeholder="Поста" required>
        <input type="password" class="password" name="password" placeholder="Пароль" style="margin-bottom: 2px;" required>
        <button name="submit" name="submit" class="btn" type="submit">Логин</button>
    </form>
    <div class="social-icons">
        <p>Создать аккаунт! <a href="register.php">Регистрация</a>.</p>
    </div>             
</body>

</html>