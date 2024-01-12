import {API_URL_V1, PREFIX} from "./constants.js";

const editFirstNameBtn = document.querySelector("#edit-first-name");
const firstNameField = document.querySelector("#first_name");
const editLastNameBtn = document.querySelector("#edit-last-name");
const lastNameField = document.querySelector("#last_name");

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
    if(type === "error") {
        messageDiv.classList.add("error");
    } else {
        messageDiv.classList.add("success");
    }
    messageDiv.append(img, messageBodySpan);
    function fadeOut(){
        messageDiv.style.opacity = '0';
        messageDiv.addEventListener('transitionend', () => messageDiv.remove());
    }
    setTimeout(fadeOut, 3000);
    messagesContainer.append(messageDiv);
}

async function sendData({user_id, data}) {
    const options = {
        "method": "POST",
        "body": JSON.stringify(data)
    };
    const response = await fetch(API_URL_V1 + "/users/" + user_id + "/edit", options);
    return await response.json();
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
        // Send data
        const user_id = firstNameField.dataset.userId;
        const {errors, message, data} = await sendData({
            user_id, data: {
                first_name: firstNameField.value
            }
        });
        if (errors.length !== 0) {
            renderMessage("error", errors[0].message);
        } else {
            firstNameField.value = data.first_name;
            renderMessage("success", message);
        }
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
        // Send data
        const user_id = lastNameField.dataset.userId;
        const {errors, message, data} = await sendData({
            user_id, data: {
                last_name: lastNameField.value
            }
        });
        if (errors.length !== 0) {
            renderMessage("error", errors[0].message);
        } else {
            lastNameField.value = data.last_name;
            renderMessage("success", message);
        }
    }
}

editFirstNameBtn.addEventListener("click", resolveFirstName);
editLastNameBtn.addEventListener("click", resolveLastName);
