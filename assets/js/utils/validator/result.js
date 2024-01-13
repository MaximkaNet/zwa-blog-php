export class Result {
    constructor(valid, message = "") {
        this.message = message;
        this.valid = valid;
    }

    getMessage() {
        return this.message;
    }

    isValid() {
        return this.valid;
    }

    isNotValid() {
        return !this.valid
    }
}