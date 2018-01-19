<?php
// include composer autoload
require '../vendor/autoload.php';

use Intervention\Image\ImageManager;
use Spatie\ImageOptimizer\OptimizerChainFactory;

define('CROPPER_UPLOAD_THUMBNAIL_DIR', 'uploads/thumbnails/');
define('ERROR_SAVE_THUMBNAIL_CODE', 00010);
define('ERROR_PARAMS', 00011);
define('ERROR_IMAGE_OPTIMIZER', 00012);

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
	isset($_POST['base64Image']) && 
	isset($_POST['compress_mode'])
) {

	$post_filename = isset($_POST['filename']) ? $_POST['filename'] : '';
	$post_base64_image = $_POST['base64Image'];
	$post_options = array(
		'compress_mode' => $_POST['compress_mode'], 
		'quality' => isset($_POST['quality']) ? $_POST['quality'] : 50
	);
	try {
		$response = process_upload($post_filename, $post_base64_image, $post_options);
		echo json_encode($response);
	} catch (Exception $e) {
		$response = array(
			'status' => ['code' => 501, 'message' => $e->getMessage()],
			'data' => [

			]
		);
		echo json_encode($response);
	}
}

function process_upload($filename ,$base64_image, $options){
	if (!is_string($base64_image) || 
		!is_array($options) || 
		!array_key_exists('compress_mode', $options)
	) {
		throw new Exception("Parameters dose not met requirements.", ERROR_PARAMS);
	}

	$optimizerChain = OptimizerChainFactory::create();

	$unique_filename = uniqid() . "_" . $filename;
	$upload_path = joinPaths(CROPPER_UPLOAD_THUMBNAIL_DIR, $unique_filename);
	$uploadImage = new UploadImage($base64_image, $upload_path, $options['quality']);

	if ($options['compress_mode'] == "manaul") {
		if (array_key_exists('quality', $options)) {
			try {
				$status = $uploadImage->make_and_save(true);
				if ($status->dirname) {
					$response = array(
						'status' => [
							'code' => 200,
							'message' => 'successfully uploaded cropped and optimized quality of thumbnail.'
						],
						'data' => [
							'uploaded_path' => $upload_path
						]
					);
					return $response;
				} else {
					throw new Exception("Failed to save thumbnail.", ERROR_SAVE_THUMBNAIL_CODE);
				}
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), ERROR_SAVE_THUMBNAIL_CODE);
			}
		}	
	} else {
		try {
			$status = $uploadImage->make_and_save();
			if ($status->dirname) {
				$optimizerChain->optimize($upload_path);
				$response = array(
					'status' => [
						'code' => 200,
						'message' => 'successfully uploaded cropped and automatically optimized quality of thumbnail.'
					],
					'data' => [
						'uploaded_path' => $upload_path
					]
				);
				return $response;
			} else {
				throw new Exception("Failed to save thumbnail.", ERROR_SAVE_THUMBNAIL_CODE);
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), ERROR_SAVE_THUMBNAIL_CODE);
		}
	}
}

function joinPaths($leftHandSide, $rightHandSide) { 
    return rtrim($leftHandSide, '/') .'/'. ltrim($rightHandSide, '/');
}

class UploadImage {
	function __construct($imageObj = null, $upload_path = null, $quality = 50)
	{
		$this->imageManager = new ImageManager(array('driver' => 'gd'));
		$this->imageObj = $imageObj;
		$this->upload_path = $upload_path;
		$this->quality = $quality;
	}

	function make_and_save($is_manaul_compress = false) {
		if ($this->imageObj != null && $this->upload_path != null) {
			$this->imageObj = $this->imageManager->make($this->imageObj);
			$img = $is_manaul_compress ? $this->imageObj->save($this->upload_path, (int)$this->quality) : $this->imageObj->save($this->upload_path);
			return $img;
		} else {
			throw new Exception("Missing arguments, image and upload path.", ERROR_SAVE_THUMBNAIL_CODE);
		}
	}
}
