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
    // Load Next Question
    // Load Questions and Propositions
    let loadQuestion = function()
    {
        let themeBackgroundImg = document.querySelector("#themeBackground");
        let propositions = document.querySelectorAll("#propositionsContainer img");
        let question = document.querySelector("#question");

        themeBackgroundImg.src = "assets/img/" + gameInfos["questions"][indexQuestion]["src_img"] + ".jpg";
/*
        question.innerText = gameInfos["questions"][indexQuestion]["question"];
        for (let i = 0, length = propositions.length; i < length; i++)
        {
            propositions[i].src = "assets/img/" + gameInfos["propositions"][indexPropo]["src_img"] + ".png";
            propositions[i].alt = gameInfos["propositions"][indexPropo]["propositions"];
            propositions[i].id = gameInfos["propositions"][indexPropo]["id"];
            propositions[i].parentNode.addEventListener("click", loadQuestion, false);
            indexPropo = indexPropo + 1;
        }
        questionPosition = questionPosition + 1;
        indexQuestion = indexQuestion + 1;*/
    }
    // Launch Game
    let launchGame = function(themePosition)
    {
        // load firstQuestion
        indexQuestion = themePosition * 3;
        indexPropo = 9 * themePosition;
        loadQuestion();
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
        for (let i = themeButtons.length - 1; i >= 0; i--)
        {
            if (themeButtons[i].classList.contains("unlocked"))
            {
                themeButtons[i].addEventListener("click", launchGame.bind(this, i), false);
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
});