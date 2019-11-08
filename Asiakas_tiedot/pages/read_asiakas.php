<?php
    if(isset($_GET["asiakasid"]) && !empty(trim($_GET["asiakasid"]))) {
        require_once "./../../database.php";

        // Tarkistaa onko myyja kirjautunut sisälle
        session_start();

        if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
            header("location: ./../../Myyja_tiedot/login.php");
            exit();
        }

        $sql = "SELECT * FROM asiakas WHERE asiakasid = ?";

        if($mysqli->set_charset("utf8")) {
            if($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("i", $param_id);

                $param_id = trim($_GET["asiakasid"]);

                if($stmt->execute()) {
                    $result = $stmt->get_result();

                    if($result->num_rows == 1) {
                        $row = $result->fetch_array(MYSQLI_ASSOC);

                        $id = $row["asiakasid"];
                        $ssn = $row["henkilotunnus"];
                        $fname = $row["etunimi"];
                        $lname = $row["sukunimi"];
                        $email = $row["sposti"];
                        $phone = $row["puhelinnro"];
                        $road = $row["lahiosoite"];
                        $number = $row["postinumero"];
                        $city = $row["postitoimipaikka"];
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
        <title>Videovuokraamo - Tarkastele tietoja</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="./../../css/style.min.css">
    </head>
    <body>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header">
                            <h2>Tarkastele asiakkaan tietoja</h2>
                        </div>
                        <hr>
                        <!-- AsiakasID -->
                        <div class="form-group">
                            <label class="font-weight-bold">AsikasID:</label>
                            <input class="form-control" readonly value="<?php echo $row["asiakasid"]; ?>">
                        </div>

                        <!-- Henkilötunnus -->
                        <div class="form-group">
                            <label class="font-weight-bold">Henkilötunnus:</label>
                            <input class="form-control" readonly value="<?php echo $row["henkilotunnus"]; ?>">
                        </div>

                        <!-- Etunimi -->
                        <div class="form-group">
                            <label class="font-weight-bold">Etunimi:</label>
                            <input class="form-control" readonly value="<?php echo $row["etunimi"]; ?>">
                        </div>

                        <!-- Sukunimi -->
                        <div class="form-group">
                            <label class="font-weight-bold">Sukunimi:</label>
                            <input class="form-control" readonly value="<?php echo $row["sukunimi"]; ?>">
                        </div>

                        <!-- Sähköposti -->
                        <div class="form-group">
                            <label class="font-weight-bold">Sähköposti:</label>
                            <input class="form-control" readonly value="<?php echo $row["sposti"]; ?>">
                        </div>

                        <!-- Puhelinnumero -->
                        <div class="form-group">
                            <label class="font-weight-bold">Puhelinnumero:</label>
                            <input class="form-control" readonly value="<?php echo $row["puhelinnro"]; ?>">
                        </div>

                        <!-- Lähiosoite -->
                        <div class="form-group">
                            <label class="font-weight-bold">Lähiosoite:</label>
                            <input class="form-control" readonly value="<?php echo $row["lahiosoite"]; ?>">
                        </div>

                        <!-- Postinumero -->
                        <div class="form-group">
                            <label class="font-weight-bold">Postinumero:</label>
                            <input class="form-control" readonly value="<?php echo $row["postinumero"]; ?>">
                        </div>
                        
                        <!-- Postitoimipaikka -->
                        <div class="form-group">
                            <label class="font-weight-bold">Postitoimipaikka:</label>
                            <input class="form-control" readonly value="<?php echo $row["postitoimipaikka"]; ?>">
                        </div>
                        
                        <!-- Tarkistaa mistä käyttäjä on tullut että osaa palauttaa oikeaan paikkaan -->
                        <?php
                            $rentPath = './../../Vuokraus_tiedot/index.php';
                            $readButtonPath = './../index.php';
                            $serverName = $_SERVER["SERVER_NAME"]; // Mahdollistaa domainin käytön?

                            if(strstr($_SERVER["HTTP_REFERER"], "". $serverName ."/videovuokraamo/Vuokraus_tiedot/index.php") !== false) {
                                echo "<p><a href='". $rentPath ."' class='btn btn-primary'>Takaisin vuokraus sivulle</a></p>";
                            } else {
                                echo "<p><a href='". $readButtonPath ."' class='btn btn-primary'>Takasin asiakas sivulle</a></p>";
                            }
                        ?>
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