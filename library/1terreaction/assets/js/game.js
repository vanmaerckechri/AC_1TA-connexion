let currentTheme;
let bonusGameAlreadyPlayed = [false, false];
// SCORES
let displayScoresBar = function()
{
    if (document.getElementById("step_scores"))
    {
        let scoresThisGame = document.querySelectorAll(".scoresThisGameContainer div");
        let scoresAllThemesContainer = document.querySelectorAll(".scoresAllThemesContainer div");
        let scoresThisPlanetContainer = document.querySelectorAll(".scoresThisPlanetContainer div");
        let scoresList = [scoresThisGame, scoresAllThemesContainer, scoresThisPlanetContainer];
        for (let j = scoresList.length - 1; j >= 0; j--)
        {
            for (let i = scoresList[j].length - 1; i >= 0; i--)
            {
                let fluidForScore = document.createElement("span");
                fluidForScore.setAttribute("class", "fluidForScore");
                scoresList[j][i].appendChild(fluidForScore);

                let score = scoresList[j][i].innerText * 50;
                scoresList[j][i].style.color = "transparent";

                fluidForScore.style.width = score + "%";
            }
        }
        document.getElementById("mainMenuContainer").classList.add("disabled_v2");
        let closeButton = document.getElementById("scoresContainerClose");
        closeButton.addEventListener("click", function()
        {
            document.getElementById("mainMenuContainer").classList.toggle("disabled_v2");
            document.getElementById("step_scores").remove();
        }, false);
    }
}
// -- DISPLAY THEMES MENU --
// Hide
let closeThemesMenu = function()
{
    let step_chooseThemeContainer = document.querySelector("#step_chooseTheme");
    step_chooseThemeContainer.classList.add("disabled");		
    document.body.onclick = null;
}
// Save Answer
let sendToDb = function()
{
    let cleanReplies = [];
    let statsEnv = [];
    let statsSan = [];
    let statsSo = [];
    let cycleShift = 0;
    currentTheme = currentTheme.slice(0, 1) + 1;
    updateQuiz(currentTheme);

    let formReplies = document.createElement("form");
    formReplies.setAttribute("method", "post");
    formReplies.setAttribute("action", "index.php");

    for (let i = 0, length = answerList.length; i < length; i++)
    {
        let index = cycleShift + questionList[i] - 1;
        cleanReplies[index] = answerList[i];
        if (i == 2 || i == 5)
        {
            currentTheme = currentTheme[0] + ((1 * currentTheme[1]) + 1);
            updateQuiz(currentTheme);
            cycleShift += 3;
        }
        statsEnv[index] = quiz["question0"+questionList[i]]["proposition0"+answerList[i]]["stats_environnement"];
        statsSan[index] = quiz["question0"+questionList[i]]["proposition0"+answerList[i]]["stats_sante"];
        statsSo[index] = quiz["question0"+questionList[i]]["proposition0"+answerList[i]]["stats_social"];

    }
    cleanReplies.push(document.getElementById("openQuestionTextArea").value);
    cleanReplies.push(currentTheme.slice(0, 1));
    let inputReply = document.createElement("input");
    inputReply.setAttribute("type", "hidden");
    inputReply.setAttribute("name", "cleanReplies");
    inputReply.setAttribute("value", JSON.stringify(cleanReplies));
    formReplies.appendChild(inputReply);

    let inputStatsEnv = document.createElement("input");
    inputStatsEnv.setAttribute("type", "hidden");
    inputStatsEnv.setAttribute("name", "statsEnv");
    inputStatsEnv.setAttribute("value", JSON.stringify(statsEnv));
    formReplies.appendChild(inputStatsEnv);

    let inputStatsSan = document.createElement("input");
    inputStatsSan.setAttribute("type", "hidden");
    inputStatsSan.setAttribute("name", "statsSan");
    inputStatsSan.setAttribute("value", JSON.stringify(statsSan));
    formReplies.appendChild(inputStatsSan);

    let inputStatsSo = document.createElement("input");
    inputStatsSo.setAttribute("type", "hidden");
    inputStatsSo.setAttribute("name", "statsSo");
    inputStatsSo.setAttribute("value", JSON.stringify(statsSo));
    formReplies.appendChild(inputStatsSo);

    document.body.appendChild(formReplies);
    formReplies.submit();

}

let backOnLastQuestion = function()
{
    let questionIndex = questionList[questionList.length - 1];
    answerList = answerList.slice(0, answerList.length - 1);
    questionList = questionList.slice(0, questionList.length - 1);

    let closeAllQuestionsButtons = function()
    {
        let questionButton = document.querySelectorAll(".questionButton");
        for (let i = questionButton.length - 1; i >= 0; i--)
        {
            questionButton[i].classList.add("questionButtonClosed");
        }        
    }

    if (questionList.length == 0 && !backToLastQuestionButton.classList.contains("disabled_v2"))
    {
        backToLastQuestionButton.classList.add("disabled_v2")
    }
    else if (questionList.length == 2)
    {
        loadQuestions(currentTheme+"1");
        closeAllQuestionsButtons();
    }
    else if (questionList.length == 5)
    {
        loadQuestions(currentTheme+"2");
        closeAllQuestionsButtons();
    }

    document.getElementById("questionButton0" + questionIndex).classList.remove("questionButtonClosed");
}

let manageDisplayQuiz = function(wantedStatus)
{
    let circlesToCallQuestion = document.querySelectorAll(".questionButton");
    for (let i = circlesToCallQuestion.length - 1; i >= 0; i--)
    {
        if (wantedStatus == "hide")
        {
            circlesToCallQuestion[i].style.opacity = "0";
        }
        else
        {
             circlesToCallQuestion[i].style.opacity = "1";           
        }
    }
    if (wantedStatus == "hide")
    {
        document.getElementById("themeBackground").style.opacity = "0";
    }
    else
    {
        document.getElementById("themeBackground").style.opacity = "1";
    }
}

let dezoomBackground = function()
{
    let imgBgContainer = document.getElementById("themeBackgroundContainer");
    let imgBg = document.getElementById("themeBackground");
    let questionButtons = document.querySelectorAll(".questionButton");

    imgBgContainer.style = "";
    imgBg.style = "";
    for (let i = questionButtons.length - 1; i >= 0; i--)
    {
        questionButtons[i].style.display = "block";
    }

    fitBackgroundQuestions();
}

let saveAnswer = function(answerIndex)
{
    let questionContainer = document.getElementById("questionContainer");
    let backToLastQuestionButton = document.getElementById("backToLastQuestionButton");

    dezoomBackground();
    if (questionList.length > 0 && backToLastQuestionButton.classList.contains("disabled_v2"))
    {
        backToLastQuestionButton.classList.remove("disabled_v2")
    }

    document.getElementById("themeBackground").classList.toggle("disabled_v2");
    questionContainer.classList.toggle("disabled_v2");
    answerList.push(answerIndex + 1);
    waitForAnswer = false;
    // check the answers to find out what to do next
    if (answerList.length == 3)
    {
        loadQuestions(currentTheme+"2");

        if (bonusGameAlreadyPlayed[0] == false)
        {
            document.getElementById("pacmanContainer").classList.toggle("disabled");
            launchPacmanHome();
            bonusGameAlreadyPlayed[0] = true;
        }
    }
    else if (answerList.length == 6)
    {
        loadQuestions(currentTheme+"3");

        if (bonusGameAlreadyPlayed[1] == false)
        {
            document.getElementById("pacmanContainer").classList.toggle("disabled");
            launchPacmanHome();
            bonusGameAlreadyPlayed[1] = true;
        }
    }
    else if (answerList.length == 9)
    {
        if (gameInfos["openquestion"] != false)
        {
            if (gameInfos["openquestion"]["serie"] == currentTheme)
            {
                let propositionsContainer = document.getElementById("propositionsContainer");
                let openQuestionTextArea = document.getElementById("openQuestionTextArea");
                let step_questions = document.getElementById("step_questions");

                document.getElementById("questionIntro").innerText = "";   
                openQuestionTextArea.classList.toggle("disabled");
                openQuestionTextArea.focus();
                document.getElementById("question").innerText = gameInfos["openquestion"]["question"];

                fitBackgroundQuestions();    

                questionContainer.style.transition = "none";
                propositionsContainer.style.transition = "none";

                questionContainer.classList.toggle("disabled_v2");
                propositionsContainer.classList.toggle("disabled_v2");

                questionContainer.style.transition = "";
                propositionsContainer.style.transition = "";

                let sendAnswersToDbButton = document.createElement("button");
                sendAnswersToDbButton.innerText = "valider";
                sendAnswersToDbButton.setAttribute("class", "buttonDefault sendAnswersToDbButton");
                step_questions.appendChild(sendAnswersToDbButton);
                sendAnswersToDbButton.addEventListener("click", sendToDb, false);
            }
        }
    }
}

// Load Questions and Propositions
let displayQuestion = function(questionIndex, event)
{
    if (waitForAnswer == false && !event.target.classList.contains("questionButtonClosed"))
    {
        fitBackgroundQuestions();

        let getQuestion = function()
        {
            let currentQuestion = {};
            let questionParagraph = document.getElementById("question");
            let propositions = document.querySelectorAll("#propositionsContainer img");
            waitForAnswer = true;
            questionList.push(questionIndex);
            
            event.target.classList.add("questionButtonClosed")
            for(let question in quiz) 
            {
                if (event.target == quiz[question]["tag"])
                {
                    currentQuestion = quiz[question];
                }
            }    
     
            questionParagraph.innerText = currentQuestion["question"];
            for (let i = propositions.length - 1; i >= 0; i--)
            {
                let proposition = "proposition0" + (i + 1)
                propositions[i].src = currentQuestion[proposition]["imageSrc"];
                propositions[i].alt = currentQuestion[proposition]["proposition"];
                propositions[i].id = currentQuestion[proposition]["proposition"];
            }

            //document.getElementById("propositionsContainer").classList.toggle("disabled_v2");
            document.getElementById("questionContainer").classList.toggle("disabled_v2");
            document.getElementById("themeBackground").classList.toggle("disabled_v2");
        }

        let zoomOnQuestionArea = function(event)
        {
            let imgBgContainer = document.getElementById("themeBackgroundContainer");
            let imgBg = document.getElementById("themeBackground");
            let questionButtons = document.querySelectorAll(".questionButton");
            let questionIntro = document.getElementById("questionIntro");
            let questionArea = event.target;
            let imgBgPosX = imgBg.offsetLeft;
            let imgBgPosY = imgBg.offsetTop;
            let imgBgWidth = imgBg.offsetWidth;
            let imgBgHeight = imgBg.offsetHeight;
            let questionAreaPosX = questionArea.offsetLeft - imgBgPosX;
            let questionAreaPosY = questionArea.offsetTop - imgBgPosY;

            imgBgContainer.style.width = imgBgWidth + "px";
            imgBgContainer.style.height = imgBgHeight + questionIntro.offsetHeight + "px";
            imgBgContainer.style.overflow = "hidden";

            let translateX = ((imgBgWidth / 2) - questionAreaPosX) * 3;
            let translateY = ((imgBgHeight / 2) - questionAreaPosY) * 3;

            imgBg.style.transition = "all 2s";
            imgBg.style.transform = "translate("+translateX+"px, "+translateY+"px) scale(3)";

            for (let i = questionButtons.length - 1; i >= 0; i--)
            {
                questionButtons[i].style.display = "none";
            }
            let waitForGetQuestion = setTimeout(function()
            {
                getQuestion();
            }, 500);
        }
        zoomOnQuestionArea(event);
    }
}

// Launch Game
let updateQuiz = function(themePosition)
{
    switch(themePosition)
    {
        case "A1":
            quiz = quizA1;
            break;
        case "A2":
            quiz = quizA2;
            break;            
        case "A3":
            quiz = quizA3;
            break;
        case "B1":
            quiz = quizB1;
            break;
        case "B2":
            quiz = quizB2;
            break;            
        case "B3":
            quiz = quizB3;
            break;
    }
}
let loadQuestions = function(themePosition)
{
    currentTheme = themePosition.slice(0, 1);
    updateQuiz(themePosition);

    // load intro
    document.getElementById("questionIntro").innerText = quiz["question01"]["intro"];
    let questionButtons = document.querySelectorAll(".questionButton");
    for (let i = questionButtons.length - 1; i >= 0; i--)
    {
        if (questionButtons[i].classList.contains("questionButtonClosed"))
        {
            questionButtons[i].classList.remove("questionButtonClosed");
        }
    }
    document.querySelector("#themeBackground").src = quiz["question01"]["imageSrc"];
    closeThemesMenu();
    document.querySelector("#step_questions").classList.remove('disabled');
    document.querySelector(".headerProfile").style.backgroundColor = "black";
}
// Display
let launchThemesMenu = function(event)
{
    event.stopPropagation();
    let step_chooseThemeContainer = document.querySelector("#step_chooseTheme");
    step_chooseThemeContainer.classList.remove("disabled");
    // close if user click out of this container
    document.body.onclick = function(event)
    {
    	let themesContainer = document.querySelector("#themesContainer");
    	let mouse = {};
    	mouse.x = event.touches != undefined ? event.touches[0].clientX : event.clientX;
    	mouse.y = event.touches != undefined ? event.touches[0].clientY : event.clientY;
    	if (mouse.x < themesContainer.offsetLeft || mouse.x > themesContainer.offsetLeft + themesContainer.offsetWidth || mouse.y < themesContainer.offsetTop || mouse.y > themesContainer.offsetTop + themesContainer.offsetHeight)
    	{
    		closeThemesMenu();
    	}
    }
    // Active Theme Buttons
    let themeButtons = document.querySelectorAll(".themeButton");
    let buttonQuizList = ["A1", "B1"];
    for (let i = themeButtons.length - 1; i >= 0; i--)
    {
        if (themeButtons[i].classList.contains("unlocked"))
        {
            themeButtons[i].addEventListener("click", loadQuestions.bind(this, buttonQuizList[i]), false);
        }
    }
}

// -- MAIN MENU --
let hideMainMenu = function()
{
    let launchThemesMenuButtonList = document.querySelectorAll(".menuButtonContainer");
    let mainMenuButton = document.querySelector("#mainMenuButton");
    for (let i = launchThemesMenuButtonList.length - 1; i >= 0; i--)
    {
        if (!launchThemesMenuButtonList[i].querySelector("#mainMenuButton"))
        {
            launchThemesMenuButtonList[i].querySelector(".menuButton").classList.add("disabled_v2");
        }
        launchThemesMenuButtonList[i].className = "menuButtonContainer";
    }   
}
let displayMainMenu = function()
{
    let launchThemesMenuButtonList = document.querySelectorAll(".menuButtonContainer");
    let mainMenuButton = document.querySelector("#mainMenuButton");
    if (!mainMenuButton.parentElement.classList.contains("menuMainButtonOpen"))
    {
        for (let i = launchThemesMenuButtonList.length - 1; i >= 0; i--)
        {
            if (launchThemesMenuButtonList[i] == mainMenuButton.parentElement)
            {
                launchThemesMenuButtonList[i].classList.add("menuMainButtonOpen");
            }
            else
            {
                launchThemesMenuButtonList[i].querySelector(".menuButton").classList.remove("disabled_v2");
                launchThemesMenuButtonList[i].className += " mainMenuOpenButton0"+i;
            }
        }
    }
    else
    {
        hideMainMenu();
    }
}
let fitBackgroundQuestions = function()
{
    let headerProfile = document.getElementById("headerProfile");
    let themeBackgroundContainer = document.getElementById("themeBackgroundContainer");
    let themeBackground = document.getElementById("themeBackground");
    let questionContainer = document.getElementById("questionContainer");
    let questionIntro = document.getElementById("questionIntro");
    let propositionsContainer = document.getElementById("propositionsContainer");
    let question = document.getElementById("question");

    let headerProfileHeight = headerProfile.offsetHeight;
    let questionIntroHeight = questionIntro.offsetHeight;

    let ratioBG = themeBackground.offsetWidth / themeBackground.offsetHeight;
    let ratioWindow = window.innerWidth / window.innerHeight;
    let ratioBgWindow;

    // background quiz size
    if (ratioWindow < ratioBG)
    {
        ratioBgWindow = (window.innerWidth - themeBackground.offsetWidth) / themeBackground.offsetWidth;
        /*themeBackground.style.height = themeBackground.height + (themeBackground.height * ratioBgWindow) + "px";
        themeBackground.style.width = window.innerWidth + "px";*/
    }
    else
    {
        ratioBgWindow = (window.innerHeight - themeBackground.offsetHeight) / themeBackground.offsetHeight;
        /*themeBackground.style.width = themeBackground.width + (themeBackground.width * ratioBgWindow) + "px";
        themeBackground.style.height = window.innerHeight + "px";*/
    }
    themeBackgroundContainer.style.height = window.innerHeight - (headerProfileHeight + questionIntroHeight) + "px";
    // fixed the container of the introductory sentence to background image
    questionIntro.style.width = themeBackground.offsetWidth + "px";
    // fixed the container question
    questionContainer.style.width = themeBackground.offsetWidth + "px";
    questionContainer.style.height = themeBackgroundContainer.offsetHeight + "px";
    questionContainer.style.left = themeBackground.offsetLeft + "px";
    questionContainer.style.top = themeBackground.offsetTop + "px";
    // fixed height of proposition container
    propositionsContainer.style.height = themeBackgroundContainer.offsetHeight - question.offsetHeight + "px";
    // background quiz position
    //themeBackgroundContainer.style.left = (window.innerWidth / 2) - (themeBackground.offsetWidth / 2) + "px";
    //themeBackgroundContainer.style.top = headerProfileHeight + "px";

    // circles position(button to call a question)
    for(let question in quiz) 
    {
        quiz[question]["tag"].style.left = ((themeBackground.offsetWidth / 100) * quiz[question]["xOrigin"]) + (quiz[question]["xOrigin"] * ratioBgWindow) + themeBackground.offsetLeft + "px";
        quiz[question]["tag"].style.top = ((themeBackground.offsetHeight / 100) * quiz[question]["yOrigin"]) + (quiz[question]["yOrigin"] * ratioBgWindow) + themeBackground.offsetTop + "px";
        quiz[question]["tag"].style.width = ((themeBackground.offsetWidth / 100) * quiz[question]["sizeOrigin"]) + (quiz[question]["sizeOrigin"] * ratioBgWindow) + "px";
        quiz[question]["tag"].style.height = ((themeBackground.offsetWidth / 100) * quiz[question]["sizeOrigin"]) + (quiz[question]["sizeOrigin"] * ratioBgWindow) + "px";
    }
}
document.getElementById("themeBackground").onload = function()
{
    fitBackgroundQuestions();
}
window.addEventListener("resize", fitBackgroundQuestions, false);

document.getElementById("questionButton01").addEventListener("click", displayQuestion.bind(this, 1), false);
document.getElementById("questionButton02").addEventListener("click", displayQuestion.bind(this, 2), false);
document.getElementById("questionButton03").addEventListener("click", displayQuestion.bind(this, 3), false);


// -- DISPLAY THEMES MENU --
let launchThemesMenuButton = document.querySelector("#launchThemesMenuButton");
launchThemesMenuButton.addEventListener("click", launchThemesMenu, false);
// -- BACK TO LOCAL UI --
let backToLocalBgButton = document.querySelector("#backToLocalBgContainer");
backToLocalBgButton.addEventListener("click", closeThemesMenu, false);
// -- BACK TO SOLAR SYSTEM --
let backToSolarSystemButton = document.querySelector("#backToSolarSystem");
backToSolarSystemButton.addEventListener("click", function()
{
    window.location.href = "index.php";
}, false);
// -- LEAVE GAME --
let leaveGameButton = document.querySelector("#leaveGame");
leaveGameButton.addEventListener("click", function()
{
    window.location.href = "../../library.php";
}, false);
// -- MAIN MENU --
let mainMenuButton = document.querySelector("#mainMenuButton");
mainMenuButton.addEventListener("click", displayMainMenu, false);

// -- PROPOSITION BUTTON TO RECORD ANSWERS --
let propositionButtons = document.querySelectorAll(".proposition");
for (let i = propositionButtons.length - 1; i >=0; i--)
{
    propositionButtons[i].addEventListener("click", saveAnswer.bind(this, i), false);
}
// -- BACK ON LAST QUESTION --
document.getElementById("backToLastQuestionButton").addEventListener("click", backOnLastQuestion, false);

displayScoresBar();
