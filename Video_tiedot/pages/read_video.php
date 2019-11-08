<?php
    // Tarkistaa onko myyja kirjautunut sisälle
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: ./../../Myyja_tiedot/login.php");
        exit();
    }

    if(isset($_GET["videoid"]) && !empty(trim($_GET["videoid"]))) {
        require_once "./../../database.php";

        $sql = "SELECT * FROM elokuva WHERE videoid = ?";

        if($mysqli->set_charset("utf8")) {
            if($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("i", $param_id);

                $param_id = trim($_GET["videoid"]);

                if($stmt->execute()) {
                    $result = $stmt->get_result();

                    if($result->num_rows == 1) {
                        $row = $result->fetch_array(MYSQLI_ASSOC);

                        $id = $row["videoid"];
                        $name = $row["nimi"];
                        $genre = $row["genre"];
                        $lenght = $row["kesto"];
                        $age_limit = $row["ikaraja"];
                        $production_year = $row["tuotantovuosi"];

                    } else {
                        header("location: ./../../error.php");
                        exit();
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
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
        <title>Videovuokraamo - Elokuvan tiedot</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="./../../css/style.min.css">
    </head>
    <body>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header">
                            <h2>Tarkastele tietoja</h2>
                        </div>
                        <hr>

                        <!-- Videoid -->
                        <div class="form-group">
                            <label class="font-weight-bold">Videoid:</label>
                            <input class="form-control" readonly value="<?php echo $row["videoid"]; ?>">
                        </div>

                        <!-- Nimi -->
                        <div class="form-group">
                            <label class="font-weight-bold">Nimi:</label>
                            <input class="form-control" readonly value="<?php echo $row["nimi"]; ?>">
                        </div>

                        <!-- Genre -->
                        <div class="form-group">
                            <label class="font-weight-bold">Genre:</label>
                            <input class="form-control" readonly value="<?php echo $row["genre"]; ?>">
                        </div>

                        <!-- Kesto -->
                        <div class="form-group">
                            <label class="font-weight-bold">Kesto (MIN):</label>
                            <input class="form-control" readonly value="<?php echo $row["kesto"]; ?>">
                        </div>

                        <!-- Ikäraja -->
                        <div class="form-group">
                            <label class="font-weight-bold">Ikäraja:</label>
                            <input class="form-control" readonly value="<?php echo $row["ikaraja"]; ?>">
                        </div>

                        <!-- Julkaisupäivä -->
                        <div class="form-group">
                            <label class="font-weight-bold">Julkaisupäivä:</label>
                            <input class="form-control" readonly value="<?php echo $row["julkaisupaiva"]; ?>">
                        </div>

                        <!-- Tuotantovuosi -->
                        <div class="form-group">
                            <label class="font-weight-bold">Tuotantovuosi:</label>
                            <input class="form-control" readonly value="<?php echo $row["tuotantovuosi"]; ?>">
                        </div>

                        <!-- Kuvaus -->
                        <div class="form-group">
                            <label class="font-weight-bold">Kuvaus:</label>
                            <textarea class="form-control" readonly><?php echo $row["kuvaus"]; ?></textarea>
                        </div>

                        <!-- Ohjaaja -->
                        <div class="form-group">
                            <label class="font-weight-bold">Ohjaaja:</label>
                            <input class="form-control" readonly value="<?php echo $row["ohjaaja"]; ?>">
                        </div>

                        <!-- Näyttelijät -->
                        <div class="form-group">
                            <label class="font-weight-bold">Näyttelijät:</label>
                            <input class="form-control" readonly value="<?php echo $row["nayttelijat"]; ?>">
                        </div>

                        <!-- Kuva -->
                        <div class="form-group">
                            <label class="font-weight-bold">Kuva:</label>
                            <input class="form-control" readonly value="<?php echo $row["kuva"]; ?>">
                        </div>

                        <!-- Tarkistaa mistä käyttäjä on tullut että osaa palauttaa oikeaan paikkaan -->
                        <?php
                            $rentPath = './../../Vuokraus_tiedot/index.php';
                            $readButtonPath = './../index.php';
                            $serverName = $_SERVER["SERVER_NAME"]; // Mahdollistaa domainin käytön?

                            if(strstr($_SERVER["HTTP_REFERER"], "". $serverName ."/videovuokraamo/Vuokraus_tiedot/index.php") !== false) {
                                echo "<p><a href='". $rentPath ."' class='btn btn-primary'>Takaisin vuokraus sivulle</a></p>";
                            } else {
                                echo "<p><a href='". $readButtonPath ."' class='btn btn-primary'>Takasin hallintasivulle</a></p>";
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