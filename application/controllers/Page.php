<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('cropper');
	}

	public function do_upload()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
			isset($_POST['base64Image']) && 
			isset($_POST['compress_mode'])
		) {

			$filename = isset($_POST['filename']) ? $_POST['filename'] : '';
			$base64_image = $_POST['base64Image'];
			$options = array(
				'compress_mode' => $_POST['compress_mode'], 
				'quality' => isset($_POST['quality']) ? $_POST['quality'] : 50
			);
			try {
				if (!is_string($base64_image) || 
					!is_array($options) || 
					!array_key_exists('compress_mode', $options)
				) {
					throw new Exception("Parameters dose not met requirements.", ERROR_PARAMS);
				}

				$filename = uniqid() . "_" . $filename;
				$upload_path = rtrim(CROPPER_UPLOAD_THUMBNAIL_DIR, '/') .'/'. ltrim($filename, '/');
				$create_image_params = array(
					"base64_image" => $base64_image,
					"upload_path" => $upload_path,
					"quality" => $options['quality']
				);
				$this->load->library('create_image', $create_image_params);

			} catch (Exception $e) {
				$response = array(
					'status' => ['code' => 501, 'message' => $e->getMessage()],
					'data' => [

					]
				);
				return $this->output
            ->set_content_type('application/json')
            ->set_status_header(500)
            ->set_output(json_encode($response));
			}

			if ($options['compress_mode'] == "manaul") {
				if (array_key_exists('quality', $options)) {
					try {
						$status = $this->create_image->make_and_save(true);
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
							return $this->output
		            ->set_content_type('application/json')
		            ->set_status_header(200)
		            ->set_output(json_encode($response));
						} else {
							throw new Exception("Failed to save thumbnail.", ERROR_SAVE_THUMBNAIL_CODE);
						}
					} catch (Exception $e) {
						$response = array(
							'status' => ['code' => ERROR_SAVE_THUMBNAIL_CODE, 'message' => $e->getMessage()],
							'data' => [

							]
						);
						return $this->output
		            ->set_content_type('application/json')
		            ->set_status_header(500)
		            ->set_output(json_encode($response));
					}
				}	
			} else {
				try {
					$status = $this->create_image->make_and_save();
					if ($status->dirname) {
						$this->load->library('image_optimizer');
						$this->image_optimizer->optimizerChain->optimize($upload_path);
						$response = array(
							'status' => [
								'code' => 200,
								'message' => 'successfully uploaded cropped and automatically optimized quality of thumbnail.'
							],
							'data' => [
								'uploaded_path' => $upload_path
							]
						);
						return $this->output
		            ->set_content_type('application/json')
		            ->set_status_header(200)
		            ->set_output(json_encode($response));
					} else {
						throw new Exception("Failed to save thumbnail.", ERROR_SAVE_THUMBNAIL_CODE);
					}
				} catch (Exception $e) {
					$response = array(
							'status' => ['code' => ERROR_SAVE_THUMBNAIL_CODE, 'message' => $e->getMessage()],
							'data' => [

							]
						);
						return $this->output
		            ->set_content_type('application/json')
		            ->set_status_header(500)
		            ->set_output(json_encode($response));
				}
			}

		}
	}

}
