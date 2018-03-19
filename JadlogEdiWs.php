<?php
namespace Jadlog;

class JadlogEdiWs
{
	private $wsdlUrl;
	private $options;
	private $method;
	function __construct($wsdlUrl, $options)
	{
		$this->wsdlUrl = $wsdlUrl;
		$this->options = $options;
	}
	public function setMethod($method){
		$this->method = $method;
	}

	public function call($array)
	{

		$soapClient = new \SoapClient(
			$this->wsdlUrl,
			array(
				'cache_wsdl' => WSDL_CACHE_NONE,
				'encoding' => 'UTF-8',
				'soap_version' => SOAP_1_1)
		);

		$array = array_merge($array,$this->options);
		$arguments = array($this->method => $array);

		$options = array('location' => $this->wsdlUrl);
		$result = $soapClient->__soapCall($this->method, $arguments, $options);
		$simplexml = new \SimpleXMLElement($result->valorarReturn);
		$value = $simplexml->Jadlog_Valor_Frete->Retorno;
		if ($value == '-1') {
			echo "Acesso Negado - Dados Incorretos -". $simplexml->Mensagem ;
			continue;
		}else if ($value == '-2'){
			echo "Parametros Incorretos - ". $simplexml->Mensagem ;
			continue;
		}else if($value == '-3'){
			echo "Erro ao ler banco de dados - ". $simplexml->Mensagem ;
			continue;
		}
		$number = doubleval(str_replace(",", ".", str_replace(".", "", $value)));
		$price = (float)str_replace(',', '.', $number);


/*		$arry_s = array_merge($array,$this->options);
		$url = $this->wsdlUrl.'&'.http_build_query($arry_s);
		$Querys = file_get_contents($url);*/

		return $price;
	}
}

/* Tipo do Seguro ―N‖ normal ―A‖ apólice própria */
abstract class Seguro  {
	const NORMAL = 'N';
	const APOSLICE_PROPRIA = 'A';
}

/* Frete a pagar no destino, ―S‖ = sim ―N‖ = não */
abstract class FretePagarDestino  {
	const SIM = 'S';
	const NAO = 'N';
}

/* Tipo de entrega ―R‖ retira unidade JADLOG, ―D‖ domicilio*/
abstract class Entrega  {
	const RETIRADA_UNIDADE_JADLOG = 'R';
	const DOMICILIO = 'D';
}

/* Modalidade do frete. Deve conter apenas números (códigos de modalidades).*/
abstract class Modalidade  {
	const EXPRESSO = 0;
	const DOT_PACKAGE = 3;
	const RODOVIARIO = 4;
	const ECONOMICO = 5;
	const DOC = 6;
	const CORPORATE = 7;
	const DOT_COM = 9;
	const INTERNACIONAL = 10;
	const CARGO = 12;
	const EMERGÊNCIAL = 14;
}
