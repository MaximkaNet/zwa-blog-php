import {API_URL_V1, PREFIX} from "./constants.js";
import {renderMessage} from "./utils/message.js";

async function sendData({post_id}) {
    const response = await fetch(API_URL_V1 + `/posts/${post_id}/delete`, {method: "post"});
    return await response.json();
}

const articles = document.querySelectorAll(".posts-item");
const articlesContainer = document.querySelector(".posts");

articles.forEach((value) => {
    const item = value;
    if (value.children.length === 0) {
        return;
    }
    const actions = value.children[1];
    if (actions.children.length === 0) {
        return;
    }
    const deleteBtn = actions.children[1];
    deleteBtn.addEventListener("click", async (e) => {
        const post_id = Number(e.target.dataset.postId);
        if (isNaN(post_id)) {
            console.error("Post id is not a number");
            renderMessage("error", "Post id is not a number");
            return;
        }
        const {errors, data, message} = await sendData({
            post_id
        });
        if (errors.length === 0) {
            renderMessage("success", message ? message : "Changes saved");
            deleteBtn.removeEventListener("click", () => {})
            item.remove();
            console.log(articlesContainer.children.length);
            if (articlesContainer.children.length === 0) {
                const notFoundPosts = document.createElement("div");
                notFoundPosts.classList.add("posts-item", "not-found", "content-wrapper");
                notFoundPosts.innerText = "Posts not found";
                articlesContainer.append(notFoundPosts);
            }
        } else {
            errors.map((el) => {
                renderMessage("error", el.message);
            });
        }
    });
})