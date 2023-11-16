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
    <?php
    // Sjekk om sesjon allerede er startet før du starter en ny
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Sett PHP-variabler basert på brukerens innloggingsstatus
    $isLoggedIn = isset($_SESSION['username']);
    ?>

    <script>
        function displayAccessDeniedMessage() {
            var messageDiv = document.createElement('div');
            messageDiv.innerHTML = 'Ingen tilgang! Logg inn!';
            messageDiv.style.position = 'fixed';
            messageDiv.style.top = '50%';
            messageDiv.style.left = '50%';
            messageDiv.style.transform = 'translate(-50%, -50%)';
            messageDiv.style.padding = '20px';
            messageDiv.style.backgroundColor = '#333';
            messageDiv.style.color = 'white';
            messageDiv.style.borderRadius = '5px';
            messageDiv.style.zIndex = '9999';

            document.body.appendChild(messageDiv);

            setTimeout(function() {
                document.body.removeChild(messageDiv);
            }, 5000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            var homeButton = document.querySelector('a[href="profile.php"]');
            var jobListingsButton = document.querySelector('a[href="jobboppføringer.php"]');

            if (homeButton) {
                homeButton.addEventListener('click', function(event) {
                    // Hindrer lenken i å utføre standard handling hvis ikke logget inn
                    if (!<?php echo $isLoggedIn ? 'true' : 'false'; ?>) {
                        event.preventDefault();
                        displayAccessDeniedMessage();
                    }
                });
            }

            if (jobListingsButton) {
                jobListingsButton.addEventListener('click', function(event) {
                    // Hindrer lenken i å utføre standard handling hvis ikke logget inn
                    if (!<?php echo $isLoggedIn ? 'true' : 'false'; ?>) {
                        event.preventDefault();
                        displayAccessDeniedMessage();
                    }
                });
            }
        });
    </script>

    <header>
        <nav>
            <ul>
                <li><a href="profile.php">Hjem</a></li>
                <li><a href="jobboppføringer.php">Jobboppføringer</a></li>
                <?php
                // Vis "Logg inn" og "Registrer deg" kun hvis brukeren ikke er logget inn
                if (!$isLoggedIn) {
                    echo '<li><a href="login.php">Logg inn</a></li>';
                    echo '<li><a href="register.php">Registrer deg</a></li>';
                }

                // Vis "Logg ut"-lenken hvis brukeren er logget inn
                if ($isLoggedIn) {
                    echo '<li><a id="logout" href="logout.php">Logg ut</a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>
</body>

</html>