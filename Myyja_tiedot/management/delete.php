<?php
    if(isset($_POST["myyjaid"]) && !empty($_POST["myyjaid"])) {
        require_once "./../../database.php";

        // Tarkistaa onko käyttäjä admin
        session_start();

        if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["flag"] !== "admin") {
            header("location: ./login/login.php");
            exit();
        }

        $sql = "DELETE FROM myyja WHERE myyjaid = ?";

        if($mysqli->set_charset("utf8")) {
            if($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("i", $param_id);

                $param_id = trim($_POST["myyjaid"]);

                if($stmt->execute()) {
                    header("location: ./../control_panel.php");
                    exit();
                } else {
                    echo '<p class="text-danger text-center">Jokin meni vikaan! Yritä myöhemmin uudelleen!</p>';
                }
            }
            $stmt->close();
        }
        $mysqli->close();

    } else {
        if(empty(trim($_GET["myyjaid"]))) {
            header("location: ./../../error.php");
            exit();
        }
    }

    if(isset($_GET["myyjaid"]) && !empty(trim($_GET["myyjaid"]))) {
        require_once "./../../database.php";

        $sql = "SELECT kayttajanimi FROM myyja WHERE myyjaid = ?";

        if($mysqli->set_charset("utf8")) {
            if($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("i", $param_id);

                $param_id = trim($_GET["myyjaid"]);

                if($stmt->execute()) {
                    $result = $stmt->get_result();

                    if($result->num_rows == 1) {
                        $row = $result->fetch_array(MYSQLI_ASSOC);

                        $name = $row["kayttajanimi"];

                    } else {
                        header("location: ./../../error.php");
                        exit();
                    }
                } else {
                    echo '<p class="text-danger text-center">Jokin meni vikaan! Yritä myöhemmin uudelleen!</p>';
                }
                // Close statement
                $stmt->close();
            }
        }
        // Close connection
        $mysqli->close();
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: ./../../error.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="fi">
    <head>
        <meta charset="utf-8">
        <title>Videovuokraamo - Poista myyjän tietoja</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="./../../css/style.min.css">
    </head>
    <body>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header">
                            <h2>Poista myyjän tiedot</h2>
                        </div>
                        <hr>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="text-center">
                            <div class="alert alert-danger text-left">
                                <input type="hidden" name="myyjaid" value="<?php echo trim($_GET["myyjaid"]); ?>"/>
                                <?php echo "<p>Haluatko varmasti poistaa myyjän <strong>{$name}</strong> tiedot?</p>" ?>

                                <input type="submit" value="Kyllä" class="btn btn-danger">
                                <a href="./../control_panel.php" class="btn btn-secondary">Peruuta</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="./../../js/init.min.js"></script>
    </body>
</html>