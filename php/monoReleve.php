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
        $jour = filter_input(INPUT_POST, 'jourReleve', FILTER_SANITIZE_SPECIAL_CHARS);
        $mois = filter_input(INPUT_POST, 'moisReleve', FILTER_SANITIZE_SPECIAL_CHARS);
        $annee = filter_input(INPUT_POST, 'anneeReleve', FILTER_SANITIZE_SPECIAL_CHARS);
        if (empty($jour) || empty($mois) || empty($annee)) {
            echo "echec du passage des variables, merci de retourner à l'accueil.<br></br>";
            echo "<a href='index.php'>Cliquez ici pour revenir à l'accueil.</a>";
        } else {

            $date = $annee . "-" . $mois . "-" . $jour;
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





            //Requete+Affichage de la mesure
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
                echo "Il n'y a pas de mesure ce jour-ci, ou la date n'est pas valide.";
                echo "<a href='index.php'>Cliquez ici pour revenir à l'accueil.</a>";
            } else {
                //$nbLignes = count($data);
                $i = 0;
                while (!empty($data)) {
                    //echo "Il y a $nbLignes ce jour-ci :<br></br>";
                    echo "<h3>Il y a une mesure le " . $data['Date'] . " à " . $data['Heure'] . "<br></br></h3><ul>";
                    echo "<li>La température était de " . $data['Temperature'] . "<br></br></li>";
                    echo "<li>Le poids était de " . $data['Poids'] . "<br></br></li>";
                    echo "<li>La pression était de " . $data['Pression'] . "<br></br></li></ul>";
                    $i+=1;
                    $data = $submitInformation->fetch();
                }
                echo "<a href='index.php'>Cliquez ici pour revenir à l'accueil.</a>";
            }
        }
        ?>
    </body>
</html>
