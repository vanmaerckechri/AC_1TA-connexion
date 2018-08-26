window.addEventListener("load", function(event)
{
	let openAvatarCustomSystem = function()
	{
		document.getElementById("avatarCustomContainer").classList.toggle("disabled");

		document.getElementById("libElemContainer").classList.toggle("libElemContainer");
		document.getElementById("libElemContainer").classList.toggle("disabled");

		let title = document.querySelector(".navBar h2");
		title.innerText =  title.innerText == "Ludothèque" ? "Personnalise ton Avatar" : "Ludothèque";

		if (!document.getElementById("avatarCustomYeuxButton").classList.contains("selected"))
		{
			document.getElementById("avatarCustomYeuxButton").classList.add("selected")
			document.getElementById("avatarCustomYeuxContainer").classList.remove("disabled")
		}
	}

	let selectAvatarCustomTheme = function(e)
	{
		let buttons = document.querySelectorAll("#avatarCustomButtons button");
		for (let i = buttons.length - 1; i >= 0; i--)
		{
			let avatarCustomThemeContainer = buttons[i]["id"].replace("Button", "Container");
			avatarCustomThemeContainer = document.getElementById(avatarCustomThemeContainer) ;
			if (e.target != buttons[i])
			{
				if (!avatarCustomThemeContainer.classList.contains("disabled"))
				{
					avatarCustomThemeContainer.classList.add("disabled");
					buttons[i].classList.remove("selected");
				}
			}
			else
			{
				avatarCustomThemeContainer.classList.remove("disabled");
				buttons[i].classList.add("selected");
			}
		}
	}	

	let updateAvatarCustom = function(color, theme)
	{
		let newSrc;
		if (theme.indexOf("Yeux") != -1)
		{
			classname = "avatar_yeux";
		}
		else if (theme.indexOf("Cheveux") != -1)
		{
			classname = "avatar_cheveux";
		}
		else if (theme.indexOf("Lunettes") != -1)
		{
			classname = "avatar_lunettes";
		}
		else if (theme.indexOf("Bouche") != -1)
		{
			classname = "avatar_bouche";
		}
		if (color.length == 5)
		{
			newSrc = document.querySelector(".avatarCustomResult ."+classname).src;
			newSrc = newSrc.slice(0, -9);
			newSrc += color+".svg";
		}
		else
		{
			newSrc = color;
		}
		document.querySelector(".avatarCustomResult ."+classname).src = newSrc;
	}

	let selectAvatarCustomElement = function(e)
	{
		if (typeof e.target.src != "undefined")
		{
			let parentId = e.target.parentElement.id;
			let newSrc = e.target.src;
			updateAvatarCustom(newSrc, parentId);
		}
	}


	let changeColor = function(color, event)
	{
		let parentId = event.target.parentElement.parentElement;
		let images = parentId.querySelectorAll("img");
		for (let i = images.length - 1; i >= 0; i--)
		{
			let newSrc = images[i].src;
			newSrc = newSrc.slice(0, -9);
			newSrc += color+".svg";
			images[i].src = newSrc;
		}
		updateAvatarCustom(color, parentId.id)
	}

	let initAvatarCustom = function()
	{
		let buttons = document.querySelectorAll("#avatarCustomButtons button");
		for (let i = buttons.length - 1; i >= 0; i--)
		{
			buttons[i].addEventListener("click", selectAvatarCustomTheme, false);
		}

		document.getElementById("avatarCustomYeuxContainer").addEventListener("click", selectAvatarCustomElement, false);
		document.getElementById("avatarCustomLunettesContainer").addEventListener("click", selectAvatarCustomElement, false);
		document.getElementById("avatarCustomBoucheContainer").addEventListener("click", selectAvatarCustomElement, false);
		document.getElementById("avatarCustomCheveuxContainer").addEventListener("click", selectAvatarCustomElement, false);

		document.getElementById("avatarContainer").addEventListener("click", openAvatarCustomSystem, false);

		// colors
		let yeuxColorsContainer = document.getElementById("avatarCustomYeuxColorsContainer");
		let lunettesColorsContainer = document.getElementById("avatarCustomLunettesColorsContainer");
		let cheveuxColorsContainer = document.getElementById("avatarCustomCheveuxColorsContainer");
		let colorsContainerList = [yeuxColorsContainer, lunettesColorsContainer, cheveuxColorsContainer];

		let yeuxColorsList = ["black", "brown", "blue", "green"];
		let lunettesColorsList = ["black", "blue", "white"];
		let CheveuxColorsList = ["black", "brown", "yellow", "orange"];
		let allColorsByTheme = [yeuxColorsList, lunettesColorsList, CheveuxColorsList];

		for (let i = colorsContainerList.length - 1; i >= 0; i--)
		{
			console.log(colorsContainerList[i])
			for (let j = 0, length = allColorsByTheme[i].length; j < length; j++)
			{
				let index = j + 1;
				let colNumber = allColorsByTheme[i].length < 10 ? "0"+index : index;
				let buttonColor = document.createElement("button");
				let buttonClass = "customColor col"+colNumber+" "+allColorsByTheme[i][j];
				buttonColor.setAttribute("class", buttonClass);
				colorsContainerList[i].appendChild(buttonColor);

				buttonColor.addEventListener("click", changeColor.bind(this, "col"+colNumber));
			}
		}
	}

	initAvatarCustom();
});