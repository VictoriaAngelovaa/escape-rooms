import { get } from './api.js';

async function loadGame(_event) {
    var gameId = sessionStorage.getItem("selected_game");
    const response = await get(`./../controllers/game_config.php?id=${gameId}`);
    const game = response.body;

    displayGame(game);
}

function displayGame(game) {
    console.log(game);
    const gameInfo = document.createElement('div');
    gameInfo.setAttribute("id", "selected-game");
    document.getElementById('game-info').appendChild(gameInfo);

    let levels = null;
    if(game.levels.length > 0) {
        levels = document.createElement('div');
        levels.setAttribute("class", "levels");
        document.getElementById('game-info').appendChild(levels);
    }

    const gameLogo = new Image();
    gameLogo.src = game.logo;
    gameLogo.setAttribute("alt", "Logo");
    gameLogo.setAttribute("width", "350");
    gameLogo.setAttribute("height", "350");

    const gameName = document.createElement('h2');
    gameName.innerHTML = game.name;

    const gameLevelsCount = document.createElement('h3');
    gameLevelsCount.innerHTML = "Levels: " + game.levelsCount;

    const buttonContainer = document.createElement('div');
    buttonContainer.setAttribute("class", "button-container");

    const download = document.createElement('button');
    download.setAttribute("class", "generic-button");
    const downloadSpan = document.createElement('span');
    downloadSpan.innerText = "EXPORT";

    download.appendChild(downloadSpan);
    download.addEventListener('click', () => onDownload(game))

    const addLevelsButton = document.createElement('button');
    addLevelsButton.setAttribute("class", "generic-button");
    const addLevelsButtonSpan = document.createElement('span');
    addLevelsButtonSpan.innerText = "ADD LEVELS";

    addLevelsButton.appendChild(addLevelsButtonSpan);
    addLevelsButton.addEventListener('click', () => onAddLevelsClick(game.id));

    buttonContainer.appendChild(download)
    buttonContainer.appendChild(addLevelsButton)

    gameInfo.appendChild(gameLogo);
    gameInfo.appendChild(gameName);
    gameInfo.appendChild(gameLevelsCount);
    gameInfo.appendChild(buttonContainer)

    game.levels.forEach( level => 
    {
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

        if(level.public || level.user === user) {
            levelFig.setAttribute("class", "clickable");
            levelFig.addEventListener('click', () => showLevel(level.id));
        }
        else {
            levelFig.style.opacity = "0.6";
        }
    });
}

function onAddLevelsClick(gameId) {
    sessionStorage.setItem("selected_game", gameId);
    window.location.href = `./../views/select_levels.php?id=${gameId}`;
}

function showLevel(levelId) {
    sessionStorage.setItem("selected_level", levelId);
    window.location.href = `./../views/selected_level.php?id=${levelId}`;
}

async function onDownload(game){
    let copy = {...game};
    delete copy.id;
    delete copy.levelsCount;
    copy.levels = copy.levels.filter(level => level.public && level.config_pass === "");
    copy.levels.forEach(level => {delete level.id; 
                                 delete level.user;
                                 level.config_pass = "";})

    let dataStr = "data:text/json;charset=utf-8," + JSON.stringify(copy);
    let downloadAnchorNode = document.createElement('a');
    downloadAnchorNode.setAttribute("href",     dataStr);
    downloadAnchorNode.setAttribute("download", "configuration" + ".json");
    document.body.appendChild(downloadAnchorNode); // required for firefox
    downloadAnchorNode.click();
    downloadAnchorNode.remove();
}


window.addEventListener('load', async () => await loadGame());
