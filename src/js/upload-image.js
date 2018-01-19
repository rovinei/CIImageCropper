export default function (filename ,base64Image, options){
	return new Promise(function(resolve, reject){
		var formData = new FormData();
		formData.append('base64Image', base64Image);
		formData.append('filename', filename);
		formData.append('compress_mode', 'manaul');
		formData.append('quality', 50);
		$.ajax({
			url: 'upload_image.php',
			type: 'POST',
			method: 'POST',
			dataType: 'json',
			data: formData,
			processData: false,
	    contentType: false,
	    success: function(response){
	    	var result = JSON.parse(JSON.stringify(response));
				swal({
        	title: result.status.code == 200 ? "Succeed" : "Failed",
        	type: result.status.code == 200 ? "success" : "error",
        	text: result.status.message,
        	timer: 4000,
        	toast: true,
        	position: "center",
        	showConfirmButton: false,
        });
	    },
	    error: function(error){
				swal({
        	title: "Failed",
        	type: "error",
        	text: "Something went wrong while upload thumbnail.",
        	timer: 4000,
        	toast: true,
        	position: "center",
        	showConfirmButton: false,
        });
	    }
		});
	});
}