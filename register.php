
<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    session_start();
    if (isset($_SESSION['SESSION_EMAIL'])) {
        header("Location: welcome.php");
        die();
    }
    require 'vendor/autoload.php';
    include 'db.php';
    $msg = "";
    if (isset($_POST['submit'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, md5($_POST['password']));
        $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm-password']));
        $code = mysqli_real_escape_string($conn, md5(rand()));
        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE email='{$email}'")) > 0) {
            $msg = "<div'>{$email} - Такой аккаунт уже есть</div>";
        } else {
            if ($password === $confirm_password) {
                $sql = "INSERT INTO users (name, email, password, code) VALUES ('{$name}', '{$email}', '{$password}', '{$code}')";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    echo "<div style='display: none;'>";
                    $mail = new PHPMailer(true);

                    try {
                        
                        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->Username   = 'bekjanm166@gmail.com';                     //SMTP username
                        $mail->Password   = 'bekjan123a)';                               //SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                        
                        $mail->setFrom('YOUR_EMAIL_HERE');
                        $mail->addAddress($email);

                        
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = 'Link for verify';
                        $mail->Body    = 'Сылка для потверждения<b><a href="http://localhost/login/?verification='.$code.'">http://localhost/login/?verification='.$code.'</a></b>';

                        $mail->send();
                        echo 'Сылка для подтверждения была отправлена';
                    } catch (Exception $e) {
                        echo "Сылка для подтверждения не была отправлена: {$mail->ErrorInfo}";
                    }
                    echo "</div>";
                    $msg = "<div'>'Сылка для подтверждения была отправлена</div>";
                } else {
                    $msg = "<div'>Сылка для подтверждения не была отправлена</div>";
                }
            } else {
                $msg = "<div'>Пароли не совпадают</div>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>Регистрация</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />
</head>
<body>
    <div>
        <h2>Регистрация</h2>
        <?php echo $msg; ?>
        <form action="" method="post">
            <input type="text" class="name" name="name" placeholder="Имя" value="<?php if (isset($_POST['submit'])) { echo $name; } ?>" required>
            <input type="email" class="email" name="email" placeholder="Почта" value="<?php if (isset($_POST['submit'])) { echo $email; } ?>" required>
            <input type="password" class="password" name="password" placeholder="Пароль" required>
            <input type="password" class="confirm-password" name="confirm-password" placeholder="Потвердите пароль" required>
            <button name="submit" class="btn" type="submit">Регистрация</button>
        </form>
        <div class="social-icons">
            <p>Есть аккаунт! <a href="index.php">Логин</a>.</p>
        </div>
    </div>
</body>
</html>
