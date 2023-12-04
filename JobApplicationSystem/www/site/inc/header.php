<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <title>Jobbsøkesystem</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        header {
            background-color: #333;
            padding: 10px;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        nav li {
            margin-right: 20px;
        }

        nav a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #4caf50;
        }

        /* Ny stil for "Logg ut"-lenken */
        nav #logout {
            background-color: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        nav #logout:hover {
            background-color: #4caf50;
        }
    </style>
</head>

<body>

    <header>
        <nav>
            <ul>
                <?php
                // Sjekk om sesjon allerede er startet før du starter en ny
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                // Vis ulike alternativer basert på brukerens userType
                if (isset($_SESSION['userType'])) {
                    if ($_SESSION['userType'] === 'jobbsoker') {
                        // Alternativer for jobbsøker
                        echo '<li><a href="jobboppføringer.php">Hjem/annonser</a></li>';
                        echo '<li><a href="profile.php">Min profil</a></li>';
                    } elseif ($_SESSION['userType'] === 'arbeidsgiver') {
                        // Alternativer for arbeidsgiver
                        echo '<li><a href="jobboppføringer.php">Hjem/annonser</a></li>';
                        echo '<li><a href="hjem.php">Opprett annonser</a></li>';
                        echo '<li><a href="index.php">Egne annonser</a></li>';
                        echo '<li><a href="profile.php">Bedriftsprofil</a></li>';
                    }
                }

                // Vis "Logg inn" og "Registrer deg" kun hvis brukeren ikke er logget inn
                if (!isset($_SESSION['username'])) {
                    echo '<li><a href="login.php">Logg inn</a></li>';
                    echo '<li><a href="register.php">Registrer deg</a></li>';
                }

                // Vis "Logg ut"-lenken hvis brukeren er logget inn
                if (isset($_SESSION['username'])) {
                    echo '<li><a id="logout" href="logout.php">Logg ut</a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>
</body>

</html>
