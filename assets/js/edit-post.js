import {API_URL_V1, PREFIX} from "./constants.js";

const editPostForm = document.querySelector("#edit-post");

function renderMessage(type, message) {
    // Message structure
    // <div class="message-item error">
    //     <img src="/assets/images/info.svg" alt="info">
    //         <span class="message-body">Test text</span>
    // </div>
    const messagesContainer = document.querySelector(".messages-container");
    if (!messagesContainer) {
        console.error("Message container is not found");
        return;
    }
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

async function sendData({post_id, data}) {
    const fetchOptions = {
        method: "POST",
        body: data
    }
    const response = await fetch(API_URL_V1 + "/posts/" + post_id + "/edit", fetchOptions);
    return await response.json();
}

async function handleSubmit(event) {
    event.preventDefault();
    const formData = new FormData(editPostForm);
    const id = Number(formData.get("id"));
    const title = formData.get("title");
    const content = formData.get("content");
    const category_id = Number(formData.get("category_id"));

    if (isNaN(id)) {
        renderMessage("error", "Id must be a number")
        return;
    }
    if (id < 0) {
        renderMessage("error", "Id cannot less then zero")
        return;
    }
    if (title.trim().length === 0) {
        renderMessage("error", "Title cannot be empty")
        return;
    }
    // Content can be empty ...
    if (isNaN(category_id)) {
        renderMessage("error", "Category id must be a number")
        return;
    }
    if (category_id < 0) {
        renderMessage("error", "Category id cannot be less the zero")
        return;
    }

    const {message, errors, data} = await sendData({
        post_id: formData.get("id"),
        data: formData
    });

    console.log({message, errors, data});

    if (errors.length === 0) {
        renderMessage("success", message ? message : "Changes saved");
    } else {
        errors.map(({message}) => {
            renderMessage("error", message);
        });
    }
}

editPostForm.addEventListener("submit", handleSubmit);