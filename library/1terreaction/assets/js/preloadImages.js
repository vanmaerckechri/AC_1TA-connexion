let mainGameQuizABgImagesSrc = ["assets/img/alim_bg01.svg", "assets/img/alim_bg02.svg", "assets/img/alim_bg03.svg"];
let mainGameQuizA1PropositionsImagesSrc = ["assets/img/cereals.svg", "assets/img/toast.svg", "assets/img/croissant.svg", "assets/img/orange-juice.svg", "assets/img/milk.svg", "assets/img/can.svg", "assets/img/chocolate.svg", "assets/img/apple.svg", "assets/img/muffin.svg"];
let mainGameQuizA2PropositionsImagesSrc = ["assets/img/fish_breaded.svg", "assets/img/salmon.svg", "assets/img/fish.svg", "assets/img/strawberry.svg", "assets/img/pineapple.svg", "assets/img/pear.svg", "assets/img/question.svg", "assets/img/controller.svg", "assets/img/cash.svg"];
let mainGameQuizA3PropositionsImagesSrc = ["assets/img/beef.svg", "assets/img/chicken.svg", "assets/img/burger_green.svg", "assets/img/bottle.svg", "assets/img/water.svg", "assets/img/plastic.svg", "assets/img/chips.svg", "assets/img/candy.svg", "assets/img/chocolate.svg"];

let flsFrLegImagesSrc = ["assets/img/cassis.png", "assets/img/cerise.png", "assets/img/groseille.png", "assets/img/myrtille.png", "assets/img/pomme.png", "assets/img/poire.png", "assets/img/carotte.png", "assets/img/petitpois.png", "assets/img/potiron.png", "assets/img/tomate.png", "assets/img/pdt.png", "assets/img/radirose.png"];
let flsGoodBadImagesSrc = ["assets/img/answer_inco.svg", "assets/img/answer_co.svg"];

let dglBackgroundImagesSrc = ["assets/img/goal.svg", "assets/img/road.svg", "assets/img/forest.svg", "assets/img/mountains_a.svg", "assets/img/mountains_b.svg", "assets/img/clouds.svg"];
let dglPlayerImagesSrc = ["assets/img/bike01.svg", "assets/img/bike02.svg", "assets/img/bike03.svg", "assets/img/bike04.svg", "assets/img/bikeJump.svg"];
let dglEnemiesImagesSrc = ["assets/img/cow.svg", "assets/img/cow_v02.svg", "assets/img/balloon.svg"];
let dglAlimImagesSrc = ["assets/img/apple.svg", "assets/img/bottle.svg", "assets/img/mushroom.svg", "assets/img/pears.svg", "assets/img/avocado.svg", "assets/img/olives.svg", "assets/img/flask.svg", "assets/img/beef.svg", "assets/img/kiwi.svg"];

let numberOfLoadedImages = 0;
let numberTotalImages = mainGameQuizABgImagesSrc.length + mainGameQuizA1PropositionsImagesSrc.length + mainGameQuizA2PropositionsImagesSrc.length + mainGameQuizA3PropositionsImagesSrc.length + flsFrLegImagesSrc.length + flsGoodBadImagesSrc.length + dglBackgroundImagesSrc.length + dglPlayerImagesSrc.length + dglEnemiesImagesSrc.length + dglAlimImagesSrc.length;


let preloadImages = function(imgSrcList)
{
	let imgList = []
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
  					initGame();
  					console.log(dglBackgroundImages)
  				});
			}
		}
	}
	return imgList;
}

let mainGameQuizABgImages = preloadImages(mainGameQuizABgImagesSrc);
let mainGameQuizA1PropositionsImages = preloadImages(mainGameQuizA1PropositionsImagesSrc);
let mainGameQuizA2PropositionsImages = preloadImages(mainGameQuizA2PropositionsImagesSrc);
let mainGameQuizA3PropositionsImages = preloadImages(mainGameQuizA3PropositionsImagesSrc);

let flsFrLegImages = preloadImages(flsFrLegImagesSrc);
let flsGoodBadImages = preloadImages(flsGoodBadImagesSrc);

let dglBackgroundImages = preloadImages(dglBackgroundImagesSrc);
let dglPlayerImages = preloadImages(dglPlayerImagesSrc);
let dglEnemiesImages = preloadImages(dglEnemiesImagesSrc);
let dglAlimImages = preloadImages(dglAlimImagesSrc);