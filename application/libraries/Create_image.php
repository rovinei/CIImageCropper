<?php defined('BASEPATH') OR exit('No direct script access allowed.');

/**
* 
*/
class Create_Image extends \Intervention\Image\ImageManager
{
	public function __construct($params = array()){
		$this->imageObj = $params["base64_image"] ? $params["base64_image"] : "";
		$this->upload_path = $params["upload_path"] ? $params["upload_path"] : FCPATH."uploads/thumbnails/";
		$this->quality = $params["quality"] ? $params["quality"] : 50;
		parent::__construct(array('driver' => 'gd'));
	}

	public function make_and_save($is_manaul_compress = false) {
		if ($this->imageObj != null && $this->upload_path != null) {
			$this->imageObj = $this->make($this->imageObj);
			$img = $is_manaul_compress ? $this->imageObj->save($this->upload_path, (int)$this->quality) : $this->imageObj->save($this->upload_path);
			return $img;
		} else {
			throw new Exception("Missing arguments, image and upload path.", defined(ERROR_SAVE_THUMBNAIL_CODE) ? ERROR_SAVE_THUMBNAIL_CODE : 505);
		}
	}
}