// Arrays for images objects
let bgImage = [];
let mainGameQuizABgImages = [], mainGameQuizA1PropositionsImages = [] , mainGameQuizA2PropositionsImages = [], mainGameQuizA3PropositionsImages = [];
let flsAndDglTutoBgImages = [];
let flsFrLegImages = [], flsGoodBadImages = [];
let dglBackgroundImages = [], dglPlayerImages = [], dglEnemiesImages = [], dglAlimImages = [];
let bgImageSrc = [backgroundImageSrc];

let numberOfLoadedImages = 0;
let numberTotalImages;

(function()
{
	let preloadImages = function(imgSrcList, imgList)
	{
		for (let i = 0, length = imgSrcList.length; i < length; i++)
		{
			let newImage = new Image();
			newImage.src = imgSrcList[i];
			imgList.push(newImage);
			newImage.onload = function()
			{
				numberOfLoadedImages += 1;
				if (numberOfLoadedImages == numberTotalImages)
				{
	  				window.addEventListener("load", function(event)
	  				{
	  					bgImage[0].setAttribute("class", "localBg localBgAir");
	  					bgImage[0].setAttribute("alt", "Maison du joueur.");
	  					document.getElementById("localBgContainer").appendChild(bgImage[0]);
	  					initGame();
	  				});
				}
			}
		}
	}
	// Src for images
	let mainGameQuizABgImagesSrc = ["assets/img/alim_bg01.svg", "assets/img/alim_bg02.svg", "assets/img/alim_bg03.svg"];
	let mainGameQuizA1PropositionsImagesSrc = ["assets/img/cereals.svg", "assets/img/toast.svg", "assets/img/croissant.svg", "assets/img/orange-juice.svg", "assets/img/milk.svg", "assets/img/can.svg", "assets/img/chocolate.svg", "assets/img/apple.svg", "assets/img/muffin.svg"];
	let mainGameQuizA2PropositionsImagesSrc = ["assets/img/fish_breaded.svg", "assets/img/salmon.svg", "assets/img/fish.svg", "assets/img/strawberry.svg", "assets/img/pineapple.svg", "assets/img/pear.svg", "assets/img/question.svg", "assets/img/controller.svg", "assets/img/cash.svg"];
	let mainGameQuizA3PropositionsImagesSrc = ["assets/img/beef.svg", "assets/img/chicken.svg", "assets/img/burger_green.svg", "assets/img/bottle.svg", "assets/img/water.svg", "assets/img/plastic.svg", "assets/img/chips.svg", "assets/img/candy.svg", "assets/img/chocolate.svg"];

	let flsAndDglTutoBgIagesSrc = ["assets/img/fls_tutobg.jpg", "assets/img/dgl_tutobg.svg"]
	let flsFrLegImagesSrc = ["assets/img/cassis.png", "assets/img/cerise.png", "assets/img/groseille.png", "assets/img/myrtille.png", "assets/img/pomme.png", "assets/img/poire.png", "assets/img/carotte.png", "assets/img/petitpois.png", "assets/img/potiron.png", "assets/img/tomate.png", "assets/img/pdt.png", "assets/img/radirose.png"];
	let flsGoodBadImagesSrc = ["assets/img/answer_inco.svg", "assets/img/answer_co.svg"];

	let dglBackgroundImagesSrc = ["assets/img/goal.svg", "assets/img/road.svg", "assets/img/forest.svg", "assets/img/mountains_a.svg", "assets/img/mountains_b.svg", "assets/img/clouds.svg"];
	let dglPlayerImagesSrc = ["assets/img/bike01.svg", "assets/img/bike02.svg", "assets/img/bike03.svg", "assets/img/bike04.svg", "assets/img/bikeJump.svg"];
	let dglEnemiesImagesSrc = ["assets/img/cow.svg", "assets/img/cow_v02.svg", "assets/img/balloon.svg"];
	let dglAlimImagesSrc = ["assets/img/apple.svg", "assets/img/bottle.svg", "assets/img/mushroom.svg", "assets/img/pears.svg", "assets/img/avocado.svg", "assets/img/olives.svg", "assets/img/flask.svg", "assets/img/beef.svg", "assets/img/kiwi.svg"];

	numberTotalImages = bgImageSrc.length + flsAndDglTutoBgImages.length + mainGameQuizABgImagesSrc.length + mainGameQuizA1PropositionsImagesSrc.length + mainGameQuizA2PropositionsImagesSrc.length + mainGameQuizA3PropositionsImagesSrc.length + flsFrLegImagesSrc.length + flsGoodBadImagesSrc.length + dglBackgroundImagesSrc.length + dglPlayerImagesSrc.length + dglEnemiesImagesSrc.length + dglAlimImagesSrc.length;

	// load img
	preloadImages(bgImageSrc, bgImage);

	preloadImages(mainGameQuizABgImagesSrc, mainGameQuizABgImages);

	preloadImages(mainGameQuizA1PropositionsImagesSrc, mainGameQuizA1PropositionsImages);
	preloadImages(mainGameQuizA2PropositionsImagesSrc, mainGameQuizA2PropositionsImages);
	preloadImages(mainGameQuizA3PropositionsImagesSrc, mainGameQuizA3PropositionsImages);

	preloadImages(flsAndDglTutoBgIagesSrc, flsAndDglTutoBgImages);
	preloadImages(flsFrLegImagesSrc, flsFrLegImages);
	preloadImages(flsGoodBadImagesSrc, flsGoodBadImages);

	preloadImages(dglBackgroundImagesSrc, dglBackgroundImages);
	preloadImages(dglPlayerImagesSrc, dglPlayerImages);
	preloadImages(dglEnemiesImagesSrc, dglEnemiesImages);
	preloadImages(dglAlimImagesSrc, dglAlimImages);
})()