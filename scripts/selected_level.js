import { post, get } from './api.js';

window.name = "level";

var levelId = 0;

async function loadLevel(_event) {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    let response = null;
    if(urlParams.has('is_return')) {
      response = await get("./../controllers/level.php" + queryString);
      const level = response.body.level;

      displayLevel(level);

      if(response.body.hasOwnProperty('message')) {
        const alert = document.getElementById('popup');
        alert.style.visibility = 'visible';
        alert.innerHTML = response.body.message;

        setTimeout(async () => {  alert.style.visibility = 'hidden'; 
                                  window.location.href = `./../views/selected_level.php?id=${level.id}`;
                               }, 3500);
      }
    }
    else if(urlParams.has('user') && urlParams.get('user') === "guest") {
      response = await get("./../controllers/level.php" + queryString);
      const level = response.body.level;

      displayLevel(level);
    }
    else {
      levelId = sessionStorage.getItem("selected_level");
      if(levelId == null) {
        window.location.href = `./../views/login.php`;
      }
      response = await get(`./../controllers/level.php?id=${levelId}`);
      const level = response.body.level;

      displayLevel(level);
    }
}

function displayLevel(level) {
  if(level.check_answer) {
   const answer = document.createElement('button');
   answer.type = "button";
   answer.setAttribute("class", "material-button");

   const spanAnswer = document.createElement('span');
   spanAnswer.setAttribute("class", "material-icons");
   spanAnswer.innerHTML = "question_mark";

   answer.appendChild(spanAnswer);
   answer.addEventListener('click', () => checkAnswer(level));
   
   const answerInput = document.createElement('input');
   answerInput.setAttribute("class", "generic-input");
   answerInput.type = "text";
   answerInput.name = "answer";
   answerInput.placeholder = "Your answer";
   
   document.getElementsByClassName('timer')[0].appendChild(answerInput);
   document.getElementsByClassName('timer')[0].appendChild(answer);
  }

   const selectedLevel = document.createElement('div');
   selectedLevel.setAttribute("id", "selected-level");
   document.getElementById('level-info').appendChild(selectedLevel);

   const logo = new Image();
   logo.src = level.logo;
   logo.setAttribute("alt", "Logo");
   logo.setAttribute("width", "300");
   logo.setAttribute("height", "300");

   const levelName = document.createElement('h2');
   levelName.innerHTML = level.name;

   const buttons = document.createElement('div');
   buttons.setAttribute("class", "button-container");

   const startLevel = document.createElement('button');
   startLevel.setAttribute("class", "generic-button");
   const startLevelSpan = document.createElement('span');
   startLevelSpan.innerText = "START";

   startLevel.appendChild(startLevelSpan);
   startLevel.addEventListener('click', () => onStart(level));

   const openLevel = document.createElement('button');
   openLevel.setAttribute("class", "generic-button");
   const openLevelSpan = document.createElement('span');
   openLevelSpan.innerText = "STORY";

   openLevel.appendChild(openLevelSpan);
   openLevel.addEventListener('click', () => onOpen(level));
   buttons.appendChild(openLevel);

   buttons.appendChild(startLevel);

   selectedLevel.appendChild(logo);
   selectedLevel.appendChild(levelName);
   selectedLevel.appendChild(buttons);

   const levelInfo = document.createElement('div');
   levelInfo.setAttribute("id", "level-full-info");
   document.getElementById('level-info').appendChild(levelInfo);

   const heading = document.createElement('h2');
   heading.innerHTML = "Level Info";

   const desc = document.createElement('p');
   desc.innerHTML = level.description;

   const attempts = document.createElement('p');
   attempts.innerHTML = "Attempts: " + level.attempts;

   const lock = document.createElement('p');
   lock.innerHTML = "Lock Type: " + level.lockType;

   const type = document.createElement('p');
   type.innerHTML = "Type: " + level.type;

   const category = document.createElement('p');
   category.innerHTML = "Category: " + level.category;

   const points = document.createElement('p');
   points.innerHTML = "Points: " + level.points;

   const duration = document.createElement('p');
   duration.innerHTML = "Duration: " + level.duration + " mins";

   const theme = document.createElement('p');
   theme.innerHTML = "Themes: " + level.theme;

   const language = document.createElement('p');
   language.innerHTML = "Languages: " + level.language;
       
   levelInfo.appendChild(heading);
   levelInfo.appendChild(desc);
   levelInfo.appendChild(lock);
   levelInfo.appendChild(type);
   levelInfo.appendChild(category);
   levelInfo.appendChild(attempts);
   levelInfo.appendChild(points);
   levelInfo.appendChild(duration);
   levelInfo.appendChild(theme);
   levelInfo.appendChild(language);

   const buttonsEx = document.createElement('div');
   buttonsEx.setAttribute("class", "button-container");
   buttonsEx.setAttribute("id", "material-container");

   if(level.show_config || user == level.user) {
     const download = document.createElement('button');
     download.type = "button";
     download.setAttribute("class", "material-button big-material-button");
     const downloadSpan = document.createElement('span');
     downloadSpan.setAttribute("class", "material-icons");
     downloadSpan.innerText = "download";

     download.appendChild(downloadSpan);
     download.addEventListener('click', () => onDownload(level));

     buttonsEx.appendChild(download);
   }

   const share = document.createElement('button');
   share.type = "button";
   share.setAttribute("class", "material-button big-material-button");
   share.style.marginLeft = "1rem";
   const shareSpan = document.createElement('span');
   shareSpan.setAttribute("class", "material-icons");
   shareSpan.innerText = "share";

   share.appendChild(shareSpan);
   share.addEventListener('click', () => onShare());

   buttonsEx.appendChild(share);

   levelInfo.appendChild(buttonsEx);

   if(user == level.user) {
     const edit = document.createElement('button');
     edit.type = "button";
     edit.setAttribute("class", "material-button big-material-button");
     edit.style.marginLeft = "1rem";
     const editSpan = document.createElement('span');
     editSpan.setAttribute("class", "material-icons");
     editSpan.innerText = "edit";

     edit.appendChild(editSpan);
     edit.addEventListener('click', () => onEdit(level));

     buttonsEx.appendChild(edit);
   }
}

function onStart(level) {
    level.open_url = level.type == "Online" ? level.open_url + "&id=" + level.id + "&user=" + user + "&name=" + window.name : level.open_url;
    new Timer(document.querySelector(".timer"), level).start();
}

function onOpen(level) {
  window = open(level.resource, '_blank');
}

function onDownload(level){
    if(level.config_pass != "" && user != level.user) {
      let pass = prompt("Please enter password to export the level!");

      if(pass == null || pass != level.config_pass) {
        alert("Wrong password!");
        return;
      }
    }

    let copy = {...level};
    delete copy.id;
    delete copy.user;
    copy.config_pass = "";
    let dataStr = "data:text/json;charset=utf-8," + JSON.stringify(copy);
    let downloadAnchorNode = document.createElement('a');
    downloadAnchorNode.setAttribute("href",     dataStr);
    downloadAnchorNode.setAttribute("download", "configuration" + ".json");
    document.body.appendChild(downloadAnchorNode); // required for firefox
    downloadAnchorNode.click();
    downloadAnchorNode.remove();
}

function onEdit(level) {
  const edit = document.createElement('textarea');

  let copy = {...level};
  delete copy.id;
  delete copy.user;

  edit.value = JSON.stringify(copy, null, 2);
  edit.setAttribute("rows", "20");
  edit.setAttribute("cols", "50");

  const save = document.createElement('button');
  save.type = "button";
  save.setAttribute("class", "material-button big-material-button");
  const saveSpan = document.createElement('span');
  saveSpan.setAttribute("class", "material-icons");
  saveSpan.innerText = "save";

  save.appendChild(saveSpan);
  save.addEventListener('click', async () => await onSave(edit, level.id));

  document.querySelector("#level-full-info").innerHTML = '';

  document.querySelector("#level-full-info").appendChild(edit);
  document.querySelector("#level-full-info").appendChild(save);
}

async function onSave(edit, levelId) {
  let postData = JSON.parse(edit.value);
  postData.id = levelId;
  console.log(postData);

  const response = await post('./../controllers/level.php', JSON.stringify({update: "true", level: postData}));

  if (response.status === 200) {
    console.log(response.body.level);
    const alert = document.getElementById('popup');
    alert.style.visibility = 'visible';
    alert.innerHTML = response.body.message;

    setTimeout(() => { 
        alert.style.visibility = 'hidden';
        if(response.body.message == 'Success.') {
            sessionStorage.setItem("selected_level", response.body.level.id);
            window.location.href = `./../views/selected_level.php?id=${response.body.level.id}`;
        }
    }, 1000);
  }
  else if (response.status === 401) {
      console.log(response.body);
  }
  else {
      console.log('Something went wrong');
  }
}

function onShare() {
  navigator.clipboard.writeText(new URL(window.location.href + "&user=guest"));
  alert("The link was copied to your clipboard.");
}

async function checkAnswer(level) {
  const answer = document.querySelector('input[name="answer"]').value;

  const alert = document.getElementById('popup');
  if(answer == level.answer) {
      
      const response = await post('./../controllers/level.php', JSON.stringify({ id: new Number(level.id), 
                                                                                 answer: new Boolean(true), 
                                                                                 user: new String(user)}));
      alert.innerHTML = response.body.message;
  }
  else {
      alert.innerHTML = "Wrong answer! Try again.";
  }

  alert.style.visibility = 'visible';
  setTimeout(() => { 
    alert.style.visibility = 'hidden'; 
  }, 2000);
}

class Timer {   
    constructor(root, level) {
      this.el = {
        hours: root.querySelector(".timer__part--hours"),
        minutes: root.querySelector(".timer__part--minutes"),
        seconds: root.querySelector(".timer__part--seconds"),
        control: root.querySelector(".timer__btn--control")
      };
  
      this.interval = null;
      this.remainingSeconds = level.duration*60;
      this.url = level.open_url;
  
      this.el.control.addEventListener("click", () => {
        if (this.interval === null) {
          this.start();
        } else {
          this.stop();
        }
      });

      this.window = open(this.url, '_blank');
    }
  
    updateInterfaceTime() {
      const seconds = this.remainingSeconds % 60;
      const minutes = Math.floor(this.remainingSeconds / 60 % 60);
      const hours = Math.floor(this.remainingSeconds / 60 / 60);
  
      this.el.hours.textContent = hours.toString().padStart(2, "0");
      this.el.minutes.textContent = minutes.toString().padStart(2, "0");
      this.el.seconds.textContent = seconds.toString().padStart(2, "0");
    }
  
    updateInterfaceControls() {
      if (this.interval === null) {
        this.el.control.innerHTML = `<span class="material-icons">play_arrow</span>`;
        this.el.control.classList.add("timer__btn--start");
        this.el.control.classList.remove("timer__btn--stop");
      } else {
        this.el.control.innerHTML = `<span class="material-icons">pause</span>`;
        this.el.control.classList.add("timer__btn--stop");
        this.el.control.classList.remove("timer__btn--start");
      }
    }
  
    start() {
      if (this.remainingSeconds === 0) return;
  
      this.interval = setInterval(() => {
        this.remainingSeconds--;
        this.updateInterfaceTime();
  
        if (this.remainingSeconds === 0) {
            this.stop();
            this.window.close();

            this.el.control.innerHTML = `<span class="material-icons">lock_clock</span>`;
            this.el.control.classList.add("timer__btn--stop");
            this.el.control.classList.remove("timer__btn--start");

            const alert = document.getElementById('popup');
            alert.style.visibility = 'visible';
            alert.innerHTML = "Времето изтече. Опитай пак!";

            setTimeout(() => { alert.style.visibility = 'hidden'; }, 2500);
        }
      }, 1000);
  
      this.updateInterfaceControls();
    }
  
    stop() {
      clearInterval(this.interval);
  
      this.interval = null;
  
      this.updateInterfaceControls();
    }
}

window.addEventListener('load', async () => await loadLevel());
