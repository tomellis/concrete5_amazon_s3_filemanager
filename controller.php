<?php
namespace Concrete\Package\AmazonS3Filemanager;

use Package,
	\Concrete\Core\File\StorageLocation\Type\Type as StorageLocationType;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package {

	protected $pkgHandle = 'amazon_s3_filemanager';
	protected $appVersionRequired = '5.7.0';
	protected $pkgVersion = '0.1';
	protected $regions = array(
					"" 					=> "Default",
					"s3-external-1"		=> "US Standard (N. Virginia)",
					"us-west-2" 		=> "US West (Oregon)",	
					"us-west-1" 		=> "US West (N. California)",
					"eu-west-1" 		=> "EU (Ireland)",
					"eu-central-1" 		=> "EU (Frankfurt)",
					"ap-southeast-1" 	=> "Asia Pacific (Singapore)",
					"ap-southeast-2" 	=> "Asia Pacific (Sydney)",
					"ap-northeast-1" 	=> "Asia Pacific (Tokyo)",
					"sa-east-1" 		=> "South America (Sao Paulo)"
				);


	public function getPackageName(){
		return t("Amazon S3 Filemanager");
	}

	public static function getRegions(){
		$r = new Controller();
		return $r->regions;
	}

	public function getPackageDescription(){
		return t("Use Amazons S3 to manager your files");
	}

	public function on_start(){
		require_once(__DIR__ . '/vendor/autoload.php');
	}

	public function install() {
		$pkg = parent::install();
		$this->install_AddNewStorageLocation($pkg);
	}

	private function install_AddNewStorageLocation($pkg){
		StorageLocationType::add('s3', 'Amazon S3', $pkg);
	}

	public function uninstall() {
		parent::uninstall();
	}

}