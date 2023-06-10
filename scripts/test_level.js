var theme = "dark";
var langUI = "en";
var langData = "en";
var callback = "./games.php";
var user = "";
var levelId = "0";
var tabName = "";

function loadLevel() {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    console.log(urlParams);

    if(urlParams.has('name')) {
        tabName = urlParams.get('name');
    }

    if(urlParams.has('action')) {
        let action = urlParams.get('action');
        if(action != "play") {
            window.open(callback, tabName);
            window.close();
        }
    }
    else {
        window.open(callback, tabName);
        window.close();
    }

    if(urlParams.has('theme')) {
        theme = urlParams.get('theme');
    }
    
    if(urlParams.has('lang_UI')) {
        langUI = urlParams.get('lang_UI');
    }
    
    if(urlParams.has('lang_DATA')) {
        langData = urlParams.get('lang_DATA');
    }
    
    if(urlParams.has('callback_url')) {
        callback = urlParams.get('callback_url');
    }

    if(urlParams.has('user')) {
        user = urlParams.get('user');
    }

    if(urlParams.has('id')) {
        levelId = urlParams.get('id');
    }

    let goBackButton = document.getElementById('go-back');
    goBackButton.addEventListener('click', () => { window.open(callback + "?id=" + levelId + "&user=" + user + "&action=play&lock_result=none&is_return=true", tabName); window.close(); } );
    if(theme == "light") {
        document.body.style.backgroundImage = 'linear-gradient(45deg, #6190E8, #A7BFE8)';
        document.body.style.color = "black";
        goBackButton.style.backgroundColor = "#31303C";
    }
    else {
        document.body.style.backgroundImage = 'linear-gradient(45deg, #31303C, #08091C, #15162C, #343752, #3B2F37, #31303C, #08091C)';
        document.body.style.color = "white";
        goBackButton.style.backgroundColor = "#24909C";
        document.querySelector('input[name="answer"]').style.color = "white";
    }

    let text = document.createElement('p');
    let answer = document.createElement('p');
    let goBackButtonSpan = document.getElementById('go-back-text');
    if(langUI == "bg") {
        text.innerHTML = "Гатанка: В гора си. Има 4 изхода (Север, Юг, Изток и Запад). На север има черна дупка, която чака да те погълне. На запад има дупка в земята, която е твърде голяма, за да я прекосиш дори с въже. На юг има 3 гладни лъва, които не са яли от 3 месеца и чакат да те изядат. На изток има гигантска каменна плоча, която е твърде висока за изкачване и заема цялото пространство. Кой път ще избереш, за да избягаш?";
        goBackButtonSpan.innerHTML = "ВЪРНИ СЕ";
        answer.innerHTML = "Напишете вашият отговор на " + (langData == "bg" ? "български." : "английски.");
        document.querySelector('input[name="answer"]').placeholder = "Твоят отговор";
    }
    else {
        text.innerHTML = "Riddle : You're in a forest. There are 4 exits (North, South, East and West). In the north, there is a black hole waiting to swallow you up. In the west, there is a hole in the ground too big for you to cross, even by rope. In the south, there are 3 hungry lions th at haven't eaten for 3 months that are waiting to eat you. In the east, there is a giant stone slab that is too high to climb and it takes up the whole space. Which way do you go to escape ?";
        goBackButtonSpan.innerHTML = "GO BACK";
        answer.innerHTML = "Write your answer in " + (langData == "bg" ? "bulgarian." : "english.");
        document.querySelector('input[name="answer"]').placeholder = "Your answer";
    }

    document.body.appendChild(text);
    document.body.appendChild(answer);
}

function checkAnswer() {
    const answer = document.querySelector('input[name="answer"]').value;
    if((langData == "en" && answer == "south") || (langData == "bg" && answer == "юг")) {
        alert(langUI == "bg" ? "Правилен отговор" : "Correct answer");
        callback = callback + "?id=" + levelId + "&user=" + user + "&action=finished&lock_result=yes&is_return=true";
    }
    else {
        alert(langUI == "bg" ? "Грешен отговор" : "Wrong answer");
        callback = callback + "?id=" + levelId + "&user=" + user + "&action=finished&lock_result=no&is_return=true";
    }
    
    window.open(callback, tabName);
    window.close();
}


window.addEventListener('load', loadLevel);
document.getElementById('check-answer').addEventListener('click', checkAnswer);
