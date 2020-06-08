<?php
require 'Vizsga.php';

function beolvas($fnev){
    $tomb=array();
    $file=fopen($fnev, 'r') or die("Hiba a beolvasásnál"+$fnev);
    while (!feof($file)){
        $sor= fgets($file);
        if(strlen($sor)>3){
            $split= explode(";", $sor);
            $evek=array();
            for ($i = 1; $i < count($split); $i++) {
                $evek[]=$split[$i];
            }
            $n=new Vizsga($split[0], $evek);
            $tomb[]=$n;
        }
    }
    array_shift($tomb);
    return $tomb;
}
$a=beolvas('sikeres.csv');
$b=beolvas('sikertelen.csv');
echo '2. feladat: <br>';
$c=array();
foreach ($a as $sikeres) {
    foreach ($b as $sikertelen) {
        if(strcmp($sikeres->getNyelv(),$sikertelen->getNyelv())==0){
            $c[$sikertelen->getNyelv()]=$sikeres->sumAllYear()+$sikertelen->sumAllYear();
        }
    }
}
arsort($c);
$szamol=0;
$behuzas="&nbsp&nbsp&nbsp&nbsp&nbsp";
foreach ($c as $nyelv => $db) {
    if($szamol<3){
        echo $behuzas.$nyelv.'<br>';
        $szamol++;
    }
}

echo '3. feladat: <br>';
echo    '<form method="POST" action="#">'
        . '<input type="text" name="ev">'
        . '<input type="submit" value="Küldés">'
       .'</form>';
if(isset($_POST['ev'])&&!empty($_POST['ev'])){
    $beker=$_POST['ev'];
    if($beker<2009 || $beker>2017){
        die('A megadott évnek 2009 és 2017 között kell lennie!<br>');
    }
}else{
    die();
}

echo '4. feladat: <br>';
$s=0;
$t=0;
$arany=0.0;
$nullasok=array();
for ($i = 0; $i < count($a); $i++) {
    $s += $a[$i]->getEvek()[$beker - 2009];
    $t += $b[$i]->getEvek()[$beker - 2009];
    if($s+$t==0){
        $arany=0;
        $nullasok[]=$a[$i]->getNyelv();
    } else {
        $arany=$t/((double)$t+$s)*100;
    }
    $osszArany[$a[$i]->getNyelv()]=$arany;
    $arany=0;
    $s=0;
    $t=0;
}
arsort($osszArany);
reset($osszArany);
echo $behuzas.$beker.'-ben '
        .key($osszArany).' nyelvből a sikertelen vizsgák aránya '
        .number_format(
                $osszArany[key($osszArany)],
                2,
                ".",
                "")
        ."%<br>";
echo '5. feladat: <br>';
foreach ($nullasok as $value) {
    echo $behuzas.$value.'<br>';
}    

echo '6. feladat: osszesites.csv';
$fajlba="";
$arany2=0.0;
for ($i = 0; $i < count($a); $i++) {
    	$fajlba.=$a[$i]->getNyelv().";";
        $fajlba.=$a[$i]->sumAllYear()+$b[$i]->sumAllYear().";";
        $arany2=$a[$i]->sumAllYear()/($a[$i]->sumAllYear()+(double)$b[$i]->sumAllYear())*100;
        $fajlba.= number_format($arany2,2,".","")."%\n";
        $arany2=0.0;
}

$myFile= fopen("osszesites.csv", "w");
fwrite($myFile, $fajlba);