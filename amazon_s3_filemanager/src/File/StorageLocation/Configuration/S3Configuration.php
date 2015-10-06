<?php
namespace Concrete\Package\AmazonS3Filemanager\Src\File\StorageLocation\Configuration;


use \Concrete\Core\Error\Error as coreError,
	\Concrete\Core\File\StorageLocation\Configuration\Configuration as coreConfiguration,
	\Concrete\Core\File\StorageLocation\Configuration\ConfigurationInterface as coreConfigurationInterface,
	\Concrete\Core\Http\Request as coreRequest,
	\Concrete\Flysystem\Adapter\AwsS3 as coreAwsS3;

use Aws\S3\S3Client;



class S3Configuration extends coreConfiguration implements coreConfigurationInterface{


	protected $bucketname;
	protected $accesskey;
	protected $secretkey;
	protected $publicPath;
	protected $subfolder;


	public function setBucketname($str){
		$this->bucketname = $str;
	}

	public function getBucketname(){
		return $this->bucketname;
	}

	public function setAccesskey($str){
		$this->accesskey = $str;
	}

	public function getAccesskey(){
		return $this->accesskey;
	}

	public function setSecretkey($str){
		$this->secretkey = $str;
	}

	public function getSecretkey(){
		return $this->secretkey;
	}

	public function setPublicPath($str){
		$this->publicPath = $str;
	}

	public function getPublicPath(){
		return $this->publicPath;
	}

	public function setSubfolder($str){
		$this->subfolder = $str;
	}

	public function getSubfolder(){
		return $this->subfolder;
	}

	public function hasPublicURL(){
		return 'https://lokalleads.s3.amazonaws.com/';
	}
	
	public function hasRelativePath(){
		return 'https://lokalleads.s3.amazonaws.com/';
	}
	
	public function loadFromRequest(coreRequest $req){
		$data = $req->get('fslType');
		$this->bucketname = $data['bucketname'];
		$this->accesskey = $data['accesskey'];
		$this->secretkey = $data['secretkey'];
		$this->subfolder = $data['subfolder'];
		$this->publicPath = trim($data['publicPath'], '/');
	}
	
	public function validateRequest(coreRequest $req){
		$e = new coreError();
	
		$data = $req->get('fslType');
		$this->bucketname = $data['bucketname'];
		$this->accesskey = $data['accesskey'];
		$this->secretkey = $data['secretkey'];
		$this->subfolder = $data['subfolder'];
		$this->publicPath = $data['publicPath'];

		if(!$this->bucketname) {
			$e->add(t("You must add a Amazon-S3 Bucketname."));
		}else if(!$this->accesskey){
			$e->add(t("You must add a Amazon-S3 Accesskey."));
		}else if(!$this->secretkey){
			$e->add(t("You must add a Amazon-S3 Secretkey."));
		}else if(!$this->testS3Connection()){
			$e->add(t("Fehler beim Aufbau der Verbindung. Bitte Überprüfe deine Angaben."));
		}
		return $e;
	}

	private function testS3Connection(){
		$bucketExist = false;
		try {
			$s3Client = S3Client::factory(array(
				'key' => $this->accesskey,
				'secret' => $this->secretkey
			));

			$buckets = $s3Client->listBuckets();

			foreach($buckets['Buckets'] as $key => $value) {
				if($value['Name'] == $this->bucketname)
					$bucketExist = true;
			}

			if($bucketExist)
				return true;
			return false;
		}catch(Exception $e){
			return false;
		}
	}

	public function getAdapter(){
		$client = S3Client::factory(array(
			'key' => $this->accesskey,
			'secret' => $this->secretkey
		));

		$AwsS3 = new coreAwsS3($client,$this->bucketname,'/test123');
		return $AwsS3;
	}
	
	public function getPublicURLToFile($file){
		if($this->publicPath)
			return $this->publicPath.$file;
		return '//'.$this->bucketname.'.s3.amazonaws.com'.($this->subfolder ? $this->subfolder : '').$file;
	}

	
	private function setHtaccessEntry(){
		//RewriteRule ^somefolder/(.*)$ http://static.domain.com/$1 [P]
	}
	
	private function getHtaccessEntry(){
		$strHt = '
		# -- s3 amazon filemanager rewrite start --'
		. $this->getRewriteRules() . '
		# -- s3 amazon filemanager rewrite end --
		';
		return preg_replace('/\t/', '', $strHt);
	}
	
	public function getRewriteRules(){
		$strRules = '
		<IfModule mod_rewrite.c>
			RewriteEngine On
			RewriteBase /
			RewriteRule ^'.$this->publicPath.'/(.*)$ http://'.$this->bucketname.'.s3.amazonaws.com'.($this->subfolder ? $this->subfolder : '').'$1 [L]
		</IfModule>';

		return $strRules;
	}

	public function getRelativePathToFile($file){
		return $file;
	}
}