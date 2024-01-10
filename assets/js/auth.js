import {PREFIX, API_URL_V1} from "./constants.js";

const loginForm = document.getElementById("login_form");
const signupForm = document.getElementById("signup_form");
const logoutForm = document.getElementById("logout_form");

function renderErrors(errors) {
    if(!errors) return;
    const errorContainer = document.querySelector(".auth-errors");
    if(!errorContainer) {
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

async function submitForm(event, uri, redirect = "/") {
    event.preventDefault();
    const fetchOptions = {
        method: "POST",
        body: new FormData(event.target)
    }
    const response = await fetch(API_URL_V1 + uri, fetchOptions);
    const result = await response.json();

    if(result.errors.length !== 0)
        renderErrors(result.errors);
    else
        window.location.href = redirect;
}

if(loginForm) {
    loginForm.addEventListener("submit", async (event) => {
        await submitForm(event, "/login");
    });
} else if (signupForm) {
    signupForm.addEventListener("submit", async (event) => {
        await submitForm(event, "/signup", "/login");
    });
} else if (logoutForm) {
    logoutForm.addEventListener("submit", async (event) => {
        await submitForm(event, "/logout", "/login");
    });
} else {
    console.error("Suitable form is not found");
}