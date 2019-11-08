<?php
    if(isset($_POST["asiakasid"]) && !empty($_POST["asiakasid"])) {
        require_once "./../../database.php";

        // Tarkistaa onko myyja kirjautunut sisälle
        session_start();

        if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
            header("location: ./../../Myyja_tiedot/login.php");
            exit();
        }

        $sql = "DELETE FROM asiakas WHERE asiakasid = ?";

        if($mysqli->set_charset("utf8")) {
            if($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("i", $param_id);

                $param_id = trim($_POST["asiakasid"]);

                if($stmt->execute()) {
                    header("location: ./../index.php");
                    exit();
                } else {
                    echo '<p class="text-danger text-center">Jokin meni vikaan! Yritä myöhemmin uudelleen!</p>';
                }
            }
        }
        $stmt->close();
        $mysqli->close();
    } else {
        if(empty(trim($_GET["asiakasid"]))) {
            header("location: ./../../error.php");
            exit();
        }
    }

    if(isset($_GET["asiakasid"]) && !empty(trim($_GET["asiakasid"]))) {
        require_once "./../../database.php";

        $sql = "SELECT etunimi, sukunimi FROM asiakas WHERE asiakasid = ?";

        if($mysqli->set_charset("utf8")) {
            if($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("i", $param_id);

                $param_id = trim($_GET["asiakasid"]);

                if($stmt->execute()) {
                    $result = $stmt->get_result();

                    if($result->num_rows == 1) {
                        $row = $result->fetch_array(MYSQLI_ASSOC);

                        $fname = $row["etunimi"];
                        $lname = $row["sukunimi"];
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
        <title>Videovuokraamo - Poista tietoja</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="./../../css/style.min.css">
    </head>
    <body>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header">
                            <h2>Poista asiakkaan tiedot</h2>
                        </div>
                        <hr>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="text-center">
                            <div class="alert alert-danger text-left">
                                <input type="hidden" name="asiakasid" value="<?php echo trim($_GET["asiakasid"]); ?>"/>
                                <?php echo "<p>Haluatko varmasti poistaa asiakkaan <strong>{$fname} {$lname}</strong> tiedot?</p>" ?>

                                <input type="submit" value="Kyllä" class="btn btn-danger">
                                <a href="./../index.php" class="btn btn-secondary">Peruuta</a>
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