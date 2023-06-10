import { get } from './api.js';

async function loadUser() {
    const response = await get(`./../controllers/user.php?user=${user}`);
    const userlevels = response.body;
    console.log(userlevels);

    displayUser(userlevels);
}

function displayUser(userlevels) {
    let totalPoints = 0;
    let levelsFinished = userlevels.length;
    userlevels.forEach(level => {totalPoints += level.points;});

    const heading = document.createElement('h2');
    heading.innerHTML = "User Info";

    const username = document.createElement('p');
    username.innerHTML = "Username: " + user;

    const points = document.createElement('p');
    points.innerHTML = "Collected Points: " + totalPoints;

    const levelsCount = document.createElement('p');
    levelsCount.innerHTML = "Finished Levels: " + levelsFinished;

    document.getElementById('user-info').appendChild(heading);
    document.getElementById('user-info').appendChild(username);
    document.getElementById('user-info').appendChild(points);
    document.getElementById('user-info').appendChild(levelsCount);

    let levels = null;
    if(userlevels.length > 0) {
        levels = document.createElement('div');
        levels.setAttribute("class", "levels");
        document.querySelector('main').appendChild(levels);
    }

    userlevels.forEach( level => 
    {
        const levelFig = document.createElement('figure');  
        levelFig.setAttribute("class", "clickable");
    
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

        levelFig.addEventListener('click', () => showLevel(level.id));
    });
}

function showLevel(levelId) {
    sessionStorage.setItem("selected_level", levelId);
    window.location.href = `./../views/selected_level.php?id=${levelId}`;
}

window.addEventListener('load', async () => await loadUser());
