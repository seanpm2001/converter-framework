<?php
require_once 'vendor/autoload.php';
require_once __DIR__.'/config_map.php';
use Symfony\Component\Yaml\Yaml;

class converter
{
	protected $credentials_source;
	protected $credentials_destination;
	protected $con_source;
	protected $con_destination;
	protected $config;
	protected $yamlQ;

	function __construct($dbname_source, $dbname_destination, $username, $dbpass, $dbdriver='pdo_mysql', $host='localhost')
	{
		$this->config = new Doctrine\DBAL\Configuration();
		$this->yamlQ = array();
		$this->credentials_source=array( //Set up the credentianls and generate a connection object for future use.
		  'dbname'=>$dbname_source,
		  'user'=>$username,
		  'password'=>$dbpass,
		  'host'=>$host,
		  'driver'=>$dbdriver,
		);
		$this->credentials_destination=array( //Set up the credentianls and generate a connection object for future use.
		  'dbname'=>$dbname_destination,
		  'user'=>$username,
		  'password'=>$dbpass,
		  'host'=>$host,
		  'driver'=>$dbdriver,
		);
		$this->con_source = \Doctrine\DBAL\DriverManager::getConnection($this->credentials_source,$this->config);
		$this->con_destination = \Doctrine\DBAL\DriverManager::getConnection($this->credentials_destination,$this->config);
		//var_dump($this->con);
		$this->begin_conversion('user_to_phpBB_user.yml');
	}

	function build_process_queue()
	{
		//Quee Builder Function to get a Quee of YAML files to process.
		//Hardcode file name as conversionQ.yml
		$this->yamlQ = Yaml::parse(file_get_contents('conversionQ.yml'));
		print_r($this->yamlQ);
	}

	function begin_conversion($file)
	{
		//Function responsible for starting the conversion by generating the configMap object.
		//This function will be wrapped over by another function to process every yaml class from yamlQ;
		//Since we havent created a Q system, we will just be using this function for now.
		$cf = new config_map($this->con_source, $this->con_destination ,$file);

	}
}
?>