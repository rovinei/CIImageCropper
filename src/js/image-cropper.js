import Cropper from 'cropperjs';
import InvalidArgument from './exceptions/invalidArgument';

export default class ImageCropper {
	constructor($imageElement, options) {
		if (!$imageElement) throw new InvalidArgument("First argument must be an image element DOM.", 890); 
		this.$imageElement = $imageElement;
		this.options = (typeof options === 'object' && !(options instanceof Array)) ? options : {};
	}

	initCropper() {
		console.log("Initialize cropper object.");
		var self = this;
		self.cropperInstance = new Cropper(self.$imageElement, {
			aspectRatio: 16 / 9,
			viewMode: 1,
			dragMode: 'move',
			// preview: self.options.preview,
			// ...self.options
			// scalable: false,
			// zoomable: false,
			// zoomOnTouch: false,
		});
	}

	getInstance() {
		return this.cropperInstance;
	}

	setRatio(ratio) {
		var self = this;
		self.cropperInstance.setAspectRatio(parseFloat(ratio));
	}

}