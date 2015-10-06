<?php
namespace Concrete\Package\AmazonS3Filemanager;

use Package,
	BlockType,
	SinglePage,
	Loader,
	\Concrete\Core\File\StorageLocation\Type\Type as StorageLocationType,
	Config,
	Environment,
	Core;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package {

	protected $pkgHandle = 'amazon_s3_filemanager';
	protected $appVersionRequired = '5.7.0';
	protected $pkgVersion = '0.1';

	public function getPackageName(){
		return t("Amazon S3 Filemanager");
	}

	public function getPackageDescription(){
		return t("Use Amazons S3 to manager your files");
	}

	public function on_start(){
		require_once(__DIR__ . '/vendor/autoload.php');
	}

	private function onStartOverrideCoreSinglePages(){}

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

	private function uninstall_RemoveBasedStorageLocations(){

	}

}