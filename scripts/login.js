import { post } from "./api.js";

async function onSubmit(event) {
  event.preventDefault();

  const username = event.target.querySelector('input[name="username"]').value;

  const password = event.target.querySelector('input[name="password"]').value;

  try {
    const response = await sendLoginDataToServer({
      username: username,
      password: password,
    });

    if (response.status === 200) {
      window.location.href = "./games.php";
    }else {
      console.log("something went wrong - unhandled");
    }
  } catch (_e) {
    setErrorMessage(_e.message)
  }
}

async function sendLoginDataToServer(data) {
  const response = await post("./../controllers/login.php", JSON.stringify(data));

  if (response.ok) {
    return { body: response.body, status: response.status };
  } else if (response.status === 401) {
    throw new Error("Невалидно потребителско име/парола");
  } else {
    throw new Error("Bъзникна грешка");
  }
}

function setErrorMessage(message) {
  const errorMessage = document.getElementById("error-message");

  errorMessage.innerHTML = message;
  errorMessage.style.display = "block";
}

document
  .querySelector("#login-container form")
  .addEventListener("submit", onSubmit);

document.getElementById("register-button").onclick = function () {
  window.location.href = "./register.php";
};
