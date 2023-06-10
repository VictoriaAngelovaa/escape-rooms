import { post } from './api.js';
import levelTemplate from "../templates/level_template.json" assert { type: "json" };

async function onUpload(event) {
    event.preventDefault();

    let fileElement = document.getElementById('resource-file');

    // check if user had selected a file
    if (fileElement.files.length === 0) {
      alert('please choose a file');
      return;
    }

    let file = fileElement.files[0];
    if(!file.type.match('application/json')) {
        alert('please choose a json file');
        return;
    }

    var reader = new FileReader();
    reader.onload = async function() {
        var fileContent = reader.result;
        console.log(fileContent);
    
        try {
            const response = await sendLevelDataToServer(JSON.stringify(fileContent));
        
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
    };
    reader.readAsText(file); 
}

async function sendLevelDataToServer(data) {
    const response = await post('./../controllers/level.php', data);

    if (response.ok || response.status === 401) {
        return { body: response.body, status: response.status };
    } else {
        throw new Error();
    }
}

function showResourceName(event) {
    let file = event.currentTarget.files[0].name;
    let resourceName = document.getElementById('label-resource');
    resourceName.innerText = file;
}

function onDownload() {
    let dataStr = "data:text/json;charset=utf-8," + JSON.stringify(levelTemplate);
    let downloadAnchorNode = document.createElement('a');
    downloadAnchorNode.setAttribute("href",     dataStr);
    downloadAnchorNode.setAttribute("download", "level" + ".json");
    document.body.appendChild(downloadAnchorNode); // required for firefox
    downloadAnchorNode.click();
    downloadAnchorNode.remove(); 
}

document.querySelector('#upload-button').addEventListener('click', onUpload);
document.querySelector('#resource-file').addEventListener('change', showResourceName);
document.querySelector('#download-template-button').addEventListener('click', onDownload);
