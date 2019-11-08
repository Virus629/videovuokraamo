<?php
    require_once "./../../database.php";

                                
    // Tarkistaa onko myyja kirjautunut sisälle
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: ./../../Myyja_tiedot/login.php");
        exit();
    }

    $id = $name = $genre = $lenght = $age_limit = $release_date = $production_year = $info = $director = $cast = $picture = "";
    $id_err = $name_err = $genre_err = $lenght_err = $age_limit_err = $release_date_err = $production_year_err = $info_err = $director_err = $cast_err = $picture_err = "";
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // Elokuvan nimi
        $input_name = trim($_POST["name"]);

        if(empty($input_name)) {
            $name_err = "Kirjoita nimi!";
        } else {
            $name = $input_name;
        }

        // Genre
        $input_genre = trim($_POST["genre"]);

        if(empty($input_genre)) {
            $genre_err = "Kirjoita genre!";
        } else {
            $genre = $input_genre;
        }

        // Pituus
        $input_lenght = trim($_POST["lenght"]);

        if(empty($input_lenght)) {
            $lenght_err = "Kirjoita pituus!";
        } else {
            $lenght = $input_lenght;
        }

        // Ikäräjä
        $input_agelimit = trim($_POST["age_limit"]);

        if(empty($input_agelimit)) {
            $age_limit_err = "Kirjoita ikäraja!";
        } else {
            $age_limit = $input_agelimit;
        }

        // Julkaisupäivä
        $input_releasedate = trim($_POST["release_date"]);

        if(empty($input_releasedate)) {
            $release_date_err = "Kirjoita julkaisu päivä!";
        } else {
            $release_date = $input_releasedate;
        }

        // Tuotantovuosi
        $input_productionyear = trim($_POST["production_year"]);

        if(empty($input_productionyear)) {
            $production_year_err = "Kirjoita tuotanto vuosi!";
        } else {
            $production_year = $input_productionyear;
        }

        // Kuvaus
        $input_info = trim($_POST["info"]);

        if(empty($input_info)) {
            $info_err = "Kirjoita jokin kuvaus!";
        } else {
            $info = $input_info;
        }

        // Ohjaaja
        $input_director = trim($_POST["director"]);

        if(empty($input_director)) {
            $director_err = "Kirjoita ohjaajan nimi!";
        } else {
            $director = $input_director;
        }

        // Näyttelijät
         $input_cast = trim($_POST["cast"]);

        if(empty($input_cast)) {
            $cast_err = "Kirjoita pää näyttelijöiden nimet!";
        } else {
            $cast = $input_cast;
        }

        // Kuva
        $input_picture = trim($_POST["picture"]);

        if(empty($input_picture)) {
            $picture_err = "Kirjoita kuvan url esim: https://imgur.com/kuva1";
        } else {
            $picture = $input_picture;
        }


        // Tarkista data
        if(empty($name_err) && empty($genre_err) && empty($lenght_err) && empty($age_limit_err) && empty($release_date_err) && empty($production_year_err) && empty($info_err) && empty($director_err) && empty($cast_err) && empty($picture_err)) {
            $sql = "INSERT INTO elokuva (nimi, genre, kesto, ikaraja, julkaisupaiva, tuotantovuosi, kuvaus, ohjaaja, nayttelijat, kuva) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            if($mysqli->set_charset("utf8")) {
                if($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param("ssssssssss", $param_name, $param_genre, $param_lenght, $param_agelimit, $param_releasedata, $param_productionyear, $param_info, $param_director, $param_cast, $param_picture);

                    $param_name = $name;
                    $param_genre = $genre;
                    $param_lenght = $lenght;
                    $param_agelimit = $age_limit;
                    $param_releasedata = $release_date;
                    $param_productionyear = $production_year;
                    $param_info = $info;
                    $param_director = $director;
                    $param_cast = $cast;
                    $param_picture = $picture;

                    if($stmt->execute()) {
                        header("location: ./../index.php");
                        exit();
                    } else {
                        echo '<p class="text-danger text-center">Jokin meni vikaan! Yritä myöhemmin uudelleen!</p>';
                    }
                }
                $stmt->close();
            }
        }
        $mysqli->close();
    }
?>

<!DOCTYPE html>
<html lang="fi">
    <head>
        <title>Videovuokraamo - Luo video tieto</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="./../../css/style.min.css">
    </head>
    <body>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header">
                            <h2>Luo video tieto</h2>
                        </div>
                        <hr>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                            <!-- Nimi -->
                            <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                                <label class="font-weight-bold">Nimi:</label>
                                <input placeholder="Kirjoita nimi" type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                                <small class="form-text text-danger">
                                    <?php echo $name_err;?>
                                </small>
                            </div>

                            <!-- Genre -->
                            <div class="form-group <?php echo (!empty($genre_err)) ? 'has-error' : ''; ?>">
                                <label class="font-weight-bold">Genre:</label>
                                <input placeholder="Kirjoita genre" type="text" name="genre" class="form-control" value="<?php echo $genre; ?>">
                                <small class="form-text text-danger">
                                    <?php echo $genre_err;?>
                                </small>
                            </div>

                            <!-- Kesto -->
                            <div class="form-group <?php echo (!empty($lenght_err)) ? 'has-error' : ''; ?>">
                                <label class="font-weight-bold">Kesto (MIN):</label>
                                <input placeholder="Kirjoita pituus" type="text" name="lenght" class="form-control" value="<?php echo $lenght; ?>">
                                <small class="form-text text-danger">
                                    <?php echo $lenght_err;?>
                                </small>
                            </div>

                            <!-- Ikäraja -->
                            <div class="form-group <?php echo (!empty($age_limit_err)) ? 'has-error' : ''; ?>">
                                <label class="font-weight-bold">Ikäraja:</label>
                                <input placeholder="Kirjoita ikäraja" type="text" name="age_limit" class="form-control" value="<?php echo $age_limit; ?>">
                                <small class="form-text text-danger">
                                    <?php echo $age_limit_err;?>
                                </small>
                            </div>

                            <!-- Julkaisupäivä -->
                            <div class="form-group <?php echo (!empty($release_date_err)) ? 'has-error' : ''; ?>">
                                <label class="font-weight-bold">Julkaisupäivä:</label>
                                <input placeholder="Kirjoita julkaisupäivä" type="date" name="release_date" class="form-control" value="<?php echo $release_date; ?>">
                                <small class="form-text text-danger">
                                    <?php echo $release_date_err;?>
                                </small>
                            </div>

                            <!-- Tuotantovuosi -->
                            <div class="form-group <?php echo (!empty($production_year_err)) ? 'has-error' : ''; ?>">
                                <label class="font-weight-bold">Tuotantovuosi:</label>
                                <input placeholder="Kirjoita tuotantovuosi" type="date" name="production_year" class="form-control" value="<?php echo $production_year; ?>">
                                <small class="form-text text-danger">
                                    <?php echo $production_year_err;?>
                                </small>
                            </div>

                            <!-- Kuvaus --> <!-- Tee isompi alue -->
                            <div class="form-group <?php echo (!empty($info_err)) ? 'has-error' : ''; ?>">
                                <label class="font-weight-bold">Kuvaus:</label>
                                <textarea placeholder="Kirjoita kuvaus" type="text" rows="3" name="info" class="form-control"><?php echo $info; ?></textarea>
                                <small class="form-text text-danger">
                                    <?php echo $info_err;?>
                                </small>
                            </div>

                            <!-- Ohjaaja -->
                            <div class="form-group <?php echo (!empty($director_err)) ? 'has-error' : ''; ?>">
                                <label class="font-weight-bold">Ohjaaja:</label>
                                <input placeholder="Kirjoita ohjaajan nimi" type="text" name="director" class="form-control" value="<?php echo $director; ?>">
                                <small class="form-text text-danger">
                                    <?php echo $director_err;?>
                                </small>
                            </div>

                            <!-- Näyttelijät -->
                            <div class="form-group <?php echo (!empty($cast_err)) ? 'has-error' : ''; ?>">
                                <label class="font-weight-bold">Näyttelijät:</label>
                                <input placeholder="Kirjoita pää näyttelijöiden nimet" type="text" name="cast" class="form-control" value="<?php echo $cast; ?>">
                                <small class="form-text text-danger">
                                    <?php echo $cast_err;?>
                                </small>
                            </div>

                            <!-- Kansikuva -->
                            <div class="form-group <?php echo (!empty($picture_err)) ? 'has-error' : ''; ?>">
                                <label class="font-weight-bold">Kansikuva:</label>
                                <input placeholder="Kirjoita kuvan url esim: https://imgur.com/kuva1" type="text" name="picture" class="form-control" value="<?php echo $picture; ?>">
                                <small class="form-text text-danger">
                                    <?php echo $picture_err;?>
                                </small>
                            </div>

                            <input type="submit" class="btn btn-primary" value="Tallenna">
                            <a href="./../index.php" class="btn btn-secondary">Peruuta</a>
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