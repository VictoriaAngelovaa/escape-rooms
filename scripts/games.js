import { get } from './api.js';


async function loadGames(_event) {
    const response = await get('./../controllers/game_config.php');
    const games = response.body;

    games.forEach(game => displayGame(game));
}

function displayGame(game) {
    const gameInfo = document.createElement('div');
    gameInfo.setAttribute("class", "info-card");
    document.getElementById('games-list').appendChild(gameInfo);

    const gameFig = document.createElement('figure');  
    gameInfo.appendChild(gameFig);

    const img = new Image();
    img.src = game.logo;
    img.setAttribute("alt", "Logo");
    img.setAttribute("width", "280");
    img.setAttribute("height", "280");

    const gameName = document.createElement('h2');
    gameName.innerHTML = game.name;

    const levelicon = new Image();
    levelicon.src = "./../views/images/level_icon.svg";
    levelicon.setAttribute("alt", "icon");
    levelicon.setAttribute("width", "25");
    levelicon.setAttribute("height", "25");

    const gameLevels = document.createElement('h3');
    gameLevels.innerHTML = "Levels   " + game.levelsCount;

    const wrapH3 = document.createElement('div');
    wrapH3.setAttribute("class", "levels-wrap");

    wrapH3.appendChild(gameLevels);
    wrapH3.appendChild(levelicon);

    const viewGame = document.createElement('button');
    viewGame.setAttribute("class", "generic-button view-button");
    const viewGameSpan = document.createElement('span');
    viewGameSpan.innerText = "VIEW GAME";

    viewGame.appendChild(viewGameSpan);
    viewGame.addEventListener('click', () => onViewGame(game.id));

    gameFig.appendChild(img);
    gameFig.appendChild(gameName);
    gameFig.appendChild(wrapH3);

    gameInfo.appendChild(viewGame);
}

function onViewGame(gameId) {
    sessionStorage.setItem("selected_game", gameId);
    window.location.href = `./../views/selected_game.php?id=${gameId}`;
}

window.addEventListener('load', async () => await loadGames());
