import { post, get } from './api.js';

async function onSubmit(event) {
    event.preventDefault();

    // add check if user had selected a file

    const form = event.currentTarget;

    let formData = new FormData(form);

    console.log(formData);

    try {
        const response = await sendLevelDataToServer(formData);

        if (response.status === 200) {
            console.log(response.body);

            const alert = document.getElementById('popup');
            alert.style.visibility = 'visible';
            alert.innerHTML = response.body.message;

            setTimeout(() => { 
                alert.style.visibility = 'hidden';
                if(response.body.message == 'Success.') {
                    sessionStorage.setItem("selected_level", response.body.level.id);
                    window.location.href = `./../views/selected_level.php?id=${response.body.level.id}`;
                }
            }, 1500);
        }
        else if (response.status === 401) {
            console.log(response.body);
        }
        else {
            console.log('Something went wrong');
        }
    }
    catch (e) {
        console.log(e.name);
        console.log(e.message);
    }
}

async function sendLevelDataToServer(data) {
    const response = await post('./../controllers/level.php', data);

    if (response.ok || response.status === 401) {
        return { body: response.body, status: response.status };
    } else {
        throw new Error();
    }
}

function showImage(event) {
    let file = event.currentTarget.files[0];

    let logo = document.getElementById('logo-image');
    
    logo.src = URL.createObjectURL(file);
    console.log(logo.src);
}

function showResourceName(event) {
    let file = event.currentTarget.files[0].name;
    let resourceName = document.getElementById('label-resource');
    resourceName.innerText = file;
}

document.querySelector('#create-level-form').addEventListener('submit', onSubmit);
document.querySelector('#logo-file').addEventListener('change', showImage);
document.querySelector('#resource-file').addEventListener('change', showResourceName);
