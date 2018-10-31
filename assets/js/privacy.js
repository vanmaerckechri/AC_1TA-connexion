document.addEventListener("DOMContentLoaded", function(event)
{
	let privacy = document.getElementById("privacy");
	if (sessionStorage.getItem("privacy") === null)
	{
		let btnPrivacyClose = document.getElementById("privacyClose");
		btnPrivacyClose.onclick = function()
		{
			privacy.remove();
			sessionStorage.setItem("privacy", true);
		}
	}
	else
	{
		privacy.remove();
	}
});