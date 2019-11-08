<!DOCTYPE html>
<html lang="fi">
    <head>
        <meta charset="utf-8">
        <title>Videovuokraamo - Tervetuloa</title>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="./css/style.min.css">

        <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <link rel="stylesheet" href="https://getbootstrap.com/docs/4.0/examples/carousel/carousel.css">
        
    </head>
    <body>
        <!-- Header -->
        <header>
            <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
                <a class="navbar-brand" href="index.php">Videovuokraamo</a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarCollapse">

                        <ul class="navbar-nav mr-auto">

                            <?php
                            // Näytetään käyttäjälle napit jos ollaan kirjauduttu sisään
                            session_start();

                            if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["flag"] === "user"){ // Jos käyttäjä on perus myyjä
                                echo '<li class="nav-item '; 
                                echo ($valikko==='asiakas')?'active':'';
                                echo ' ">
                                    <a class="nav-link" href="./Asiakas_tiedot/index.php">Asiakas <span class="sr-only">(current)</span></a>
                                </li>';

                                echo '<li class="nav-item '; 
                                echo ($valikko==='video')?'active':'';
                                echo ' ">
                                    <a class="nav-link" href="./Video_tiedot/index.php">Video</a>
                                </li>';

                                echo '<li class="nav-item '; 
                                echo ($valikko==='vuokraus')?'active':'';
                                echo ' ">
                                    <a class="nav-link" href="./Vuokraus_tiedot/pages/create_vuokraus.php">Vuokraus</a>
                                </li>';

                                echo '<li class="nav-item dropdown ';
                                echo ($valikko==='raportit')?'active':'';
                                echo ' ">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Raportit
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="./Vuokraus_tiedot/index.php">Vuokralla</a>

                                    </div>
                                </li>';

                                echo '<li class="nav-item dropdown ';
                                echo ($valikko=='resetoisalasana')?'active':'';
                                echo ' ">
                                <a class="nav-link" href="./Myyja_tiedot/management/password_reset.php">Vaihda Salasanasi</a>
                                </li>';
                            } else if (isset($_SESSION['flag']) && !empty($_SESSION['flag']) && $_SESSION['flag']=="admin") { // Jos käyttäjä on admin
                                echo '<li class="nav-item '; 
                                echo ($valikko==='asiakas')?'active':'';
                                echo ' ">
                                    <a class="nav-link" href="./Asiakas_tiedot/index.php">Asiakas <span class="sr-only">(current)</span></a>
                                </li>';
                                echo '<li class="nav-item '; 
                                echo ($valikko==='video')?'active':'';
                                echo ' ">
                                    <a class="nav-link" href="./Video_tiedot/index.php">Video</a>
                                </li>';
                                echo '<li class="nav-item '; 
                                echo ($valikko==='vuokraus')?'active':'';
                                echo ' ">
                                    <a class="nav-link" href="./Vuokraus_tiedot/pages/create_vuokraus.php">Vuokraus</a>
                                </li>';
                                echo '<li class="nav-item ';
                                echo ($valikko==='myyja')?'active':'';
                                echo ' ">
                                    <a class="nav-link" href="./Myyja_tiedot/control_panel.php">Myyjä</a>
                                </li>';
                                echo '<li class="nav-item dropdown ';
                                echo ($valikko==='raportit')?'active':'';
                                echo ' ">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Raportit
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="./Vuokraus_tiedot/index.php">Vuokralla</a>

                                    </div>
                                </li>';

                            }
                            ?>
                        </ul>

                    <form class="form-inline mt-2 mt-md-0">
                        <input class="form-control mr-sm-2" type="text" placeholder="Syötä hakusana" aria-label="Search">

                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Haku</button>
                    </form>

                    <?php
                    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                        echo '<a class="nav-link" href="./Myyja_tiedot/login/logout.php">Kirjaudu Ulos<span class="oi oi-account-logout"></span></a>';
                    } else {
                        echo '<a class="nav-link" href="./Myyja_tiedot/login/login.php">Kirjaudu Sisään<span class="oi oi-account-login"></span></a>';
                    }
                    ?>
                </div>
            </nav>
        </header>
        <?php
            if ($_SERVER["PHP_SELF"] != "/videovuokraamo/index.php" ){
                if ($_SERVER["PHP_SELF"] != "/videovuokraamo/Myyja_tiedot/login/login.php" ){
                    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
                        header("location: index.php");
                        exit();
                    }
                }
            }
        ?>
        <!-- Header -->

        <!-- Karuselli -->
        <main role="main">
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="first-slide" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="First slide">
                        <div class="container">
                            <div class="carousel-caption text-left">
                                <h1>Example headline.</h1>
                                <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                                <p><a class="btn btn-lg btn-primary" href="#" role="button">Sign up today</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img class="second-slide" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Second slide">
                        <div class="container">
                            <div class="carousel-caption">
                                <h1>Another example headline.</h1>
                                <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                                <p><a class="btn btn-lg btn-primary" href="#" role="button">Learn more</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img class="third-slide" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Third slide">
                        <div class="container">
                            <div class="carousel-caption text-right">
                                <h1>One more for good measure.</h1>
                                <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                                <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <!-- Karuselli -->

            <!-- Myynti -->
            <div class="container marketing">
                <div class="row">
                    <?php
                        include_once "database.php";

                        $sql = 'SELECT * FROM elokuva ORDER BY julkaisupaiva DESC LIMIT 3';

                        if($mysqli->set_charset("utf8")) {
                            foreach($mysqli->query($sql) as $row) {
                                echo '<div class="col-lg-4">';
                                echo '<img style="margin-bottom: 5px;" src="' .$row['kuva']. '" alt="' . $row['nimi'] . '" width="120" >';
                                echo '<p><strong>' . $row['nimi'] . '</strong></p>';
                                echo '<p>' . $row['julkaisupaiva'] . '</p>';
                                echo '<p><a class="btn btn-secondary" href="./katso_elokuva.php?videoid=' . $row['videoid'] . '" role="button">Katso Lisää</a></p>';
                                echo '</div>';
                            }
                        }
                        $mysqli->close();
                    ?>
                </div>
                <hr class="featurette-divider">

                    <div class="row featurette">
                        <div class="col-md-7">
                            <h2 class="featurette-heading">Zombieland: Double Tap</h2>
                            <p class="lead">Vuosikymmen sen jälkeen, kun Zombieland-elokuvasta tuli hitti ja kulttiklassikko, sen pääosanesittäjät Woody Harrelson, Jesse Eisenberg, Abigail Breslin ja Emma Stone palaavat yhteen ohjaaja Ruben Fleischerin (Venom) sekä alkuperäisten kirjoittajien Rhett Reesen ja Paul Wernickin (Deadpool) kanssa jatko-osassa Zombieland: Double Tap.</p>
                        </div>
                        <div class="col-md-5">
                            <img src="https://media.finnkino.fi/1012/Event_12661/portrait_medium/Zombieland_1080.jpg" width="250">
                        </div>
                    </div>

                    <hr class="featurette-divider">

                    <div class="row featurette">
                        <div class="col-md-7 order-md-2">
                            <h2 class="featurette-heading">Dumbo </h2>
                            <p class="lead">Disney ja mielikuvituksen mestari Tim Burton tuovat valkokankaille uuden live-action seikkailun DUMBO. Elokuva laajentaa rakastetun klassikon tarinaa, joka juhlii erilaisuutta, vaalii perhettä ja joka siivittää unelmat lentoon. Sirkuksen omistaja Max Medici (Danny DeVito) antaa entiselle tähdelle Holt Ferrierille (Colin Farrel) ja hänen lapsilleen Millylle (Nico Parker) ja Joelle (Finley Hobbins) tehtäväksi huolehtia vastasyntyneestä elefantista...</p>
                        </div>
                        <div class="col-md-5 order-md-1">
                            <img src="https://images-na.ssl-images-amazon.com/images/I/71jp0aEooTL._SY679_.jpg" width="250">
                        </div>
                    </div>

                    <hr class="featurette-divider">

                    <div class="row featurette">
                        <div class="col-md-7">
                            <h2 class="featurette-heading">Jurassic World – Fallen Kingdom</h2>
                            <p class="lead">On kulunut kolme vuotta siitä, kun teemapuisto ja luksuslomakohde Jurassic World tuhoutui dinosaurusten päästyä valloilleen. Isla Nublar on hylätty ja hävityksestä selviytyneet dinosaurukset elävät villeinä viidakoissa. Kun saaren uinuva tulivuori alkaa näyttää elonmerkkejä, Owen (Chris Pratt) ja Claire (Bryce Dallas Howard) aloittavat kampanjan, jonka tarkoituksena on pelastaa dinosaurukset uuden sukupuuton uhalta.</p>
                        </div>
                        <div class="col-md-5">
                            <img src="https://images-na.ssl-images-amazon.com/images/I/A1lxLcfQLQL._SY741_.jpg" width="250">
                        </div>
                    </div>

                <hr class="featurette-divider">
            </div>
            <!-- Myynti -->

            <!-- Footer -->
            <footer class="container">
                <p class="float-right"><a href="#">Takaisin ylös</a></p>
                <p>&copy; 2019 Videovuokraamo</p>
            </footer>
            <!-- Footer -->
        </main>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/holder/2.9.6/holder.min.js" integrity="sha256-yF/YjmNnXHBdym5nuQyBNU62sCUN9Hx5awMkApzhZR0=" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="./js/init.min.js"></script>
    </body>
</html>