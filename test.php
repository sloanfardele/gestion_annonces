<?php
/**
 * Created by PhpStorm.
 * User: link0
 * Date: 11/02/2016
 * Time: 18:27
 */

$a = "5,9";

if ($a === (int)$a) {
    echo "entier";
    br();
    echo (int)$a;
    br();
    echo $a;
} else {
    echo "pas entier";
    br();
    echo (int)$a;
    br();
    echo $a;
}

function br()
{
    echo "<br>";
}


$nbpages = $nbarticles / 35;

if ((int)$nbpages !== $nbpages) {
    $nbpages = (int)$nbpages + 1;
}