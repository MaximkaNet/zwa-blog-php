import {API_URL_V1, PREFIX} from "./constants.js";
import {FileValidator, Validator} from "./utils/validator/validator.js";
import {renderMessage} from "./utils/message.js";

const profileForm = document.querySelector("#profile-form");

const editFirstNameBtn = document.querySelector("#edit-first-name");
const firstNameField = document.querySelector("#first_name");
const editLastNameBtn = document.querySelector("#edit-last-name");
const lastNameField = document.querySelector("#last_name");

function removeFileFromAvatarInput() {
    const dt = new DataTransfer()
    const input = document.getElementById('avatar-file')
    const {files} = input
    for (let i = 0; i < files.length; i++) {
        const file = files[i]
        if (0 !== i)
            dt.items.add(file) // here you exclude the file. thus removing it.
    }
    input.files = dt.files // Assign the updates list
}

async function sendData({user_id, data}) {
    const fetchOptions = {
        method: "POST",
        body: data
    };
    const response = await fetch(API_URL_V1 + "/users/" + user_id + "/edit", fetchOptions);
    return await response.json();
}

async function submitForm(e) {
    e.preventDefault();
    // Validation
    const formData = new FormData(e.target);
    const avatar = formData.get("avatar");
    if (avatar.name.length !== 0) {
        const avatar_validation = {
            type: FileValidator.type(avatar),
            size: FileValidator.size(avatar)
        };
        if (avatar_validation.type.isNotValid() || avatar_validation.size.isNotValid()) {
            if (avatar_validation.type.isNotValid()) {
                renderMessage("error", "Invalid file type");
            }
            if (avatar_validation.size.isNotValid()) {
                renderMessage("error", "Too big file. Max ~10kb");
            }
            formData.delete("avatar");
            removeFileFromAvatarInput();
            return;
        }
    }

    const names_validation = {
        first_name: Validator.name(formData.get("first_name"), "First name"),
        last_name: Validator.name(formData.get("last_name"), "Last name"),
    }

    if (names_validation.first_name.isNotValid()) {
        renderMessage("error", names_validation.first_name.getMessage());
        return;
    }
    if (names_validation.last_name.isNotValid()) {
        renderMessage("error", "Last name is not valid");
        return;
    }
    // Send data
    const {errors, data, message} = await sendData({
        user_id: formData.get("id"),
        data: formData
    });
    if (errors.length === 0) {
        renderMessage("success", message);
        if (data.avatar !== undefined) {
            document.querySelector("#avatar-file_name").innerText = data.avatar;
            const pathToAvatar = PREFIX + "/static/users/" + data.avatar;
            document.querySelector("#avatar").setAttribute("src", pathToAvatar);
        }
        if (data.first_name !== undefined) {
            document.querySelector("#first_name").value = data.first_name;
        }
        if (data.last_name !== undefined) {
            document.querySelector("#last_name").value = data.last_name;
        }
        removeFileFromAvatarInput();
    } else {
        errors.map(({message}) => {
            renderMessage("error", message);
        })
    }
}

profileForm.addEventListener("submit", submitForm);

function setAttributesForBtn(btn, {alt, src}) {
    btn.setAttribute("alt", alt);
    btn.setAttribute("src", src);
}

function toggleReadonly(input, btn) {
    if (input.hasAttribute("readonly")) {
        input.removeAttribute("readonly");
        setAttributesForBtn(btn, {
            alt: "complete",
            src: PREFIX + "/assets/images/save.svg"
        });
    } else {
        input.setAttribute("readonly", "");
        setAttributesForBtn(btn, {
            alt: "edit",
            src: PREFIX + "/assets/images/pen.png"
        });
    }
}

async function resolveFirstName(e) {
    // Toggle readonly
    toggleReadonly(firstNameField, editFirstNameBtn);
}

async function resolveLastName(e) {
    // Toggle readonly
    toggleReadonly(lastNameField, editLastNameBtn);
}

editFirstNameBtn.addEventListener("click", resolveFirstName);
editLastNameBtn.addEventListener("click", resolveLastName);
