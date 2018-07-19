window.addEventListener('load', function()
{
	let closeThemesMenu = function()
	{
        let step_chooseThemeContainer = document.querySelector("#step_chooseTheme");
        step_chooseThemeContainer.classList.add("disabled");		
        document.body.onclick = null;
	}
    let launchThemesMenu = function()
    {
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

    let launchThemesMenuButton = document.querySelector("#launchThemesMenuButton");
    launchThemesMenuButton.addEventListener("click", launchThemesMenu, false);

    let backToLocalBgButton = document.querySelector("#backToLocalBg");
    backToLocalBgButton.addEventListener("click", closeThemesMenu, false);

});