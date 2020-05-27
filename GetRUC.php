<?php


$TokenGet = $_POST["tonketSet"];
$RUC = $_POST["rucSet"];

$dato = str_replace("/[\t\r\n|\n|\r]+/", '', $RUC);
echo $dato;

// $dondeEstas =getCSVValues($RUC,',',$TokenGet);

function getCSVValues($string, $separator=",",$_gettoken)
{
    $elements = explode($separator, $string);
    //crea el archivo plano
    $file = fopen("RUC_GET_V2".date("Y-m-d").".csv","a");
    fwrite($file, 'numeroRuc,razonSocial,nombreComercial,agenteRepresentante,direccionCompleta 1'.PHP_EOL);
    for ($i = 0; $i < count($elements); $i++) {
        // $nquotes = substr_count($elements[$i], '"');
        // var_dump($elements[$i].'</br>');
        //consular RUC ya separado
        $respuestaRUC = sendRuc($elements[$i],$_gettoken);
        $respuestaRUC2 = direccion($elements[$i],$_gettoken);
        // carga de data
        fwrite($file, $respuestaRUC[0]->numeroRuc .','.$respuestaRUC[0]->razonSocial.','.$respuestaRUC[0]->nombreComercial.','.$respuestaRUC[0]->agenteRepresentante .','.$respuestaRUC2[0]->direccionCompleta.PHP_EOL);
        // fwrite($file,$respuesta.PHP_EOL);
    }
    fclose($file);
    return $elements;
}

function sendRuc($getRuc,$TokenSRI=""){
  $url = 'https://srienlinea.sri.gob.ec/sri-catastro-sujeto-servicio-internet/rest/ConsolidadoContribuyente/obtenerPorNumerosRuc?&ruc='.$getRuc;
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HTTPGET, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 0);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=UTF-8', 'User-Agent: cUrl Testing', 'Authorization:'.$TokenSRI));
  $resultRUC = curl_exec($ch);
  $respuestaRUC = json_decode($resultRUC);
  return $respuestaRUC;
  // var_dump($respuestaRUC);
}

// direccion
// https://srienlinea.sri.gob.ec/sri-catastro-sujeto-servicio-internet/rest/Establecimiento/consultarPorNumeroRuc?numeroRuc=0190485943001

function direccion($getRuc,$TokenSRI){
  $url = 'https://srienlinea.sri.gob.ec/sri-catastro-sujeto-servicio-internet/rest/Establecimiento/consultarPorNumeroRuc?numeroRuc='.$getRuc;
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HTTPGET, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 0);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=UTF-8', 'User-Agent: cUrl Testing', 'Authorization:'.$TokenSRI));
  $resultRUC = curl_exec($ch);
  $respuestaRUC = json_decode($resultRUC);
  return $respuestaRUC;
  // var_dump($respuestaRUC[0]);
}

 ?>
