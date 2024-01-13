import {PREFIX, API_URL_V1} from "./constants.js";
import {Validator} from "./utils/validator/validator.js";

const loginForm = document.getElementById("login_form");
const signupForm = document.getElementById("signup_form");
const logoutForm = document.getElementById("logout_form");

function renderErrors(errors) {
    if (!errors) return;
    const errorContainer = document.querySelector(".auth-errors");
    if (!errorContainer) {
        console.error("Error container not found");
        return;
    }

    // Clear error container
    errorContainer.innerHTML = ""

    const appendError = (error) => {
        const liError = document.createElement("li");
        liError.classList.add("error");
        const messageSpan = document.createElement("span");
        messageSpan.innerText = error.message;
        const errorStatusIcon = document.createElement("img");
        errorStatusIcon.classList.add("error-status-img");
        errorStatusIcon.src = PREFIX + "/assets/images/info-error.svg";
        liError.appendChild(errorStatusIcon);
        liError.appendChild(messageSpan);
        // Add error
        errorContainer.appendChild(liError);
    }

    errors.map(appendError);
}

/**
 * Validate signup form and return form data
 * @param {HTMLElement} form
 * @return {FormData|null} Returns null if form is not valid
 */
function validateSignupForm(form) {
    if (form == null) return null;
    const formData = new FormData(form);
    const first_name = formData.get("first_name");
    const last_name = formData.get("last_name");
    const email = formData.get("email");
    const password = formData.get("password");
    const password_confirm = formData.get("confirm_password");
    const validation = {
        first_name: Validator.name(first_name, "First name", true),
        last_name: Validator.name(last_name, "Last name"),
        email: Validator.email(email, true),
        password: Validator.password(password, "Password", true),
        password_confirm: Validator.password(password_confirm, "Confirm password", true),
    };
    let errors = [];
    if(validation.first_name.isNotValid()) {
        errors.push({message: validation.first_name.getMessage()})
    }
    if(validation.last_name.isNotValid()) {
        errors.push({message: validation.last_name.getMessage()})
    }
    if(validation.email.isNotValid()) {
        errors.push({message: validation.email.getMessage()})
    }
    if(validation.password.isNotValid()) {
        errors.push({message: validation.password.getMessage()})
    }
    if(validation.password_confirm.isNotValid()) {
        errors.push({message: validation.password_confirm.getMessage()})
    }
    if (errors.length === 0) {
        if (password === password_confirm)
            return formData;
        else {
            renderErrors([{message: "Passwords do not match"}]);
            return null;
        }
    } else {
        renderErrors(errors);
        return null;
    }
}

/**
 * Validate login form and return form data
 * @param {HTMLElement} form
 * @return {FormData|null} Returns null if form is not valid
 */
function validateLoginForm(form) {
    if (form == null) return null;
    const formData = new FormData(form);
    const email = formData.get("email");
    const password = formData.get("password");
    const email_validation = Validator.email(email, true);
    let errors = [];
    if (email_validation.isNotValid()) {
        errors.push({message: email_validation.getMessage()})
    }
    if (password.trim() == "") {
        errors.push({message: "Password cannot be empty"})
    }
    if (errors.length !== 0) {
        renderErrors(errors);
        return null;
    }
    return formData;
}

async function submitForm(data, uri) {
    // Form validation
    const fetchOptions = {
        method: "POST",
        body: data
    }
    const response = await fetch(API_URL_V1 + uri, fetchOptions);
    return await response.json();
}

async function submitSignupForm(event, uri, redirect = "/") {
    event.preventDefault();
    const validationResult = validateSignupForm(event.target);
    if (validationResult === null) return;

    const result = await submitForm(validationResult, uri)

    if (result.errors.length !== 0)
        renderErrors(result.errors);
    else
        window.location.href = PREFIX + redirect;
}

async function submitLoginForm(event, uri, redirect = "/") {
    event.preventDefault();
    const validationResult = validateLoginForm(event.target);
    if (validationResult === null) return;

    const result = await submitForm(validationResult, uri)

    if (result.errors.length !== 0)
        renderErrors(result.errors);
    else
        window.location.href = PREFIX + redirect;
}

async function submitLogoutForm(event, uri, redirect = "/") {
    event.preventDefault();

    const result = await submitForm(event.target, uri)

    if (result.errors.length !== 0)
        renderErrors(result.errors);
    else
        window.location.href = PREFIX + redirect;
}

if (loginForm) {
    loginForm.addEventListener("submit", async (event) => {
        await submitLoginForm(event, "/login");
    });
} else if (signupForm) {
    signupForm.addEventListener("submit", async (event) => {
        await submitSignupForm(event, "/signup", "/login");
    });
} else if (logoutForm) {
    logoutForm.addEventListener("submit", async (event) => {
        await submitLogoutForm(event, "/logout", "/login");
    });
} else {
    console.error("Suitable form is not found");
}