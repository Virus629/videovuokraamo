<?php

    // Tarkistaa onko myyja kirjautunut sisälle
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: ./../../Myyja_tiedot/login.php");
        exit();
    }
    
    require_once "./../../database.php";
    require_once "./../utils/phone.php";

    $ssn = $fname = $lname = $email = $phone = $road = $postnumber = $city = "";
    $ssn_err = $fname_err = $lname_err = $email_err = $phone_err = $road_err = $postnumber_err = $city_err = "";

    if(isset($_POST["asiakasid"]) && !empty($_POST["asiakasid"])) {
        $id = $_POST["asiakasid"];

        $input_ssn = trim($_POST["ssn"]);

        if(empty($input_ssn)) {
            $ssn_err = "Kirjoita henkilötunnus!";
        } else {
            $ssn = $input_ssn;
        }

        // Etunimi
        $input_fname = trim($_POST["fname"]);

        if(empty($input_fname)) {
            $fname_err = "Kirjoita etunimi!";
        } elseif(!filter_var($input_fname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-öA-Ö\s]+$/")))) {
            $fname_err = "Kirjoita nimi oikein!";
        } else {
            $fname = $input_fname;
        }

        // Sukunimi
        $input_lname = trim($_POST["lname"]);

        if(empty($input_lname)) {
            $lname_err = "Kirjoita sukunimi!";
        } elseif(!filter_var($input_lname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-öA-Ö\s]+$/")))) {
            $lname_err = "Kirjoita nimi oikein!";
        } else {
            $lname = $input_lname;
        }

        // Sposti
        $input_email = trim($_POST["email"]);

        if(empty($input_email)) {
            $email_err = "Kirjoita sähköposti!";
        } elseif(!filter_var($input_email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Kirjoita sähköposti oikein";
        } else {
            $email = $input_email;
        }

        // Puhelin numero
        $input_phone = trim($_POST["phone"]);

        if(empty($input_phone)) {
            $phone_err = "Kirjoita puhelinnumero!";
        } elseif(checkPhoneNumber($input_phone) == false) {
            $phone_err = "Syötä puhelinnumero oikeassa muodossa!";
        } else {
            $phone = $input_phone;
        }

        // Lähiosoite
        $input_road = trim($_POST["road"]);

        if(empty($input_road)) {
            $road_err = "Kirjoita katuosoite!";
        } else {
            $road = $input_road;
        }

        // Postinumero
        $input_postnumber = trim($_POST["postnumber"]);

        if(empty($input_postnumber)) {
            $postnumber_err = "Kirjoita postinumero!";
        } else {
            $postnumber = $input_postnumber;
        }

        // Postitoimipaikka
        $input_city = trim($_POST["city"]);

        if(empty($input_city)) {
            $city_err = "Kirjoita postitoimipaikka!";
        } else {
            $city = $input_city;
        }

        if(empty($ssn_err) && empty($fname_err) && empty($lname_err) && empty($email_err) && empty($phone_err) && empty($phone_err) && empty($road_err) && empty($postnumber_err) && empty($city_err)) {
            $sql = "UPDATE asiakas SET henkilotunnus=?, etunimi=?, sukunimi=?, sposti=?, puhelinnro=?, lahiosoite=?, postinumero=?, postitoimipaikka=? WHERE asiakasid = ".$id."";

            if($mysqli->set_charset("utf8")) {
                if($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param("ssssssss", $param_ssn, $param_fname, $param_lname, $param_email, $param_phone, $param_road, $param_postnumber, $param_city);

                    $param_ssn   = $ssn;
                    $param_fname = $fname;
                    $param_lname = $lname;
                    $param_email = $email;
                    $param_phone = $phone;
                    $param_road  = $road;
                    $param_postnumber = $postnumber;
                    $param_city  = $city;

                    if($stmt->execute()) {
                        header("location: ./../index.php");
                        exit();
                    } else {
                        echo '<p class="text-danger text-center">Jokin meni vikaan! Yritä myöhemmin uudelleen!</p>';
                    }
                }
                $stmt->close();
            }
            $mysqli->close();
        }
    } else {
        if(isset($_GET["asiakasid"]) && !empty(trim($_GET["asiakasid"]))) {
            $id = trim($_GET["asiakasid"]);

            $sql = "SELECT * FROM asiakas WHERE asiakasid = ?";

            if($mysqli->set_charset("utf8")) {
                if($stmt = $mysqli->prepare($sql)) {
                    $stmt -> bind_param("i", $param_id);

                    $param_id = $id;

                    if($stmt->execute()) {
                        $result = $stmt->get_result();

                        if($result->num_rows == 1) {
                            $row = $result->fetch_array(MYSQLI_ASSOC);

                            $ssn   = $row["henkilotunnus"];
                            $fname = $row["etunimi"];
                            $lname = $row["sukunimi"];
                            $email = $row["sposti"];
                            $phone = $row["puhelinnro"];
                            $road  = $row["lahiosoite"];
                            $postnumber = $row["postinumero"];
                            $city  = $row["postitoimipaikka"];
                        } else {
                            header("location: ./../../error.php");
                            exit();
                        }
                    }
                } else {
                    echo '<p class="text-danger text-center">Jokin meni vikaan! Yritä myöhemmin uudelleen!</p>';
                }
                $stmt->close();
            }
            $mysqli->close();
        } else {
            header("location: ./../../error.php");
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="fi">
    <head>
        <meta charset="utf-8">
        <title>Videovuokraamo - Päivitä tietoja</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="./../../css/style.min.css">
    </head>
    <body>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header">
                            <h2>Asiakkaan tietojen päivitys</h2>
                        </div>
                        <hr>
                        <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <!-- Asiakkaan id -->
                                <div class="form-group <?php echo (!empty($id_err)) ? 'has-error' : ''; ?>">
                                    <label class="font-weight-bold">Asiakkaan ID:</label>
                                    <input placeholder="Kirjoita asiakkaan id" type="text" name="id" class="form-control" value="<?php echo $id; ?>" readonly>
                                </div>
                                <!-- Henkilötunnus -->
                                <div class="form-group <?php echo (!empty($ssn_err)) ? 'has-error' : ''; ?>">
                                    <label class="font-weight-bold">Henkilötunnus:</label>
                                    <input placeholder="Kirjoita asiakkaan henkilötunnus" type="text" name="ssn" class="form-control" value="<?php echo $ssn; ?>" readonly>
                                </div>
                                <!-- Etunimi -->
                                <div class="form-group <?php echo (!empty($fname_err)) ? 'has-error' : ''; ?>">
                                    <label class="font-weight-bold">Etunimi:</label>
                                    <input placeholder="Kirjoita asiakkaan etunimi" type="text" name="fname" class="form-control" value="<?php echo $fname; ?>">
                                    <small class="form-text text-danger">
                                        <?php echo $fname_err;?>
                                    </small>
                                </div>
                                <!-- Sukunimi -->
                                <div class="form-group <?php echo (!empty($lname_err)) ? 'has-error' : ''; ?>">
                                    <label class="font-weight-bold">Sukunimi:</label>
                                    <input placeholder="Kirjoita asiakkaan sukunimi" type="text" name="lname" class="form-control" value="<?php echo $lname; ?>">
                                    <small class="form-text text-danger">
                                        <?php echo $lname_err;?>
                                    </small>
                                </div>
                                <!-- Sposti -->
                                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                                    <label class="font-weight-bold">Sähköposti:</label>
                                    <input placeholder="Kirjoita asiakkaan sähköposti" type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                                    <small class="form-text text-danger">
                                        <?php echo $email_err;?>
                                    </small>
                                </div>
                                <!-- Puhelinnumero -->
                                <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
                                    <label class="font-weight-bold">Puhelinnumero:</label>
                                    <input placeholder="Kirjoita asiakkaan puhelinnumero" type="text" name="phone" class="form-control" value="<?php echo $phone; ?>">
                                    <small class="form-text text-danger">
                                        <?php echo $phone_err;?>
                                    </small>
                                </div>
                                <!-- Lähiosoite -->
                                <div class="form-group <?php echo (!empty($road_err)) ? 'has-error' : ''; ?>">
                                    <label class="font-weight-bold">Lähiosoite:</label>
                                    <input placeholder="Kirjoita asiakkaan lähiosoite" type="text" name="road" class="form-control" value="<?php echo $road; ?>">
                                    <small class="form-text text-danger">
                                        <?php echo $road_err;?>
                                    </small>
                                </div>
                                <!-- Postinumero -->
                                <div class="form-group <?php echo (!empty($postnumber_err)) ? 'has-error' : ''; ?>">
                                    <label class="font-weight-bold">Postinumero:</label>
                                    <input placeholder="Kirjoita asiakkaan postinumero" type="text" name="postnumber" class="form-control" value="<?php echo $postnumber; ?>">
                                    <small class="form-text text-danger">
                                        <?php echo $postnumber_err;?>
                                    </small>
                                </div>
                                <!-- Postitoimipaikka -->
                                <div class="form-group <?php echo (!empty($city_err)) ? 'has-error' : ''; ?>">
                                    <label class="font-weight-bold">Postitoimipaikka:</label>
                                    <input placeholder="Kirjoita asiakkaan postitoimipaikka" type="text" name="city" class="form-control" value="<?php echo $city; ?>">
                                    <small class="form-text text-danger">
                                        <?php echo $city_err;?>
                                    </small>
                                </div>

                                <input type="hidden" name="asiakasid" value="<?php echo $id; ?>"/>
                                <input type="submit" class="btn btn-primary" value="Tallenna">
                                <a href="./../index.php" class="btn btn-secondary">Peruuta</a>
                            </form>
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