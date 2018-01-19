export default class InvalidArgument extends Error {
	constructor(message = undefined , ...params) {
		super(...params);
		if (Error.captureStackTrace) {
	      Error.captureStackTrace(this, CustomError);
	    }
	    this.code = 890;
	    this.name = "InvalidArgument";
	    this.message = message ? message : "Passing invalid argument exception.";
	}

	getMessage() {
		return this.message;
	}

	getCode() {
		return this.code;
	}

	toString() {
		return "Error code " + this.code + " by " + this.message;
	}
}