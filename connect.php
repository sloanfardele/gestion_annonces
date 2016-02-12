<?php
/**
 * Created by PhpStorm.
 * User: link0
 * Date: 06/02/2016
 * Time: 16:08
 */
function connect()
{
    $link = mysqli_connect("localhost", "root", "", "stage");

    if (!$link) {
        die('erreur de connexion a la base de données(' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
    }

    return $link;

}

function deconnect($link)
{
    mysqli_close($link);
}


