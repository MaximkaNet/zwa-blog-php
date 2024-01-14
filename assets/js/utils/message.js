import {PREFIX} from "../constants.js";

export function renderMessage(type, message) {
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