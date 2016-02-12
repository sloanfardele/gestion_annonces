<?php
/**
 * Created by PhpStorm.
 * User: link0
 * Date: 11/02/2016
 * Time: 21:20
 */
echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">";  //Gestion des caractères spéciaux.

include 'connect.php';
//Récupère les annonces du site leboncoin.fr
function recupAnnonces()
{
    //traite la page de résultat en insérant les annonces dans la BD.

    function traitePage($page)
    {
        $link = connect(); //connexion à la BD
        $i = 0;

        //traitement du DOMDocument pour la recherche des balises <div>.

        foreach ($page->getElementsByTagName('div') as $div) {

            //teste le nombre d'annonces.

            if ($i < 100) {

                //récupération du titre, du lieu et du prix

                if ($div->getAttribute('class') == "detail") {
                    $title = trim($div->getElementsByTagName('h2')->item(0)->nodeValue);
                    if ($div->getElementsByTagName('div')->item(1)->getAttribute('class') == "placement") {
                        $lieu = trim($div->getElementsByTagName('div')->item(1)->nodeValue);
                    }
                    //teste si le prix est définis dans l'annonce, sinon met un prix de 0.

                    if (null !== $div->getElementsByTagName('div')->item(2)) {
                        if ($div->getElementsByTagName('div')->item(2)->getAttribute('class') == "price") {
                            $price = trim($div->getElementsByTagName('div')->item(2)->nodeValue);
                        }
                    } else {
                        $price = "0€";
                    }
                    $i++;
                    $title = $link->real_escape_string($title);
                    $lieu = $link->real_escape_string($lieu);
                    $price = $link->real_escape_string($price);
                    $query = "INSERT INTO animals(`titre`,`lieu`,`prix`) VALUES ('" . $title . "','" . $lieu . "','" . $price . "')";
                    mysqli_query($link, $query);
                }
            } else {
                break;
            }

        }
        deconnect($link);
    }

    $link = connect();

    //Vide la BD à chaque nouvelle récupération

    $query = "TRUNCATE animals";
    mysqli_query($link, $query);
    deconnect($link);

    $urllbc_a = 'http://www.leboncoin.fr/animaux/offres/rhone_alpes/?f=a&th=0';

    //transforme la page html en chaine de caractère
    $res_a = file_get_contents($urllbc_a);

    libxml_use_internal_errors(true);   //gestion des erreurs

    $lbcPage_a = new DOMDocument();

    $lbcPage_a->loadHTML($res_a);


    traitePage($lbcPage_a);

}


//affiche le tableau des annonces récupérées.

function afficheTab()
{
    $link = connect();
    $query = "SELECT * FROM animals";
    $result = mysqli_query($link, $query);
    if (!$result) {
        echo 'Impossible d\'exécuter la requête : ' . mysqli_error();
        exit();
    }
    echo "<table class=\"sortable\" id=\"youhou\">";
    echo "<thead>";
    echo "<tr>";
    echo "<td>";
    echo "ID";
    echo "</td>";
    echo "<td>";
    echo "Titre";
    echo "</td>";
    echo "<td>";
    echo "Lieu";
    echo "</td>";
    echo "<td>";
    echo "Prix (en €)";
    echo "</td>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    while ($row = mysqli_fetch_row($result)) {
        echo "<tr>";
        echo "<td>";
        echo $row[0];
        echo "</td>";
        echo "<td>";
        echo $row[1];
        echo "</td>";
        echo "<td>";
        echo $row[2];
        echo "</td>";
        echo "<td>";
        echo $row[3];
        //echo "€";
        echo "</td>";
        echo "</tr>";

    }
    echo "</tbody>";
    echo "</table>";
}