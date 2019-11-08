<?php

    // Tarkistaa onko käyttäjä admin
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["flag"] !== "admin") {
        header("location: ./../login/login.php");
        exit();
    }
    
    require_once "./../../database.php";
    require_once "./../../Asiakas_tiedot/utils/phone.php";

    $username = $fname = $lname = $road = $postnumber = $city = $phone = $email = $role = "";
    $username_err = $fname_err = $lname_err = $road_err = $postnumber_err = $city_err = $phone_err = $email_err = $role_err = "";

    if(isset($_POST["myyjaid"]) && !empty($_POST["myyjaid"])) {
        $id = $_POST["myyjaid"];

        // Käyttäjänimi
        $input_username = trim($_POST["username"]);

        if(empty($input_username)) {
            $username_err = "Kirjoita käyttäjänimi!";
        } else {
            $username = $input_username;
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

        // Puhelinnumero
        $input_phone = trim($_POST["phone"]);

        if(empty($input_phone)) {
            $phone_err = "Kirjoita puhelinnumero!";
        } elseif(checkPhoneNumber($input_phone) == false) {
            $phone_err = "Syötä puhelinnumero oikeassa muodossa!";
        } else {
            $phone = $input_phone;
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

        // Rooli
        $input_role = trim($_POST["role"]);

        if(empty($input_role)) {
            $role_err = "Kirjoita rooli!";
        } else {
            $role = $input_role;
        }

        if(empty($fname_err) && empty($lname_err) && empty($email_err) && empty($phone_err) && empty($phone_err) && empty($road_err) && empty($postnumber_err) && empty($city_err) && empty($role_err) && empty($username_err)) {
            $sql = "UPDATE myyja SET kayttajanimi=?, etunimi=?, sukunimi=?, lahiosoite=?, postinumero=?, postitoimipaikka=?, puhelinnro=?, sposti=?, rooli=? WHERE myyjaid = ".$id."";

            if($mysqli->set_charset("utf8")) {
                if($stmt = $mysqli->prepare($sql)) {
                    if($stmt = $mysqli->prepare($sql)) {
                        $stmt->bind_param("sssssssss", $param_username, $param_fname, $param_lname, $param_road, $param_postnumber, $param_city, $param_phone, $param_email, $param_role);

                        $param_username = $username;
                        $param_fname = $fname;
                        $param_lname = $lname;
                        $param_road  = $road;
                        $param_postnumber = $postnumber;
                        $param_city  = $city;
                        $param_phone = $phone;
                        $param_email = $email;
                        $param_role  = $role;

                        if($stmt->execute()) {
                            header("location: ./../control_panel.php");
                            exit();
                        } else {
                            echo '<p class="text-danger text-center">Jokin meni vikaan! Yritä myöhemmin uudelleen!</p>';
                        }
                    }
                }
                $stmt->close();
            }
            $mysqli->close();
        }
    } else {
        if(isset($_GET["myyjaid"]) && !empty(trim($_GET["myyjaid"]))) {
            $id = trim($_GET["myyjaid"]);

            $sql = "SELECT * FROM myyja WHERE myyjaid = ?";

            if($mysqli->set_charset("utf8")) {
                if($stmt = $mysqli->prepare($sql)) {
                    $stmt -> bind_param("i", $param_id);

                    $param_id = $id;

                    if($stmt->execute()) {
                        $result = $stmt->get_result();

                        if($result->num_rows == 1) {
                            $row = $result->fetch_array(MYSQLI_ASSOC);

                            $username = $row["kayttajanimi"];
                            $fname = $row["etunimi"];
                            $lname = $row["sukunimi"];
                            $road  = $row["lahiosoite"];
                            $postnumber = $row["postinumero"];
                            $city  = $row["postitoimipaikka"];
                            $phone = $row["puhelinnro"];
                            $email = $row["sposti"];
                            $role  = $row["rooli"];
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
        <title>Videovuokraamo - Päivitä myyjän tietoja</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="./../../css/style.min.css">
    </head>
    <body>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header">
                            <h2>Myyjän tietojen päivitys</h2>
                        </div>
                        <hr>
                        <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <!-- Myyjän id -->
                                <div class="form-group">
                                    <label class="font-weight-bold">Myyjän ID:</label>
                                    <input placeholder="Kirjoita myyjän id" type="text" name="id" class="form-control" value="<?php echo $id; ?>" readonly>
                                </div>

                                <!-- Käyttäjätunnus -->
                                <div class="form-group">
                                    <label class="font-weight-bold">Käyttäjätunnus:</label>
                                    <input placeholder="Kirjoita myyjän käyttäjätunnus" type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                                </div>

                                <!-- Etunimi -->
                                <div class="form-group <?php echo (!empty($fname_err)) ? 'has-error' : ''; ?>">
                                    <label class="font-weight-bold">Etunimi:</label>
                                    <input placeholder="Kirjoita myyjän etunimi" type="text" name="fname" class="form-control" value="<?php echo $fname; ?>">
                                    <small class="form-text text-danger">
                                        <?php echo $fname_err;?>
                                    </small>
                                </div>

                                <!-- Sukunimi -->
                                <div class="form-group <?php echo (!empty($lname_err)) ? 'has-error' : ''; ?>">
                                    <label class="font-weight-bold">Sukunimi:</label>
                                    <input placeholder="Kirjoita myyjän sukunimi" type="text" name="lname" class="form-control" value="<?php echo $lname; ?>">
                                    <small class="form-text text-danger">
                                        <?php echo $lname_err;?>
                                    </small>
                                </div>

                                <!-- Lähiosoite -->
                                <div class="form-group <?php echo (!empty($road_err)) ? 'has-error' : ''; ?>">
                                    <label class="font-weight-bold">Lähiosoite:</label>
                                    <input placeholder="Kirjoita myyjän lähiosoite" type="text" name="road" class="form-control" value="<?php echo $road; ?>">
                                    <small class="form-text text-danger">
                                        <?php echo $road_err;?>
                                    </small>
                                </div>

                                <!-- Postinumero -->
                                <div class="form-group <?php echo (!empty($postnumber_err)) ? 'has-error' : ''; ?>">
                                    <label class="font-weight-bold">Postinumero:</label>
                                    <input placeholder="Kirjoita myyjän postinumero" type="text" name="postnumber" class="form-control" value="<?php echo $postnumber; ?>">
                                    <small class="form-text text-danger">
                                        <?php echo $postnumber_err;?>
                                    </small>
                                </div>

                                <!-- Postitoimipaika -->
                                <div class="form-group <?php echo (!empty($city_err)) ? 'has-error' : ''; ?>">
                                    <label class="font-weight-bold">Postitoimipaika:</label>
                                    <input placeholder="Kirjoita myyjän postitoimipaika" type="text" name="city" class="form-control" value="<?php echo $city; ?>">
                                    <small class="form-text text-danger">
                                        <?php echo $city_err;?>
                                    </small>
                                </div>

                                <!-- Puhelinnumero -->
                                <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
                                    <label class="font-weight-bold">Puhelinnumero:</label>
                                    <input placeholder="Kirjoita myyjän puhelinnumero" type="text" name="phone" class="form-control" value="<?php echo $phone; ?>">
                                    <small class="form-text text-danger">
                                        <?php echo $phone_err;?>
                                    </small>
                                </div>

                                <!-- Sähköposti -->
                                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                                    <label class="font-weight-bold">Sähköposti:</label>
                                    <input placeholder="Kirjoita myyjän sähköposti" type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                                    <small class="form-text text-danger">
                                        <?php echo $email_err;?>
                                    </small>
                                </div>

                                <!-- Rooli -->
                                <div class="form-group <?php echo (!empty($role_err)) ? 'has-error' : ''; ?>">
                                    <label class="font-weight-bold">Rooli:</label>
                                    <input placeholder="Kirjoita myyjän rooli" type="text" name="role" class="form-control" value="<?php echo $role; ?>">
                                    <small class="form-text text-danger">
                                        <?php echo $role_err;?>
                                    </small>
                                </div>

                                <input type="hidden" name="myyjaid" value="<?php echo $id; ?>"/>
                                <input type="submit" class="btn btn-primary" value="Tallenna">
                                <a href="./../control_panel.php" class="btn btn-secondary">Peruuta</a>
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