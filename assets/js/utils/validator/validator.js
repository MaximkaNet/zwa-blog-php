import {Result} from "./result.js";

export class Validator {
    static email(value, required = false) {
        const field_name = "Email";
        const re = new RegExp(/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/);
        if (required && value == null) {
            return new Result(false, `${field_name} is not valid. Empty`);
        } else if (
            required && re.test(value) ||
            !required && re.test(value) ||
            !required && value == null
        ) {
            return new Result(true, `${field_name} is valid`);
        }
        return new Result(false, `${field_name} is not valid`);
    }

    static password(value, field_name = "Password", required = false) {
        const re = new RegExp(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/);
        if (required && value == null) {
            return new Result(false, `${field_name} field is required`);
        } else if (
            required && re.test(value) ||
            !required && re.test(value) ||
            !required && value == null
        ){
            return new Result(true, `${field_name} is valid`);
        }
        return new Result(
            false,
            `The ${field_name} must contain capital letters and numbers or it is too short (minimum 8 characters)`
        );
    }

    static name(value, field_name = "Name", required = false) {
        const re = new RegExp(/\s+/);
        if (required && value == null) {
            return new Result(false, `${field_name} field is required`);
        } else if (
            required && !re.test(value) ||
            !required && !re.test(value) ||
            !required && value == null
        ) {
            return new Result(true, `${field_name} field is valid`);
        }
        if (value.length > 15) {
            return new Result(false, `${field_name} is too long`);
        }
        if (re.test(value)) {
            return new Result(false, `Gaps in ${field_name} are not allowed`);
        }
        return new Result(
            true,
            `${field_name} is valid`
        );
    }
}

export class FileValidator {
    /**
     * Check file size
     * @param file
     * @param max_size default 10000 (~10kb)
     * @returns {Result}
     */
    static size(file, max_size = 10000) {
        if (file.size < max_size) {
            return new Result(true, "File is valid");
        }
        return new Result(false, "File is not valid");
    }

    static type(
        file,
        allowed_types = ["png", "jpg", "jpeg", "svg", "ico"]
    ) {
        const fileType = file.name.substring(file.name.indexOf(".") + 1);
        if (fileType.toLowerCase() in allowed_types) {
            return new Result(true, "Type is valid");
        }
        return new Result(false, "Type is not valid");
    }
}