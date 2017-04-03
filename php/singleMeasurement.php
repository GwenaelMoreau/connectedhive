<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1>Affichage d'une journée de relevés des capteurs de la ruche</h1>
        <?php
        $day = filter_input(INPUT_POST, 'chosenDay', FILTER_SANITIZE_SPECIAL_CHARS);
        $month = filter_input(INPUT_POST, 'chosenMonth', FILTER_SANITIZE_SPECIAL_CHARS);
        $year = filter_input(INPUT_POST, 'chosenYear', FILTER_SANITIZE_SPECIAL_CHARS);
        if (empty($day) || empty($month) || empty($year)) {
            echo "Error passing variables, please go back to the previous page.<br></br>";
            echo "<a href='index.php'>Clic here to go back to the previous page.</a>";
        } else {

            $date = $year . "-" . $month . "-" . $day;

            //database parameters
            $hostname = "127.0.0.1";
            $username = "root";
            $password = "";
            $dbname = "connectedhive";

            // connection
            $con = mysqli_connect($hostname, $username, $password, $dbname);
            if (mysqli_connect_errno()) {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }

            //query + displaying chosen measurement
            try {
                $pdo = new PDO("mysql:host=$hostname;port=3306;dbname=$dbname", $username, $password);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            $submitInformation = $pdo->prepare('SELECT * FROM `mesures` WHERE `Date`=:date ORDER BY `Date` DESC, `Heure` DESC');
            $submitInformation->bindValue(":date", $date);

            $submitInformation->execute();
            $data = $submitInformation->fetch();


            if (empty($data)) {
                echo "No measurements on this day, or the date is incorrect.";
                echo "<a href='index.php'>Clic here to go back to the previous page.</a>";
            } else {
                $i = 0;
                while (!empty($data)) {
                    echo "<h3>There is a measurement on " . $data['Date'] . " at " . $data['Heure'] . "<br></br></h3><ul>";
                    echo "Temperature was at " . $data['Temperature'] . " degrees.<br></br>";
                    echo "Weight was at " . $data['Poids'] . " kg.<br></br>";
                    echo "Pressure was at " . $data['Pression'] ." *10^5 Pascals.<br></br>";
                    $i += 1;
                    $data = $submitInformation->fetch();
                }
                echo "<a href='index.php'>Clic here to go back to the previous page.</a>";
            }
        }
        ?>
    </body>
</html>
