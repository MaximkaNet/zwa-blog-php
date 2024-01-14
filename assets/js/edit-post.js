import {API_URL_V1, PREFIX} from "./constants.js";
import {renderMessage} from "./utils/message.js";

const editPostForm = document.querySelector("#edit-post");

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