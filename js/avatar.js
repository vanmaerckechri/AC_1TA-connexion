window.addEventListener("load", function(event)
{
	let avatarImages =
	{
		imageLoaded: 0,
		avatar_tete: new Image(),
		avatar_yeux: new Image(),
		avatar_lunettes: new Image(),
		avatar_bouche: new Image(),
		avatar_cheveux: new Image()
	}

	let adaptNumberFileImage = function(imageName, number)
	{
		if (number < 10)
		{
			number = "0"+number;
		}
		avatarImages[imageName].src = "assets/img/"+imageName+number+".svg";
	}

	for (let avatarPropertyName in avatarInfos)
	{
		adaptNumberFileImage(avatarPropertyName, avatarInfos[avatarPropertyName]);
	}

	let loadAvatar = function()
	{
		let avatarContainer = document.getElementById("avatarContainer");

		for (let avatarPropertyName in avatarInfos)
		{
			avatarContainer.appendChild(avatarImages[avatarPropertyName]);
		}
	}

	let checkImageLoaded = function(image)
	{
		avatarImages["imageLoaded"] += 1;
		if (avatarImages["imageLoaded"] == 5)
		{
			loadAvatar();
		}
	}

	avatarImages["avatar_tete"].onload = checkImageLoaded();
	avatarImages["avatar_yeux"].onload = checkImageLoaded();
	avatarImages["avatar_lunettes"].onload = checkImageLoaded();
	avatarImages["avatar_bouche"].onload = checkImageLoaded();
	avatarImages["avatar_cheveux"].onload = checkImageLoaded();
});