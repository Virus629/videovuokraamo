<?php
    session_start();

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header('location: ./../../index.php');
        exit;
    }

    require_once "./../../database.php";

    $username = $password = "";
    $username_err = $password_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {

        // Tarkistaa onko käyttjänimi tyhjä
        if(empty(trim($_POST["username"]))) {
            $username_err = "Kirjoita käyttjänimesi!";
        } else {
            $username = trim($_POST["username"]);
        }

        // Tarkistaa onko salasana tyhjä
        if(empty(trim($_POST["password"]))) {
            $password_err = "Kirjoita salasana!";
        } else {
            $password = trim($_POST["password"]);
        }

        if(empty($username_err) && empty($password_err)) {
            // DB kysely
            $sql = "SELECT myyjaid, kayttajanimi, salasana FROM myyja WHERE kayttajanimi = ?";

            if($mysqli->set_charset("utf8")) {
                if($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param("s", $param_username);

                    $param_username = $username;

                    if($stmt->execute()) {
                        $stmt->store_result();

                        if($stmt->num_rows == 1) {
                            $stmt->bind_result($id, $username, $hashed_password);
                            if($stmt->fetch()) {
                                if(password_verify($password, $hashed_password)) {
                                    session_start();
                                
                                    $_SESSION["loggedin"] = true;
                                    $_SESSION["id"] = $id;
                                    $_SESSION["username"] = $username;

                                    if($username == "Admin") { // Vaihda käyttämään roolia yms. Hard coded nimi on huono idea.
                                        $_SESSION["flag"] = "admin";
                                    } else {
                                        $_SESSION["flag"] = "user";
                                    }

                                    header("location: ./../../index.php");
                                } else {
                                    $password_err = "Virheellinen käyttäjätunnus tai salasana!";
                                }
                            }    
                        } else {
                            $username_err = "Virheellinen käyttäjätunnus tai salasana!";
                        }
                    } else {
                        echo '<p class="text-danger text-center">Jokin meni vikaan! Yritä myöhemmin uudelleen!</p>';
                        header("location: ./../../error.php");
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
        <meta charset="utf-8">
        <title>Videovuokraamo - Kirjaudu sisään</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="./../../css/style.min.css">
    </head>
    <body>
        <div class="wrapper">
            <div class="page-header">
                <h2>Kirjaudu sisään</h2>
            </div>
            <hr>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <!-- Käyttäjänimi -->
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <label class="font-weight-bold">Käyttäjänimi:</label>
                    <input placeholder="Käyttäjänimesi" type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                    <small class="form-text text-danger">
                        <?php echo $username_err;?>
                    </small>
                </div>
                
                <!-- Salasna -->
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label class="font-weight-bold">Salasana:</label>
                    <input placeholder="Salasana" type="password" name="password" class="form-control">
                    <small class="form-text text-danger">
                        <?php echo $password_err;?>
                    </small>
                </div>

                <!-- Nappit -->
                <div class="form-group pt-2">
                    <input type="submit" class="btn btn-primary" value="Kirjaudu Sisään">
                    <a type="button" href="./../../index.php" class="btn btn-secondary">Peruuta</a>
                </div>
            </form>
        </div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="./../../js/init.min.js"></script>
    </body>
</html>