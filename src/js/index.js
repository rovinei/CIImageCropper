import jQuery from 'jquery';
import ImageCropper from './image-cropper';
import uploadCroppedImage from './upload-image';
import swal from 'sweetalert2';

(function($){
	var $imgElement = $('#imageElement');
	var imgCropper;
	$('#drop').on('click', function() {
	    $('#fileUpload').trigger('click');
	});

	$('#fileUpload').on('change', function(e) {
	    addImage(e.target);
	});

	$("#drop").on("dragover", function(e) {
	    e.preventDefault();
	    e.stopPropagation();
	    $(this).addClass('dragging');
	});

	$("#drop").on("dragleave", function(e) {
	    $(this).removeClass('dragging');
	});

	$("#drop").on("drop", function(e) {
	    e.preventDefault();
	    e.stopPropagation();
	    var data = e.dataTransfer || e.originalEvent.dataTransfer;
	    addImage(data);
	});

	$('.btn-ratio').on('click', function(e){
		e.preventDefault();
		if (imgCropper) {
			imgCropper.setRatio($(this).attr('data-ratio'));
		}
	});

	$('#compress-quality-auto').on('change', function(e){
		$(this).is(':checked') ? $('#compress-quality-manaul').prop('disabled', true) : $('#compress-quality-manaul').prop('disabled', false);
	});

	$('#compress-quality-manaul').on('change', function(e){
		$(this).val() == "" ? $('#compress-quality-auto').prop('disabled', false) : $('#compress-quality-auto').prop({'disabled': true, 'checked': false});
	});

	function addImage(data) {
	    var file = data.files[0];
	    if (file.type.indexOf('image') === -1) {
	        swal({
	        	title: "Failed",
	        	type: "error",
	        	text: "File upload must be image.",
	        	timer: 4000,
	        	toast: true,
	        	position: "center",
	        	showConfirmButton: false,
	        });
	        return false;
	    }

	    var reader = new FileReader();
    reader.onload = function(event) {
      var img = new Image();
      img.onload = function() {
        if (img.width < 200 || img.height < 200) {
      	swal({
        	title: "Failed",
        	type: "error",
        	text: "Sorry, the image you uploaded doesn\'t appear to be large enough.",
        	timer: 4000,
        	toast: true,
        	position: "center",
        	showConfirmButton: false,
        });
            return false;
        }
      	cropImage(img);
      }
      img.src = event.target.result;
    }
    reader.readAsDataURL(file);
	}

	function cropImage(originalImage) {

	    $(originalImage).attr('id', 'fullImage');
	    $('#imageResize').html(originalImage);
	    $('#sectionDragAndDrop').addClass('hidden');
	    $('#sectionResize').removeClass('hidden');
	    var isReadyForCrop = false;
	    originalImage.addEventListener('ready', function() {
	    	console.log('Image ready to crop.');
	    	isReadyForCrop = true;
	    });

	    var cropperOption = {
    		preview: '#previewContainer',
    	};
    	imgCropper = new ImageCropper(originalImage, cropperOption);
    	var imgBase64 = 'data:image/png;base64,';
    	imgCropper.initCropper();

	    $('#crop').on('click', function(e) {
	    	if(!$('#compress-quality-auto').is(':checked') && $('#compress-quality-manaul').val() == "") {
	    		swal({
	        	title: "Error",
	        	type: "error",
	        	text: "Quality compress option must be specify.",
	        	timer: 4000,
	        	toast: true,
	        	position: "center",
	        	showConfirmButton: false,
	        });

	        return false;
	    	}
	    	imgBase64 = imgCropper.getInstance().getCroppedCanvas().toDataURL('image/jpeg');
        // new uploadCroppedImage('my_cropped_thumbnail.png', imgBase64, uploadOptions)
        // .then(function(error, response){
        // 	console.log(response);
        // }).catch(function(error){
        // 	console.log(JSON.stringify(error));
        // });
        var compress_mode = 
        (
        	$('#compress-quality-manaul').is(':disabled') 
        	&& 
        	$('#compress-quality-auto').is(':checked')
        ) ? 'auto' : 'manaul';

        var quality = compress_mode == 'manaul' ? parseInt($('#compress-quality-manaul').val()) : 50;

        console.log(compress_mode, quality);

        var formData = new FormData();
				formData.append('base64Image', imgBase64);
				formData.append('filename', 'cropped_thumbnail.jpeg');
				formData.append('compress_mode', compress_mode);
				formData.append('quality', quality);
				$.ajax({
					url: '/page/do_upload',
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

})(jQuery);


