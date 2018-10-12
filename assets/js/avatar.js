window.addEventListener("load", function(event)
{
	let openAvatarCustomSystem = function()
	{
		// display/hide avatar custom container and library container
		document.getElementById("avatarCustomContainer").classList.toggle("disabled");
		document.getElementById("libElemContainer").classList.toggle("libElemContainer");
		document.getElementById("libElemContainer").classList.toggle("disabled");
		// change title
		let title = document.querySelector(".navBar h2");
		title.innerText =  title.innerText == "Ludothèque" ? "Personnalise ton Avatar" : "Ludothèque";
		// detect if section is already active and disabled it
		let avatarCustomButtons = document.querySelectorAll("#avatarCustomButtons button");
		for (let i = avatarCustomButtons.length - 1; i >= 0; i--)
		{
			if (avatarCustomButtons[i].classList.contains("selected"))
			{
				avatarCustomButtons[i].classList.remove("selected");
				let id = avatarCustomButtons[i].id;
				id = id.replace("Button", "Container");
				document.getElementById(id).classList.add("disabled");
			}
		}
		// if skin section is disabled, active it
		if (!document.getElementById("avatarCustomBackButton").classList.contains("selected"))
		{
			document.getElementById("avatarCustomBackButton").classList.add("selected")
			document.getElementById("avatarCustomBackContainer").classList.remove("disabled")
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

	let updateAvatarCustom = function(color, theme, aucun = false)
	{
		let newSrc;
		if (theme.indexOf("Back") != -1)
		{
			classname = "avatar_back";
		}
		else if (theme.indexOf("Peau") != -1)
		{
			classname = "avatar_tete";
		}
		else if (theme.indexOf("Yeux") != -1)
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
		else if (theme.indexOf("Corps") != -1)
		{
			classname = "avatar_corps";
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
		if (aucun == true)
		{
			document.querySelector(".avatarCustomResult ."+classname).src = "";
		}
		else
		{
			document.querySelector(".avatarCustomResult ."+classname).src = newSrc;
		}
	}

	let selectAvatarCustomElement = function(e)
	{
		if (typeof e.target.src != "undefined")
		{
			let aucun = false;
			if (e.target.classList.contains("aucun"))
			{
				aucun = true;
			}
			let parentId = e.target.parentElement.id;
			let newSrc = e.target.src;
			updateAvatarCustom(newSrc, parentId, aucun);
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
			if (!images[i].classList.contains("aucun"))
			{
				images[i].src = newSrc;
			}
		}
		updateAvatarCustom(color, parentId.id)
	}

	/*let cutAfterWord = function(stringToCut, stringAfterWich)
	{
		let indexBegin;
		let result;
		if (stringToCut.indexOf(stringAfterWich) != -1)
		{
			indexBegin = stringToCut.indexOf(stringAfterWich);
			result = stringToCut.slice(indexBegin+stringAfterWich.length);
		}
		else
		{
			result = "";
		}
		return result;
	}*/

	let recordAvatar = function()
	{
		let avatarCustomResult = document.querySelectorAll(".avatarCustomResult img");
		let form = document.createElement("form");
		form.setAttribute("action", "library.php");
		form.setAttribute("method", "post");
		form.setAttribute("class", "disabled");
		for (let i = 0, length = avatarCustomResult.length; i < length; i++)
		{
			let relativeSrc = avatarCustomResult[i].src.slice(-11);
			relativeSrc = relativeSrc.replace(".svg", "");
			if (relativeSrc =="library.php")
			{
				relativeSrc = "";
			}
			//let relativeSrc = cutAfterWord(avatarCustomResult[i].src, "/img/");
			let output = document.createElement("input");
			output.setAttribute("name", "filesSrcList[]");
			output.setAttribute("value", relativeSrc);
			form.appendChild(output);
			console.log(relativeSrc);
		}
		document.body.appendChild(form);
		form.submit();
	}

	let initAvatarCustom = function()
	{
		let buttons = document.querySelectorAll("#avatarCustomButtons button");
		for (let i = buttons.length - 1; i >= 0; i--)
		{
			buttons[i].addEventListener("click", selectAvatarCustomTheme, false);
		}

		document.getElementById("avatarCustomBackContainer").addEventListener("click", selectAvatarCustomElement, false);
		document.getElementById("avatarCustomYeuxContainer").addEventListener("click", selectAvatarCustomElement, false);
		document.getElementById("avatarCustomLunettesContainer").addEventListener("click", selectAvatarCustomElement, false);
		document.getElementById("avatarCustomBoucheContainer").addEventListener("click", selectAvatarCustomElement, false);
		document.getElementById("avatarCustomCheveuxContainer").addEventListener("click", selectAvatarCustomElement, false);
		document.getElementById("avatarCustomCorpsContainer").addEventListener("click", selectAvatarCustomElement, false);

		document.getElementById("avatarContainer").addEventListener("click", openAvatarCustomSystem, false);

		// colors
		let backColorsContainer = document.getElementById("avatarCustomBackColorsContainer");
		let peauColorsContainer = document.getElementById("avatarCustomPeauColorsContainer");
		let yeuxColorsContainer = document.getElementById("avatarCustomYeuxColorsContainer");
		let lunettesColorsContainer = document.getElementById("avatarCustomLunettesColorsContainer");
		let cheveuxColorsContainer = document.getElementById("avatarCustomCheveuxColorsContainer");
		let corpsColorContainer = document.getElementById("avatarCustomCorpsColorsContainer");

		let colorsContainerList = [backColorsContainer, peauColorsContainer, yeuxColorsContainer, lunettesColorsContainer, cheveuxColorsContainer, corpsColorContainer];

		let backColorsList = ["#d5e7e3", "#ebdcc8", "#9f745b", "#8e9272", "#7d2c1f", "#5c5882", "#222b00", "black"];
		let peauColorsList = ["#f4d4b8", "#e5ab86", "#eea791", "#a56951", "#754133"];
		let yeuxColorsList = ["black", "#aa4400", "#aad400", "blue"];
		let lunettesColorsList = [];
		let CheveuxColorsList = ["black", "#a05a2c", "#ffcc00", "#ff6600"];
		let CorpsColorsList = ["#495caa", "#aa0000", "#ffcc00", "#ff6600"];
		let allColorsByTheme = [backColorsList, peauColorsList, yeuxColorsList, lunettesColorsList, CheveuxColorsList, CorpsColorsList];

		for (let i = colorsContainerList.length - 1; i >= 0; i--)
		{
			for (let j = 0, length = allColorsByTheme[i].length; j < length; j++)
			{
				let index = j + 1;
				let colNumber = allColorsByTheme[i].length < 10 ? "0"+index : index;
				let buttonColor = document.createElement("button");
				//let buttonClass = "customColor col"+colNumber+" "+allColorsByTheme[i][j];
				let buttonClass = "customColor col"+colNumber;
				buttonColor.setAttribute("class", buttonClass);
				buttonColor.style.backgroundColor = allColorsByTheme[i][j];
				colorsContainerList[i].appendChild(buttonColor);

				buttonColor.addEventListener("click", changeColor.bind(this, "col"+colNumber));
			}
		}
		if (!typeof firsCo!= "undefined" && firsCo == 1)
		{
			openAvatarCustomSystem();
		}
	}

	document.getElementById("avatarRecordButton").addEventListener("click", recordAvatar, false);

	initAvatarCustom();
});