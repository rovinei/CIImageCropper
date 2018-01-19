<?php defined('BASEPATH') OR exit('No direct script access allowed.');

/**
* 
*/
use Spatie\ImageOptimizer\OptimizerChainFactory;

class Image_Optimizer
{
	public function __construct($upload_path=null){
		$this->upload_path = $upload_path;
		$this->optimizerChain = OptimizerChainFactory::create();
	}
}