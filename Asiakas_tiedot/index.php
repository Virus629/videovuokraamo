<!DOCTYPE html>
<html lang="fi">
    <head>
        <meta charset="utf-8">
        <title>Videovuokraamo - Asiakastiedot</title>

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
                            <h2>Asiakastiedot
                                <a href="./../index.php" class="btn btn-secondary float-right ml-2">Takaisin</a>
                                <a href="./pages/create_asiakas.php" class="btn btn-success float-right">Kirjaa uusi asiakas</a>
                            </h2>
                        </div>
                        <hr>
                        <?php
                            // Tarkistaa onko myyja kirjautunut sisälle
                            session_start();

                            if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
                                header("location: ../Myyja_tiedot/login.php");
                                exit();
                            }

                            require_once "./../database.php";

                            $sql = "SELECT * FROM asiakas";

                            if($mysqli->set_charset("utf8")) {
                                if($result = $mysqli->query($sql)) {
                                    if($result->num_rows > 0) {
                                        echo "<table class='table table-bordered table-striped'>";
                                            echo "<thead>";
                                                echo "<tr>";
                                                    echo "<th>Etunimi:</th>";
                                                    echo "<th>Sukunimi:</th>";
                                                    echo "<th>Sähköposti:</th>";
                                                    echo "<th>Toiminnot:</th>";
                                                echo "</tr>";
                                            echo "</thead>";
                                            echo "<tbody>";
                                            while($row = $result->fetch_array()) {
                                                echo "<tr>";
                                                    echo "<td>" . $row['etunimi'] . "</td>";
                                                    echo "<td>" . $row['sukunimi'] . "</td>";
                                                    echo "<td>" . $row['sposti'] . "</td>";
                                                    echo "<td>";
                                                        echo "<a class='mr-2' href='./pages/read_asiakas.php?asiakasid=". $row['asiakasid'] ."' title='Katsele tietoja' data-toggle='tooltip'><span class='far fa-eye'></span></a>";
                                                        echo "<a class='mr-2' href='./pages/update_asiakas.php?asiakasid=". $row['asiakasid'] ."' title='Päivitä tietoja' data-toggle='tooltip'><span class='fas fa-user-edit'></span></a>";
                                                        echo "<a class='mr-2' href='./pages/delete_asiakas.php?asiakasid=". $row['asiakasid'] ."' title='Poista tiedot' data-toggle='tooltip'><span class='far fa-trash-alt'></span></a>";
                                                    echo "</td>";
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