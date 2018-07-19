window.addEventListener('load', function()
{
    // -- DISPLAY THEMES MENU --
    // Hide
	let closeThemesMenu = function()
	{
        let step_chooseThemeContainer = document.querySelector("#step_chooseTheme");
        step_chooseThemeContainer.classList.add("disabled");		
        document.body.onclick = null;
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
    }
    // -- MAIN MENU --
    let hideMainMenu = function()
    {
        let launchThemesMenuButtonList = document.querySelectorAll(".menuButtonContainer");
        let mainMenuButton = document.querySelector("#mainMenuButton");
        for (let i = launchThemesMenuButtonList.length - 1; i >= 0; i--)
        {
            if (launchThemesMenuButtonList[i] == mainMenuButton.parentElement)
            {
                launchThemesMenuButtonList[i].classList.remove("menuMainButtonOpen");
            }
            else
            {
                launchThemesMenuButtonList[i].classList.add("disabled");
            }
            launchThemesMenuButtonList[i].classList.remove("menuButtonContainerOpen");
        }   
    }
    let displayMainMenu = function()
    {
        let launchThemesMenuButtonList = document.querySelectorAll(".menuButtonContainer");
        let mainMenuButton = document.querySelector("#mainMenuButton");
        if (!mainMenuButton.parentElement.classList.contains("menuMainButtonOpen"))
        {
            console.log(mainMenuButton.parentElement)
            for (let i = launchThemesMenuButtonList.length - 1; i >= 0; i--)
            {
                if (launchThemesMenuButtonList[i] == mainMenuButton.parentElement)
                {
                    launchThemesMenuButtonList[i].classList.add("menuMainButtonOpen");
                }
                else
                {
                    launchThemesMenuButtonList[i].classList.remove("disabled");
                }
                launchThemesMenuButtonList[i].classList.add("mainMenuOpenButton0"+i);
            }
        }
        else
        {
            hideMainMenu();
        }
    }

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