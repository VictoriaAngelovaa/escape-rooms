import { post } from "./api.js";

async function onSubmit(event) {
  event.preventDefault();
  const form = event.currentTarget;

  let formData = new FormData(form);

  try {
    const response = await sendLevelDataToServer(formData);

    if (response.status === 200) {
      console.log(response.body);

      const alert = document.getElementById("popup");
      alert.style.visibility = "visible";
      alert.innerHTML = response.body.message;

      setTimeout(() => {
        alert.style.visibility = "hidden";
        sessionStorage.setItem("selected_game", response.body.game.id);
        window.location.href = `./../views/selected_game.php?id=${response.body.game.id}`;
      }, 1500);
    } else if (response.status === 401) {
      console.log(response.body);
    } else {
      console.log("Something went wrong");
    }
  } catch (e) {
    console.log(e.name);
    console.log(e.message);
  }
}

async function sendLevelDataToServer(data) {
  const response = await post("./../controllers/game_config.php", data);

  if (response.ok || response.status === 401) {
    return { body: response.body, status: response.status };
  } else {
    throw new Error();
  }
}

function showImage(event) {
  let file = event.currentTarget.files[0];

  let logo = document.getElementById("logo-image");

  logo.src = URL.createObjectURL(file);
  console.log(logo.src);
}

document.querySelector("#create-level-form").addEventListener("submit", onSubmit);
document.querySelector("#logo-file").addEventListener("change", showImage);
