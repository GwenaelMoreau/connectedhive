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
        <?php
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

        //Query + displaying most recent measurement
        try {
            $pdo = new PDO("mysql:host=$hostname;port=3306;dbname=$dbname", $username, $password);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $submitInformation = $pdo->prepare('SELECT * FROM `mesures` ORDER BY `Date` DESC, `Heure` DESC');
        $submitInformation->execute();
        $data = $submitInformation->fetch();
        echo "<h3>Last measurement to date was the " . $data['Date'] . " at " . $data['Heure'] . "<br></br></h3>";
        echo "Temperature was at " . $data['Temperature'] . " degrees.<br></br>";
        echo "Weight was at " . $data['Poids'] . " kg.<br></br>";
        echo "Pressure was at " . $data['Pression'] . " *10^5 Pascals.<br></br>";


        //Displaying measurement by date
        $i = $j = 1;
        $k = 2017;
        echo "<h3>Directly choose a date :</h3>";
        echo "<form method='post' action='singleMeasurement.php'>";
        echo "<select name='chosenDay'>";
        while ($i < 32) {
            echo "<option value=$i>" . $i . "</option>";
            $i += 1;
        }
        echo "</select>";
        echo "<select name='chosenMonth'>";
        while ($j < 13) {
            echo "<option value=$j>" . $j . "</option>";
            $j += 1;
        }
        echo "</select>";
        echo "<select name='chosenYear'>";
        while ($k < date(Y) + 1) {
            echo "<option value=$k>" . $k . "</option>";
            $k += 1;
        }
        echo "</select>"
        . "<input  type='submit' value='Display this day's measurements>"
        . "</form>";
        ?>
    </body>
</html>
