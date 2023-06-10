import { post, get } from './api.js';

async function loadLevels(_event) {
    var gameId = sessionStorage.getItem("selected_game");
    console.log(gameId);
    // window.open('https://www.w3schools.com/html/html5_semantic_elements.asp');
    const response = await get(`./../controllers/add_levels.php?id=${gameId}&user=${user}`);
    const levels = response.body;

    levels.forEach(level => displayLevel(level));

    if(levels.length > 0) {
        const doneButton = document.createElement('button');
        doneButton.setAttribute("class", "generic-button");
        const doneButtonSpan = document.createElement('span');
        doneButtonSpan.innerText = "DONE";
        doneButton.appendChild(doneButtonSpan);

        doneButton.addEventListener('click', () => onAddLevelsClick(gameId));

        document.getElementById('levels-list').appendChild(doneButton);
    }
    else {
        const noLevels = document.createElement('h2');
        noLevels.innerHTML = "Няма налични нива.";

        document.getElementById('levels-list').appendChild(noLevels);
    }
}

async function onAddLevelsClick(gameId) {
    let checkboxes = document.querySelectorAll('input[name="accept"]:checked');
    let values = [];
    checkboxes.forEach((checkbox) => {
        values.push(checkbox.value);
    });

    const response = await post('./../controllers/add_levels.php', JSON.stringify({gameId: gameId, levels: values}));

    if (response.status === 200) {
        console.log(response.body);

        window.location.href = `./../views/selected_game.php`;
    }
    else if (response.status === 401) {
        console.log(response.body);
    }
    else {
        console.log('Something went wrong');
    }

    return;
}

function displayLevel(level) {
    
    const levels = document.getElementsByClassName("levels")[0];
    const levelFig = document.createElement('figure');  

    levels.appendChild(levelFig);

    const levelLogo = new Image();
    levelLogo.src = level.logo;
    levelLogo.setAttribute("alt", "Logo");
    levelLogo.setAttribute("width", "125");
    levelLogo.setAttribute("height", "125");

    const levelName = document.createElement('h2');
    levelName.innerHTML = level.name;

    const lock = document.createElement('h3');
    lock.innerHTML = "Lock: " + level.lockType;

    levelFig.appendChild(levelLogo);
    levelFig.appendChild(levelName);
    levelFig.appendChild(lock);

    const buttonWrap = document.createElement('div');  
    buttonWrap.setAttribute("class", "ck-button");

    levelFig.appendChild(buttonWrap);

    const label = document.createElement('label'); 
    buttonWrap.appendChild(label); 

    const checkbox = document.createElement('input');  
    checkbox.type = "checkbox";
    checkbox.name = "accept";
    checkbox.value = level.id;
    label.appendChild(checkbox);

    const span = document.createElement('span');
    span.innerText = "SELECT";
    checkbox.appendChild(span);
}

window.addEventListener('load', async () => await loadLevels());
