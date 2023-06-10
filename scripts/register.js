import { post } from "./api.js";

async function onSubmit(event) {
  event.preventDefault();

  const username = event.target.querySelector('input[name="username"]').value;

  const password = event.target.querySelector('input[name="password"]').value;

  const verifyPassword = event.target.querySelector(
    'input[name="verify-password"]'
  ).value;

  if (password !== verifyPassword) {
    setErrorMessage("Паролите не съвпадат");
    return;
  }

  try {
    const response = await sendLoginDataToServer({
      username,
      password,
    });

    if (response.status === 200) {
        window.location.href = "./login.php";
    } else {
      console.log("something went wrong - unhandled");
    }
  } catch (_e) {
    setErrorMessage(_e.message);
  }
}

async function sendLoginDataToServer(data) {
  const response = await post("./../controllers/register.php", JSON.stringify(data));

  if (response.ok) {
    return { body: response.body, status: response.status };
  } else if (response.status === 409) {
    throw new Error("Потребителското име вече е заето");
  } else {
    throw new Error("Възникна грешка");
  }
}

function setErrorMessage(message) {
  const errorMessage = document.getElementById("error-message");

  errorMessage.innerHTML = message;
  errorMessage.style.display = "block";
}

document
  .querySelector("#register-container form")
  .addEventListener("submit", onSubmit);

document.getElementById("already-signed-up-button").onclick = function () {
  window.location.href = "./login.php";
};
