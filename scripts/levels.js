import { post, get } from './api.js';


async function loadLevels(_event) {
    const response = await get('./../controllers/level.php');
    const games = response.body;

    games.forEach(game => displayLevel(game));
}

function displayLevel(game) {
    const gameInfo = document.createElement('div');
    gameInfo.setAttribute("class", "info-card");
    document.getElementById('games-list').appendChild(gameInfo);

    const publicSign = document.createElement('label');
    publicSign.setAttribute("class", "public-sign");
    publicSign.innerHTML = game.public ? "PUBLIC" : "PRIVATE";
    gameInfo.appendChild(publicSign);

    const gameFig = document.createElement('figure');  
    gameInfo.appendChild(gameFig);

    const img = new Image();
    img.src = game.logo;
    img.setAttribute("alt", "Logo");
    img.setAttribute("width", "200");
    img.setAttribute("height", "200");

    const gameName = document.createElement('h2');
    gameName.innerHTML = game.name;

    const wrapH3 = document.createElement('div');
    wrapH3.setAttribute("class", "headings-wrap");

    const type = document.createElement('h3');
    type.innerHTML = "Type:   " + game.type;

    const lock = document.createElement('h3');
    lock.innerHTML = "Lock Type:   " + game.lockType;

    const category = document.createElement('h3');
    category.innerHTML = "Category:   " + game.category;

    wrapH3.appendChild(type);
    wrapH3.appendChild(category);
    wrapH3.appendChild(lock);

    const viewGame = document.createElement('button');
    viewGame.setAttribute("class", "generic-button");
    const viewGameSpan = document.createElement('span');
    viewGameSpan.innerText = "VIEW LEVEL";

    viewGame.appendChild(viewGameSpan);

    if(game.public || game.user == user) {
        viewGame.addEventListener('click', () => onViewlLevel(game.id));
    }
    else {
        viewGame.style.opacity = 0.4;
    }

    gameFig.appendChild(img);
    gameFig.appendChild(gameName);
    gameFig.appendChild(wrapH3);

    gameInfo.appendChild(viewGame);
}

function onViewlLevel(levelId) {
    sessionStorage.setItem("selected_level", levelId);
    window.location.href = `./../views/selected_level.php?id=${levelId}`;
}

function checkRegexProperty(property) {
    const keys = new Array("language", "category", "theme", "public", "name", "lock_type", "type", "all"); 
    for(const key of keys) {
        if(key.includes(property)){
            return key;
        }
    }

    return undefined;
}

async function onFilter() {
    const filter = document.querySelector('input[name="category"]').value;
    
    let response = null;
    if(filter == "all") {
        response = await get('./../controllers/level.php');
    }
    else {
        const filterBy = filter.substring(0, filter.indexOf(':'));
        const value = filter.substring(filter.indexOf(':') + 1).trimStart();
        const property = checkRegexProperty(filterBy);

        response = await get(`./../controllers/level.php?filter=${property}&value=${value}`);
    }

    document.querySelector('input[name="category"]').value = '';

    const games = response.body;

    const levels = document.querySelectorAll('.info-card');
    const headings = document.querySelectorAll('#games-list h2');

    headings.forEach(heading => {
        heading.remove();
    });

    levels.forEach(level => {
        level.remove();
    });

    if(games.length == 0) {
        const noLevels = document.createElement('h2');
        noLevels.innerHTML = "Няма налични нива.";

        document.getElementById('games-list').appendChild(noLevels);

        return;
    }

    games.forEach(game => displayLevel(game));
}

window.addEventListener('load', async () => await loadLevels());
document.getElementById("search-button").addEventListener('click', onFilter);
