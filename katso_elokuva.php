<?php
    if(isset($_GET["videoid"]) && !empty(trim($_GET["videoid"]))) {
        require_once "./database.php";

        $sql = "SELECT * FROM elokuva WHERE videoid = ?";

        if($mysqli->set_charset("utf8")) {
            if($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("i", $param_id);

                $param_id = trim($_GET["videoid"]);

                if($stmt->execute()) {
                    $result = $stmt->get_result();

                    if($result->num_rows == 1) {
                        $row = $result->fetch_array(MYSQLI_ASSOC);

                        $name = $row["nimi"];
                        $genre = $row["genre"];
                        $lenght = $row["kesto"];
                        $age_limit = $row["ikaraja"];
                        $production_year = $row["tuotantovuosi"];

                    } else {
                        header("location: ./error.php");
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
        header("location: ./error.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="fi">
    <head>
        <meta charset="utf-8">
        <title>Videovuokraamo - Elokuvan tiedot</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="./css/style.min.css">
    </head>
    <body>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header">
                            <h2><?php echo $row["nimi"]; ?></h2>
                        </div>
                        <hr>
                        <div><p style="float: left;"><img src="<?php echo $row["kuva"]; ?>" height="300px" width="220px" style="margin-right: 25px;"></p>
                            <h3>Tiedot:</h3>
                            <div class="info" style="margin-left: 50px;">
                                <p>Nimi: <?php echo $row["nimi"]; ?></p>
                                <p>Genre: <?php echo $row["genre"]; ?></p>
                                <p>Kesto (min): <?php echo $row["kesto"]; ?></p>
                                <p>Ikäraja: <?php echo $row["ikaraja"]; ?></p>
                                <p>Julkaisupäivä: <?php echo $row["julkaisupaiva"]; ?></p>
                                <p>Näyttelijät: <?php echo $row["nayttelijat"]; ?></p>
                                <p>Ohjaaja: <?php echo $row["ohjaaja"]; ?></p>
                            </div>
                        </div>
                        <div style="clear: left;">
                        <hr>
                            <h3>Synopsis:</h3>
                            <p><?php echo $row["kuvaus"]; ?></p>
                        </div>
                        <hr>
                        <a href="./index.php" class="btn btn-secondary">Etusivulle</a>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="./js/init.min.js"></script>
    </body>
</html>