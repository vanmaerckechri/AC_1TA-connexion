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

	let selectAvatarCustomElement = function(e)
	{
		if (e.target.localName == "img")
		{
			let avatarCustomResult = document.querySelector(".avatarCustomResult");
			let avatarCustomContainer = document.getElementById("avatarCustomContainer");
			let theme;
			let src;
			if (e.target["src"].indexOf("yeux") != -1)
			{
				theme = "avatar_yeux";
				src = e.target["src"];
			}
			else if (e.target["src"].indexOf("lunettes") != -1)
			{
				theme = "avatar_lunettes";
							src = e.target["src"];

			}		
			else if (e.target["src"].indexOf("bouche") != -1)
			{
				theme = "avatar_bouche";
							src = e.target["src"];

			}		
			else if (e.target["src"].indexOf("cheveux") != -1)
			{
				theme = "avatar_cheveux";
				src = e.target["src"];
			}
			avatarCustomResult.querySelector("."+theme).src = src;
		}
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
	}

	initAvatarCustom();
});