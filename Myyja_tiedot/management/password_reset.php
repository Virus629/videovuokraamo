<?php
    // Tarkistaa onko myyja kirjautunut sisälle
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: ./../login/login.php");
        exit();
    }

    require_once "./../../database.php";

    $new_passwd = $confirm_passwd = "";
    $new_passwd_err = $confirm_passwd_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {


        if(empty(trim($_POST["new_passwd"]))) {
            $new_passwd_err = "Kirjoita uusi salasana!";
        } elseif(strlen(trim($_POST["new_passwd"])) < 6) {
            $new_passwd_err = "Salasanan täytyy olla 6 merkkiä!";
        } else {
            $new_passwd = trim($_POST["new_passwd"]);
        }

        if(empty(trim($_POST["confirm_passwd"]))) {
            $confirm_passwd_err = "Kirjoita uusi salasana uusiksi!";
        } else {
            $confirm_passwd = trim($_POST["confirm_passwd"]);
            if(empty($new_passwd_err) && ($new_passwd != $confirm_passwd)) {
                $confirm_passwd_err = "Salasanat eivät täsmää!";
            }
        }

        if(empty($new_passwd_err) && empty($confirm_passwd_err)) {
            $sql = "UPDATE myyja SET salasana = ? WHERE myyjaid = ?";

            if($mysqli->set_charset("utf8")) {
                if($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param("si", $param_passwd, $param_id);

                    $param_passwd = password_hash($new_passwd, PASSWORD_DEFAULT);
                    $param_id = $_SESSION["id"];

                    if($stmt->execute()) {
                        session_destroy();
                        header("location: ./../control_panel.php");
                        exit();
                    } else {
                        echo '<p class="text-danger text-center">Jokin meni vikaan! Yritä myöhemmin uudelleen!</p>';
                    }
                }
                $stmt->close();
            }
            $mysqli->close();
        }
    }
?>
<!DOCTYPE html>
<html lang="fi">
    <head>
        <meta charset="UTF-8">
        <title>Videovuokraamo - Vaihda salasana</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="./../../css/style.min.css">
    </head>
    <body>
        <div class="wrapper">
            <div class="page-header">
                <h2>Vaihda Salasanasi</h2>
            </div>
            <hr>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($new_passwd_err)) ? 'has-error' : ''; ?>">
                    <label class="font-weight-bold">Uusi salasana:</label>
                    <input type="password" name="new_passwd" class="form-control" value="<?php echo $new_passwd; ?>">
                    <small class="form-text text-danger">
                        <?php echo $new_passwd_err;?>
                    </small>
                </div>

                <div class="form-group <?php echo (!empty($confirm_passwd_err)) ? 'has-error' : '';?>">
                    <label class="font-weight-bold">Vahvista uusi salasana:</label>
                    <input type="password" name="confirm_passwd" class="form-control">
                    <small class="form-text text-danger">
                        <?php echo $confirm_passwd_err;?>
                    </small>
                </div>
                
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Vahvista">
                    <a class="btn btn-secondary" href="./../control_panel.php">Peruuta</a>
                </div>
            </form>
        </div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="./../../js/init.min.js"></script>
    </body>
</html>


