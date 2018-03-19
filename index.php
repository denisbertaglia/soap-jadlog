<?php
require_once 'JadlogEdiWs.php';
use Jadlog\JadlogEdiWs;
use Jadlog\Modalidade;
use Jadlog\Seguro;
use Jadlog\FretePagarDestino;
use Jadlog\Entrega;

$wsdlUrl = 'http://www.jadlog.com.br/JadlogEdiWs/services/ValorFreteBean?wsdl';
$options = [
	'vCnpj' => '***********',
	'Password' => '*****'
];
$soapCliente = new JadlogEdiWs($wsdlUrl, $options);
$soapCliente->setMethod('valorar');

$parametros = [
	'vModalidade' =>Modalidade::EXPRESSO,
	'vSeguro' =>Seguro::NORMAL,
	'vVlDec' =>'100,00',
	'vVlColeta' =>'0',
	'vCepOrig' =>'75909802',
	'vCepDest' =>'66825107',
	'vPeso' =>'1.00', /*Em quilos */
	'vFrap' =>FretePagarDestino::NAO,
	'vEntrega' =>Entrega::DOMICILIO,
];

$resposta = $soapCliente->call($parametros);
var_dump($resposta);
