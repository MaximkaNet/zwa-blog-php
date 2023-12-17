import { handleForm } from "./auth";

const signupForm = document.getElementById("signup_form")
const loginForm = document.getElementById("login_form")
const logoutForm = document.getElementById("logout_form")

if(loginForm)
    handleForm({
        url: "http://zwa-blog/login",
        redirect: "./",
        form: loginForm
    })
else if(logoutForm)
    handleForm({
        url: "http://zwa-blog/logout",
        redirect: "./login",
        form: logoutForm
    })
else if (signupForm)
    handleForm({
        url: "http://zwa-blog/signup",
        redirect: "./login",
        form: signupForm
    })