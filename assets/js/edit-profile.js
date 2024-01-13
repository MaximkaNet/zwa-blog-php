import {API_URL_V1, PREFIX} from "./constants.js";

const profileForm = document.querySelector("#profile-form");

const editFirstNameBtn = document.querySelector("#edit-first-name");
const firstNameField = document.querySelector("#first_name");
const editLastNameBtn = document.querySelector("#edit-last-name");
const lastNameField = document.querySelector("#last_name");

async function submitForm(e) {
    e.preventDefault();
    // Send data
    const user_id = document.querySelector("#user_id").value;
    const fetchOptions = {
        "method": "POST",
        "body": new FormData(e.target)
    };
    const response = await fetch(API_URL_V1 + "/users/" + user_id + "/edit", fetchOptions);
    const {errors, message, data} = await response.json();
    if (errors.length !== 0) {
        renderMessage("error", errors[0].message);
    } else {
        renderMessage("success", message);
    }
    if(data != null) {
        // profileForm.reset();
        if (data.avatar != undefined) {
            document.querySelector("#avatar")
                .setAttribute("src", PREFIX + "/static/users/" + data.avatar);
            document.querySelector("#avatar-file_name").innerText = data.avatar;
        }
        if (data.first_name != undefined) {
            firstNameField.value = data.first_name;
        }
        if (data.last_name != undefined) {
            firstNameField.value = data.last_name;
        }
    }
}

profileForm.addEventListener("submit", submitForm);



function renderMessage(type, message) {
    // Message structure
    // <div class="message-item error">
    //     <img src="/assets/images/info.svg" alt="info">
    //         <span class="message-body">Test text</span>
    // </div>
    const messagesContainer = document.querySelector(".messages-container");
    const messageDiv = document.createElement("div");
    messageDiv.classList.add("message-item");
    const img = document.createElement("img");
    img.setAttribute("src", PREFIX + "/assets/images/info.svg");
    img.setAttribute("alt", "info");
    const messageBodySpan = document.createElement("span");
    messageBodySpan.classList.add("message-body");
    messageBodySpan.innerText = message;
    if (type === "error") {
        messageDiv.classList.add("error");
    } else {
        messageDiv.classList.add("success");
    }
    messageDiv.append(img, messageBodySpan);

    function fadeOut() {
        messageDiv.style.opacity = '0';
        messageDiv.addEventListener('transitionend', () => messageDiv.remove());
    }

    setTimeout(fadeOut, 3000);
    messagesContainer.append(messageDiv);
}

async function resolveFirstName(e) {
    // Toggle readonly
    if (firstNameField.hasAttribute("readonly")) {
        firstNameField.removeAttribute("readonly");
        editFirstNameBtn.setAttribute("alt", "complete");
        editFirstNameBtn.setAttribute("src", PREFIX + "/assets/images/save.svg");
    } else {
        firstNameField.setAttribute("readonly", "");
        editFirstNameBtn.setAttribute("alt", "edit");
        editFirstNameBtn.setAttribute("src", PREFIX + "/assets/images/pen.png");
    }
}

async function resolveLastName(e) {
    // Toggle readonly
    if (lastNameField.hasAttribute("readonly")) {
        lastNameField.removeAttribute("readonly");
        editLastNameBtn.setAttribute("alt", "complete");
        editLastNameBtn.setAttribute("src", PREFIX + "/assets/images/save.svg");
    } else {
        lastNameField.setAttribute("readonly", "");
        editLastNameBtn.setAttribute("alt", "edit");
        editLastNameBtn.setAttribute("src", PREFIX + "/assets/images/pen.png");
    }
}

editFirstNameBtn.addEventListener("click", resolveFirstName);
editLastNameBtn.addEventListener("click", resolveLastName);
