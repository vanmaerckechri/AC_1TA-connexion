window.addEventListener('DOMContentLoaded', function()
{
    let answersId = [];
    let questionPosition = 1;
    let indexQuestion = 0;
    let indexPropo = 0;
    // -- DISPLAY THEMES MENU --
    // Hide
	let closeThemesMenu = function()
	{
        let step_chooseThemeContainer = document.querySelector("#step_chooseTheme");
        step_chooseThemeContainer.classList.add("disabled");		
        document.body.onclick = null;
	}
    // Save Answer
    let saveAnswer = function(answerIndex)
    {
        document.getElementById("questionContainer").classList.toggle("disabled");
        answerList.push(answerIndex + 1);
        waitForAnswer = false;
        // check the answers to find out what to do next
        if (answerList.length == 3)
        {
            // launch Pacman
        }
        else if (answerList.length == 6)
        {
            // launch Fruit Ninja
        }
        else if (answerList.length == 9)
        {
            // send results in db and display stats
        }
    }

    // Load Questions and Propositions
    let loadQuestion = function(questionIndex, event)
    {
        if (waitForAnswer == false && !event.target.classList.contains("questionButtonClosed"))
        {
            let currentQuestion = {};
            let questionContainer = document.getElementById("questionContainer");
            let questionParagraph = document.getElementById("question");
            let propositions = document.querySelectorAll("#propositionsContainer img");
            waitForAnswer = true;
            questionList.push(questionIndex);
            questionContainer.classList.toggle("disabled");
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
        }
    }

    // Launch Game
    let launchGame = function(themePosition)
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
                themeButtons[i].addEventListener("click", launchGame.bind(this, buttonQuizList[i]), false);
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

        let ratioBG = themeBackground.offsetWidth / themeBackground.offsetHeight;
        let ratioWindow = window.innerWidth / window.innerHeight;
        let ratioBgWindow;  
        if (ratioWindow < ratioBG)
        {
            ratioBgWindow = (window.innerWidth - themeBackground.offsetWidth) / themeBackground.offsetWidth;
            themeBackground.style.height = themeBackground.height + (themeBackground.height * ratioBgWindow);
            themeBackground.style.width = window.innerWidth;
        }
        else
        {
            ratioBgWindow = (window.innerHeight - themeBackground.offsetHeight) / themeBackground.offsetHeight;
            themeBackground.style.width = themeBackground.width + (themeBackground.width * ratioBgWindow);
            themeBackground.style.height = window.innerHeight;
        }
        for(let question in quiz) 
        {
            quiz[question]["tag"].style.left = ((themeBackground.offsetWidth / 100) * quiz[question]["xOrigin"]) + (quiz[question]["xOrigin"] * ratioBgWindow) + themeBackground.offsetLeft + "px";
            quiz[question]["tag"].style.top = ((themeBackground.offsetHeight / 100) * quiz[question]["yOrigin"]) + (quiz[question]["yOrigin"] * ratioBgWindow) + themeBackground.offsetTop + "px";
            quiz[question]["tag"].style.width = ((themeBackground.offsetWidth / 100) * quiz[question]["sizeOrigin"]) + (quiz[question]["sizeOrigin"] * ratioBgWindow) + "px";
            quiz[question]["tag"].style.height = ((themeBackground.offsetWidth / 100) * quiz[question]["sizeOrigin"]) + (quiz[question]["sizeOrigin"] * ratioBgWindow) + "px";

            themeBackgroundContainer.style.left = (window.innerWidth / 2) - (themeBackground.offsetWidth / 2) + "px";
            themeBackgroundContainer.style.top = headerProfile.offsetHeight + "px";
        }
    }
    document.getElementById("themeBackground").onload = function()
    {
        fitBackgroundQuestions();
    }
    window.addEventListener("resize", fitBackgroundQuestions, false);

    document.getElementById("questionButton01").addEventListener("click", loadQuestion.bind(this, 1), false);
    document.getElementById("questionButton02").addEventListener("click", loadQuestion.bind(this, 2), false);
    document.getElementById("questionButton03").addEventListener("click", loadQuestion.bind(this, 3), false);


    // -- DISPLAY THEMES MENU --
    let launchThemesMenuButton = document.querySelector("#launchThemesMenuButton");
    launchThemesMenuButton.addEventListener("click", launchThemesMenu, false);
    // -- BACK TO LOCAL UI --
    let backToLocalBgButton = document.querySelector("#backToLocalBg");
    backToLocalBgButton.addEventListener("click", closeThemesMenu, false);
    // -- BACK TO SOLAR SYSTEM --
    let backToSolarSystemButton = document.querySelector("#backToSolarSystem");
    backToSolarSystemButton.addEventListener("click", function()
    {
        window.location.href = "index.php";
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
});