<!DOCTYPE html>
<html lang="fi">
    <head>
        <meta charset="utf-8">
        <title>Videovuokraamo - Vuokrausten hallinta</title>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="./../css/style.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    </head>
    <body> 
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="clearfix page-header">
                            <h2>Vuokrattujen elokuvien hallinta
                                <a href="./../index.php" class="btn btn-secondary float-right ml-2">Takaisin</a>
                                <a href="./pages/create_vuokraus.php" class="btn btn-success float-right">Luo uusi vuokraus</a>
                            </h2>
                        </div>
                        <hr>
                        <?php
                            // Tarkistaa onko myyja kirjautunut sisälle
                            session_start();

                            if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
                                header("location: ./../Myyja_tiedot/login/login.php");
                                exit();
                            }

                            require_once "./../database.php";

                            $sql = "SELECT concat(a.etunimi, ' ', a.sukunimi) asiakkaanNimi, vi.nimi elokuvanNimi, v.asiakasID asiakasID, vi.videoid videoID, v.vuokrauspvm, v.palautuspvm, v.kokonaishinta FROM asiakas a, elokuva vi, vuokraus v WHERE a.asiakasid = v.asiakasID AND vi.videoid = v.videoID AND v.palautuspvm ORDER BY palautuspvm ASC";

                            if($mysqli->set_charset("utf8")) {
                                if($result = $mysqli->query($sql)) {
                                    if($result->num_rows > 0) {
                                        echo "<table class='table table-bordered table-striped'>";
                                            echo "<thead>";
                                                echo "<tr>";
                                                    echo "<th>Asiakkaan nimi:</th>";
                                                    echo "<th>Elokuvan nimi:</th>";
                                                    echo "<th>Vuokrauspäivä:</th>";
                                                    echo "<th>Palautuspäivä:</th>";
                                                    echo "<th>Kokonaishinta (€):</th>";
                                                echo "</tr>";
                                            echo "</thead>";
                                            echo "<tbody>";
                                            while($row = $result->fetch_array()) {
                                                echo "<tr>";
                                                    echo "<td><a href='./../Asiakas_tiedot/pages/read_asiakas.php?asiakasid=". $row['asiakasID']. "'<span>" . $row['asiakkaanNimi'] . "</span></td></a>";
                                                    echo "<td><a href='./../Video_tiedot/pages/read_video.php?videoid=". $row['videoID']. "'<span>" . $row['elokuvanNimi'] . "</span></td></a>";
                                                    echo "<td>" . $row["vuokrauspvm"] . "</td>";
                                                    echo "<td>" . $row["palautuspvm"] . "</td>";
                                                    echo "<td>" . $row["kokonaishinta"] . "</td>";
                                                echo "</tr>";
                                            }
                                            echo "</tbody>";
                                        echo "</table>";
                                        $result->free();
                                    } else {
                                        echo "Yhtään dataa ei löydetty!";
                                    }
                                } else {
                                    echo "Error: Could not able to exec $sql. ".$mysqli->error;
                                }
                            }
                            $mysqli->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="./../js/init.min.js"></script>
    </body>
</html>