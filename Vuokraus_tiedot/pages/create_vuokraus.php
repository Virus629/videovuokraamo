<?php
    require_once "./../../database.php";

    // Tarkistaa onko myyja kirjautunut sisälle
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: ./../../Myyja_tiedot/login/login.php");
        exit();
    }

    $customerID = $rentDay = $returnDay = $fullPrice = $movie = "";
    $customerID_err = $rentDay_err = $returnDay_err = $fullPrice_err = $movie_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {

        // Asiakas ID
        $input_customer = trim($_POST["customerID"]);

        if(empty($input_customer)) {
            $customerID_err = "Valitse asiakas!";
        } else {
            $customerID = $input_customer;
        }

        // Vuokrapäivä
        $input_rentday = trim($_POST["rentDay"]);

        if(empty($input_rentday)) {
            $rentDay_err = "Valitse vuokrapäivä!";
        } else {
            $rentDay = $input_rentday;
        }

        // Palautuspäivä
        $input_returnday = trim($_POST["returnDay"]);

        if(empty($input_returnday)) {
            $returnDay_err = "Valitse palautuspäivä!";
        } else {
            $returnDay = $input_returnday;
        }

        // Kokonaishinta
        $input_fullprice = trim($_POST["fullPrice"]);

        if(empty($input_fullprice)) {
            $fullPrice_err = "Hinta ei voi olla tyhjä!";
        } else {
            $fullPrice = $input_fullprice;
        }

        // Elokuva
        $input_movie = trim($_POST["movie"]);

        if(empty($input_movie)) {
            $movie_err = "Elokuva ei voi olla tyhjä";
        } else {
            $movie = $input_movie;
        }

        if(empty($customerID_err) && empty($rentDay_err) && empty($returnDay_err) && empty($fullPrice_err) && empty($movie_err)) {
            // DEBUG: Ongelma ei ole koodissa enää vaan databasessa. Tee uusi vuokraus tables yms.
            $sql = "INSERT INTO vuokraus (asiakasID, videoID, vuokrauspvm, palautuspvm, kokonaishinta) VALUES (?, ?, ?, ?, ?)";

            if($mysqli->set_charset("utf8")) {
                if($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param("iissd", $param_customer, $param_movie, $param_rentday, $param_returnday, $param_fullprice);

                    $param_customer  = $customerID;
                    $param_movie     = $movie;
                    $param_rentday   = $rentDay;
                    $param_returnday = $returnDay;
                    $param_fullprice = $fullPrice;

                    if($stmt->execute()) {
                        header("location: ./../index.php");
                        exit();
                    } else {
                        echo '<p class="text-danger text-center"> Jokin meni vikaan! Tarkista, että kaikki tiedot ovat varmasti oikein! </p>';
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
        <title>Videovuokraamo - Luo vuokraus</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="./../../css/style.min.css">
    </head>
    <body>
    <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header">
                            <h2>Uusi vuokraustiedot</h2>
                        </div>
                        <hr>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <!-- Asiakas id -->
                            <div class="form-group <?php echo (!empty($customerID_err)) ? 'has-error' : ''; ?>">
                                <label class="font-weight-bold">Asiakas:</label>
                                <select class="form-control" name="customerID">
                                    <?php
                                        require_once "./../../database.php";
                                        $sql = "SELECT asiakasid, CONCAT(etunimi, ' ', sukunimi) kokonimi FROM asiakas ORDER BY sukunimi, etunimi DESC";
                                        if($mysqli->set_charset("utf8")) {
                                            if($result = $mysqli->query($sql)) {
                                                if($result->num_rows > 0) {
                                                    while($row = $result->fetch_array()) {
                                                        echo "<option value=".$row["asiakasid"].">".$row["kokonimi"]."</option>";
                                                    }
                                                    $result->free();
                                                } else {
                                                    echo "Yhtään käyttäjää ei löydetty!";
                                                }
                                            } else {
                                                echo "Error: Could not able to exec $sql. ".$mysqli->error;
                                            }
                                        }
                                    ?>
                                </select>
                                <small class="form-text text-danger">
                                    <?php echo $customerID_err;?>
                                </small>
                            </div>
                            <!-- Elokuva id -->
                            <div class="form-group <?php echo (!empty($movie_err)) ? 'has-error' : ''; ?>">
                                <label class="font-weight-bold">Elokuva:</label>
                                <select class="form-control" name="movie">
                                    <?php
                                        require_once "./../../database.php";
                                        $sql = "SELECT videoid, nimi FROM elokuva ORDER BY nimi";
                                        if($mysqli->set_charset("utf8")) {
                                            if($result = $mysqli->query($sql)) {
                                                if($result->num_rows > 0) {
                                                    while($row = $result->fetch_array()) {
                                                        echo "<option value=".$row["videoid"].">".$row["nimi"]."</option>";
                                                    }
                                                    $result->free();
                                                } else {
                                                    echo "Yhtään elokuvaa ei löydetty!";
                                                }
                                            } else {
                                                echo "Error: Could not able to exec $sql. ".$mysqli->error;
                                            }
                                        }
                                        $mysqli->close();
                                    ?>
                                </select>
                                <small class="form-text text-danger">
                                    <?php echo $movie_err;?>
                                </small>
                            </div>
                            <!-- Vuokrapäivä -->
                            <div class="form-group <?php echo (!empty($rentDay_err)) ? 'has-error' : ''; ?>">
                                <label class="font-weight-bold">Vuokrapäivä:</label>
                                <input placeholder="Kirjoita vuokrapäivä" type="date" name="rentDay" class="form-control" value="<?php echo $rentDay; ?>">
                                <small class="form-text text-danger">
                                    <?php echo $rentDay_err;?>
                                </small>
                            </div>
                            <!-- Palautuspäivä -->
                            <div class="form-group <?php echo (!empty($returnDay_err)) ? 'has-error' : ''; ?>">
                                <label class="font-weight-bold">Palautuspäivä:</label>
                                <input placeholder="Kirjoita palautuspäivä" type="date" name="returnDay" class="form-control" value="<?php echo $returnDay; ?>">
                                <small class="form-text text-danger">
                                    <?php echo $returnDay_err;?>
                                </small>
                            </div>
                            <!-- Kokonaishinta -->
                            <div class="form-group <?php echo (!empty($fullPrice_err)) ? 'has-error' : ''; ?>">
                                <label class="font-weight-bold">Kokonaishinta:</label>
                                <input placeholder="Kirjoita kokonaishinta" type="text" name="fullPrice" class="form-control" value="<?php echo $fullPrice; ?>">
                                <small class="form-text text-danger">
                                    <?php echo $fullPrice_err;?>
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