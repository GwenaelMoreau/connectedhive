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
        //Parametres BDD
        $hostname = "127.0.0.1";
        $username = "root";
        $password = "";
        $dbname = "connectedhive";

        // Connection et vérification de la connection
        $con = mysqli_connect($hostname, $username, $password, $dbname);
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        
        
        
        
        
        //Requete+Affichage de la derniere mesure
        try {
            $pdo = new PDO("mysql:host=$hostname;port=3306;dbname=$dbname", $username, $password);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $submitInformation = $pdo->prepare('SELECT * FROM `mesures` ORDER BY `Date` DESC, `Heure` DESC');
        //$submitInformation->bindValue(":varOne",$variable1);
        //$submitInformation->bindValue(":varTwo",$variable2);
        $submitInformation->execute();
        $data=$submitInformation->fetch();
        echo "<h3>La dernière mesure en date est le ".$data['Date']." à ".$data['Heure']."<br></br></h3>";
        echo "La température était de ".$data['Temperature']."<br></br>";
        echo "Le poids était de ".$data['Poids']."<br></br>";
        echo "La pression était de ".$data['Pression']."<br></br>";
        
        
        $i= $j = 1;
        $k = 2017;
        echo "<h3>Choisissez directement une date :</h3>";
        echo "<form method='post' action='monoReleve.php'>";
        echo "<select name='jourReleve'>";
        while($i<32) {
        echo "<option value=$i>" . $i . "</option>";
        $i+=1;
        }
        echo "</select>";
        echo "<select name='moisReleve'>";
        while($j<13) {
        echo "<option value=$j>" . $j . "</option>";
        $j+=1;
        }
        echo "</select>";
        echo "<select name='anneeReleve'>";
        while($k<date(Y)+1) {
        echo "<option value=$k>" . $k . "</option>";
        $k+=1;
        }
        echo "</select>"
        . "<input  type='submit' value='Afficher les mesures du jour choisit'>"
        . "</form>";
        
        
        /* echo "Choisissez directement une date : (Format : Année-Mois-Jour)";
          echo "<form method='post' action='monoReleve.php'>";
          echo "<select name='jourReleve'>";
          foreach (glob("*.txt") as $filename) {
          echo "<option value=$filename>" . substr($filename, 0, 10) . "</option>";
          }
          echo "</select>"
          . "<input  type='submit' value='Afficher les mesures du jour choisit'>"
          . "</form>";

          //Ouverture fichier du jour meme
          $dateday=date("Y-m-d");

          $filename = $dateday.".txt";
          if (file_exists($filename) && $file = fopen($filename, "r") != false){
          $filenamecut = explode("-", $filename);
          $year = $filenamecut[0];
          $month = $filenamecut[1];
          $day = substr($filenamecut[2], 0, 2);
          echo "<h4>Au jour du $day/$month/$year , les mesures sont :<br></br></h4><ul>";
          while (!feof($file)) {
          $line1 = fgets($file);
          $line2 = fgets($file);
          $line1cut = explode("-", $line1);
          $hour = $line1cut[0];
          $minutes = $line1cut[1];
          $seconds = $line1cut[2];
          $line2cut = explode(";", $line2);
          $temperature = $line2cut[0];
          $poids = $line2cut[1];
          $pression = $line2cut[2];
          echo "<li>A $hour heures $minutes minutes et $seconds secondes : la température était de $temperature, le poids de $poids et la pression de $pression.<br></br></li>";
          }
          echo"</ul>";
          fclose($file);
          }
          else {
          echo("<br></br>Il n'y a pas de mesure aujourd'hui.");
          $files = scandir('data', SCANDIR_SORT_DESCENDING);
          $newest_file = $files[0];
          }

         */
        ?>
    </body>
</html>
