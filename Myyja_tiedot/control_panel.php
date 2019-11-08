<?php
    // Tarkistaa onko käyttäjä admin
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["flag"] !== "admin") {
        header("location: ./login/login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="fi">
    <head>
        <meta charset="UTF-8">
        <title>Videovuokraamo - Hallintapaneeli</title>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="./../css/style.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    </head>
    <body>
        <div class="wrapper">
            <div class="text-left page-header">
                <h2 class="text-left">Tervetuloa, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b> hallintapaneeliin
                    <a href="./management/create.php" class="btn btn-success float-right">Luo uusi käyttäjä</a>
                </h2>
            </div>
            <hr>
            <?php
                require_once "./../database.php";

                $sql = "SELECT * FROM myyja";

                if($mysqli->set_charset("utf8")) {
                    if($result = $mysqli->query($sql)) {
                        if($result->num_rows > 0) {
                            echo '<table class="table table-bordered table-striped">';
                                echo '<thead>';
                                    echo '<tr>';
                                        echo '<th>Käyttäjänimi:</th>';
                                        echo '<th>Etunimi:</th>';
                                        echo '<th>Sukunimi:</th>';
                                        echo '<th>Rooli:</th>';
                                        echo '<th>Toiminnot:</th>';
                                    echo '</tr>';
                                echo '</thead>';
                                echo '<tbody>';
                                while($row = $result->fetch_array()) {
                                    echo '<tr>';
                                        echo '<td>' . $row['kayttajanimi'] . '</td>';
                                        echo '<td>' . $row['etunimi'] . '</td>';
                                        echo '<td>' . $row['sukunimi'] . '</td>';
                                        echo '<td>' . $row['rooli'] . '</td>';

                                        echo '<td>';
                                            echo "<a class='mr-2' href='./management/read.php?myyjaid=". $row['myyjaid'] ."' title='Katsele tietoja' data-toggle='tooltip'><span class='far fa-eye'></span></a>";
                                            echo "<a class='mr-2' href='./management/update.php?myyjaid=". $row['myyjaid'] ."' title='Päivitä tietoja' data-toggle='tooltip'><span class='fas fa-user-edit'></span></a>";
                                            echo "<a class='mr-2' href='./management/delete.php?myyjaid=". $row['myyjaid'] ."' title='Poista tiedot' data-toggle='tooltip'><span class='far fa-trash-alt'></span></a>";
                                        echo '</td>';
                                    echo '</tr>';
                                }
                                echo '</tbody>';
                            echo '</table>';
                            $result->free();
                        } else {
                            echo '<p class="text-danger text-center"> Yhtään tietoa ei löydetty! </p>';
                        }
                    } else {
                        echo "Error: Could not able to exec $sql. ".$mysqli->error;
                    }
                }
                $mysqli->close();
            ?>
            <footer class="container fixed-bottom">
                <div class="button-group text-center" role="handling">
                    <p> 
                        <a type="button" href="./management/password_reset.php" class="btn btn-warning">Vaihda salasanasi</a>
                        <a type="button" href="./login/logout.php" class="btn btn-danger">Kirjaudu ulos</a>
                        <a type="button" href="./../index.php" class="btn btn-secondary">Takaisin etusivulle</a>
                    </p>
                </div>
            </footer>
        </div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="./../js/init.min.js"></script>
    </body>
</html>
