<?php 
    require_once "./../../database.php";

    // Tarkistaa onko myyja kirjautunut sisälle
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["flag"] !== "admin") {
        header("location: ./../../index.php");
        exit();
    }

    $username = $password = $password_confirm = "";
    $username_err = $password_err = $password_confirm_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {

        // Tarkistaa onko käyttäjänimi jo käytössä
        if(empty(trim($_POST["username"]))) {
            $username_err = "Kirjoita käyttäjänimesi!";
        } else {
            $sql = "SELECT myyjaid FROM myyja WHERE kayttajanimi = ?";

            if($mysqli->set_charset("utf8")) {
                if($stmt = $mysqli->prepare($sql)) {

                    $stmt->bind_param("s", $param_username);

                    $param_username = trim($_POST["username"]);

                    if($stmt->execute()) {
                        $stmt->store_result();

                        if($stmt->num_rows == 1) {
                            $username_err = "Tämä käyttäjänimi on jo käytössä! Valitse joku muu.";
                        } else {
                            $username = trim($_POST["username"]);
                        }
                    } else {
                        echo '<p class="text-danger text-center">Jokin meni vikaan! Yritä myöhemmin uudelleen!</p>';
                    }
                }
                $stmt->close();
            }
        }
    

        // Salasana
        if(empty(trim($_POST["password"]))) {
            $password_err = "Ole hyvä ja kirjoita salasana.";
        } elseif(strlen(trim($_POST["password"])) < 6) {
            $password_err = "Salasanan täytyy olla vähintään 6 merkkiä!";
        } else {
            $password = trim($_POST["password"]);
        }

        // Vahvista salasana
        if(empty(trim($_POST["password_confirm"]))) {
            $password_confirm_err = "Vahvista salasana!";
        } else {
            $password_confirm = trim($_POST["password_confirm"]);

            if(empty($password_confirm_err) && ($password != $password_confirm)) {
                $password_confirm_err = "Salasanat eivät täsmänneet keskenään!";
            }
        }

        if(empty($username_err) && empty($password_err) && empty($password_confirm_err)) {
            
            $sql = "INSERT INTO myyja (kayttajanimi, salasana) VALUES (?, ?)";

            if($mysqli->set_charset("utf8")) {
                if($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param("ss", $param_username, $param_password);

                    $param_username = $username;
                    $param_password = password_hash($password, PASSWORD_DEFAULT);

                    if($stmt->execute()) {
                        header("location: ./../control_panel.php");
                    } else {
                        echo '<p class="text-danger text-center">Jokin meni vikaan! Yritä myöhemmin uudelleen!</p>';
                    }
        
                }
                // Sulje stmt
                $stmt->close();
            }
        }
        // Sulje sql yhteys
        $mysqli->close();
    }
?>
<!DOCTYPE html>
<html lang="fi">
    <head>
        <meta charset="utf-8">
        <title>Videovuokraamo - Luo uusi myyjäntili</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="./../../css/style.min.css">
    </head>
    <body>
        <div class="wrapper">
            <div class="page-header">
                <h2>Rekisteröidy</h2>
            </div>
            <hr>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <!-- Käyttäjänimi -->
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <label class="font-weight-bold">Käyttäjänimi:</label>
                    <input type="text" placeholder="Kirjoita käyttäjänimi" name="username" class="form-control" value="<?php echo $username; ?>">
                    <small class="form-text text-danger">
                        <?php echo $username_err;?>
                    </small>
                </div>

                <!-- Salasana -->
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label class="font-weight-bold">Salasana:</label>
                    <input type="password" placeholder="Kirjoita salasana" name="password" class="form-control">
                    <small class="form-text text-danger">
                        <?php echo $password_err;?>
                    </small>
                </div>
                
                <!-- Vahvista salasana -->
                <div class="form-group <?php echo (!empty($password_confirm_err)) ? 'has-error' : ''; ?>">
                    <label class="font-weight-bold">Vahvista salasana:</label>
                    <input type="password" placeholder="Kirjoita salasana uudelleen" name="password_confirm" class="form-control">
                    <small class="form-text text-danger">
                        <?php echo $password_confirm_err;?>
                    </small>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Rekisteröidy">
                    <a href="./../control_panel.php" class="btn btn-secondary">Peruuta</a>
                    <input type="reset" class="btn btn-warning float-right" value="Tyhjennä kentät">
                </div>
            </form>
        </div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="./../js/init.min.js"></script>
    </body>
</html>