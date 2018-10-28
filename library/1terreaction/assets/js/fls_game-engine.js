// -- GAME OBJECT --
let Fruilegsais = class
{
	constructor()
	{
		this.refreshGameLoop;
		this.canvasList = [];
		this.timeStart;
		this.monthList = ["mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre", "janvier", "février"];
		this.daysByMonth = [31, 30, 31, 30, 31, 31, 30, 31, 30, 31, 31, 29];
		this.currentMonth = 0;//!(it's the array index);
		this.currentDay = 21;
		this.currentFruiLeg;

		this.fruitsLegumesList = 
		[
			this.cassis = 
			{
				monthToEat: ["juillet", "août"],
				img: createElem("img", "src", "assets/img/cassis.png"),
				alt: "cassis",
				id: 0
			},
			this.cerises = 
			{
				monthToEat: ["juillet", "août"],
				img: createElem("img", "src", "assets/img/cerise.png"),
				alt: "cerises",
				id: 1
			},
			this.groseilles = 
			{
				monthToEat: ["juin", "juillet"],
				img: createElem("img", "src", "assets/img/groseille.png"),
				alt: "groseilles",
				id: 2		
			},
			this.myrtilles = 
			{
				monthToEat: ["juillet", "août"],
				img: createElem("img", "src", "assets/img/myrtille.png"),
				alt: "myrtilles",
				id: 3		
			},
			this.pomme = 
			{
				monthToEat: ["mars", "septembre", "octobre", "novembre", "décembre", "janvier", "février"],
				img: createElem("img", "src", "assets/img/pomme.png"),
				alt: "pomme",
				id: 4	
			},
			this.poire = 
			{
				monthToEat: ["mars", "septembre", "octobre", "novembre", "décembre", "janvier", "février"],
				img: createElem("img", "src", "assets/img/poire.png"),
				alt: "poire",
				id: 5	
			},
			this.carotte = 
			{
				monthToEat: ["juin", "juillet", "août", "septembre", "octobre"],
				img: createElem("img", "src", "assets/img/carotte.png"),
				alt: "carotte",	
				id: 6
			},
			this.petitspois = 
			{
				monthToEat: ["juin", "juillet"],
				img: createElem("img", "src", "assets/img/petitpois.png"),
				alt: "petits pois",	
				id: 7
			},
			this.potiron = 
			{
				monthToEat: ["août", "septembre", "octobre", "novembre", "décembre", "janvier", "février"],
				img: createElem("img", "src", "assets/img/potiron.png"),
				alt: "potiron",		
				id: 8
			},
			this.tomate = 
			{
				monthToEat: ["mai", "juin", "juillet", "août", "septembre", "octobre"],
				img: createElem("img", "src", "assets/img/tomate.png"),
				alt: "tomate",	
				id: 9
			},
			this.pdt = 
			{
				monthToEat: ["mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "décembre", "janvier", "février"],
				img: createElem("img", "src", "assets/img/pdt.png"),
				alt: "pommes de terre",	
				id: 10
			},
			this.radi = 
			{
				monthToEat: ["avril", "mai", "juin", "juillet", "août", "septembre"],
				img: createElem("img", "src", "assets/img/radirose.png"),
				alt: "radis roses",		
				id: 11
			}
		];
		this.badAnswerImg = createElem("img", ["id", "class", "src"], ["flsAnswerImg", "flsAnswerImg", "assets/img/answer_inco.svg"]);
		this.goodAnswerImg = createElem("img", ["id", "class", "src"], ["flsAnswerImg", "flsAnswerImg", "assets/img/answer_co.svg"]);

		this.restFruitsLegumesList = this.fruitsLegumesList.slice();// empty slice to clone array with objects(not instance it)
		this.finalResult = [[], [], []];//this.finalResult[ [0] = fruit/legume index/id, [1] = month index, [2] = good/bad answer
		this.score = 0;
		this.endOfGame = false;
		this.numberDaysRemaining = 0;
	}

	choseRandFruitLegume()
	{
		if (document.getElementById("flsFruitLegumeImg") == null)
		{
			let fruitLegumeContainer = document.getElementById("flsFruitLegumeContainer");

			let lengthList = this.restFruitsLegumesList.length;
			let randFruitLegume = Math.floor((Math.random() * lengthList) + 0);

			let currentImg = this.restFruitsLegumesList[randFruitLegume].img;
			currentImg.setAttribute("id", "flsFruitLegumeImg");
			currentImg.setAttribute("class", "flsFruitLegumeImg");
			fruitLegumeContainer.appendChild(currentImg);

			this.currentFruiLeg = this.restFruitsLegumesList[randFruitLegume];
			this.finalResult[0].push(this.restFruitsLegumesList[randFruitLegume]["id"]);
			//delete the pick from list
			this.restFruitsLegumesList.splice(randFruitLegume, 1);
		}
	}

	updateDate()
	{
		if (this.currentDay < this.daysByMonth[this.currentMonth])
		{
			this.currentDay += 1;
			document.getElementById("flsDateDay").innerText = this.currentDay;
		}
		else
		{
			this.currentDay = 1;
			if (this.currentMonth < this.monthList.length - 1)
			{
				this.currentMonth += 1;
			}
			else
			{
				this.currentMonth = 0;
			}
			document.getElementById("flsDateDay").innerText = this.currentDay;
			document.getElementById("flsDateMonth").innerText = this.monthList[this.currentMonth];
		}

		if (this.currentDay == 21 && this.currentMonth % 3 == 0)
		{
			let flsSeason = document.getElementById("flsSeason");
			let flsDateSeasonContainer = document.getElementById("flsDateSeasonContainer");

			switch(this.currentMonth)
			{
			    case 0:
			    	if (flsSeason.innerText != "Printemps")
			    	{
						flsSeason.innerText = "Printemps";
					}
			        break;
			    case 3:
			    	if (flsSeason.innerText != "Été")
			    	{
			    		flsSeason.innerText = "Été";
			    		flsDateSeasonContainer.classList.remove("flsBgPrintemps");
						flsDateSeasonContainer.classList.add("flsBgEte");
					}
			        break;
			    case 6:
			    	if (flsSeason.innerText != "Automne")
			    	{
						flsSeason.innerText = "Automne";
						flsDateSeasonContainer.classList.remove("flsBgEte");
						flsDateSeasonContainer.classList.add("flsBgAutomne");
					}
			        break;
			    case 9:
			    	if (flsSeason.innerText != "Hiver")
			    	{
						flsSeason.innerText = "Hiver";
						flsDateSeasonContainer.classList.remove("flsBgAutomne");
						flsDateSeasonContainer.classList.add("flsBgHiver");
					}
			        break;
			} 
		}
	}

	countTime(milliSec)
	{
		if (this.currentDay == 20 && this.currentMonth == 0)
		{
			//fin de la partie
		}
		else
		{
			let currentTime = new Date().getTime();
			if ((currentTime - this.timeStart) >= milliSec || typeof this.timeStart == "undefined")
			{
				this.updateDate();
				this.timeStart = new Date().getTime();

				// calcul time bonus score on final screen				
				if (this.endOfGame == true)
				{
					this.numberDaysRemaining += 1
				}
			}
		}
	}

	treatScore(answerResult)
	{
		let score = document.getElementById("flsScore");

		this.finalResult[1].push(this.currentMonth);

		if (answerResult == true)
		{
			this.score += 100;
			score.innerText = this.score;
			this.numberOfGoodAnswer += 1;
			this.finalResult[2].push(true);
		}
		else
		{
			if (parseInt(score.innerText) > 0)
			{
				this.score -= 100;
				score.innerText = this.score;
			}
			this.finalResult[2].push(false);
		}
	}

	checkAccuracyAnswer(answer)
	{
		let fruitLegumeContainer = document.getElementById("flsFruitLegumeContainer");
		let fruitLegumeImg;
		let accuracyAnswer;

		// check if this fruit/legume is in season
		for (let i = this.currentFruiLeg["monthToEat"].length - 1; i >= 0; i--)
		{
			if (this.currentFruiLeg["monthToEat"][i] == this.monthList[this.currentMonth])
			{
				if (answer == true)
				{
					accuracyAnswer = true;
				}
				else
				{
					accuracyAnswer = false;
				}
				break;
			}
		}
		// not in season
		if (typeof accuracyAnswer == "undefined")
		{
			accuracyAnswer = answer == false ? true : false;
		}

		fruitLegumeImg = accuracyAnswer == true ? this.goodAnswerImg : this.badAnswerImg;
		fruitLegumeContainer.appendChild(fruitLegumeImg);
		return accuracyAnswer;
	}

	treatAnswer(answer)
	{
		let fruitLegumeImg = document.getElementById("flsFruitLegumeImg");
		let answerResult;
		let that = this;

		if (this.endOfGame == false && fruitLegumeImg != null && !fruitLegumeImg.classList.contains("flsFruitLegumeImgAnswerTrue") && !fruitLegumeImg.classList.contains("flsFruitLegumeImgAnswerFalse"))
		{
			// score and display
			answerResult = this.checkAccuracyAnswer(answer);
			this.treatScore(answerResult);

			// reset
			let deleteImg = setTimeout(function()
			{
				let answerImg = document.getElementById("flsAnswerImg");
				fruitLegumeImg.remove();
				answerImg.remove();
				that.endOfGame = that.restFruitsLegumesList.length == 0 ? true : false;
				clearTimeout(deleteImg);
			}, 500);

			// animate fruit/leg after answer
			if (answer == true)
			{
				fruitLegumeImg.classList.add("flsFruitLegumeImgAnswerTrue");
			}
			else
			{
				fruitLegumeImg.classList.add("flsFruitLegumeImgAnswerFalse");
			}
		}
	}

	chooseAnswer(that, event)
	{
		let fruitLegumeContainer = document.getElementById("flsFruitLegumeContainer");
		let touchStartPosX;
		let touchMovePosX;
		// with tactile
		if ('ontouchstart' in document.documentElement)
		{
			event.preventDefault();
			touchStartPosX = event.touches[0].clientX;
			fruitLegumeContainer.ontouchmove = function(event)
			{
				touchMovePosX = event.touches[0].clientX;
			};

			window.ontouchend = function()
			{
				fruitLegumeContainer.ontouchmove = null;
				window.ontouchend = null;

				if (touchStartPosX + 50 < touchMovePosX)
				{
					that.treatAnswer(true);
				}
				else if (touchStartPosX - 50 > touchMovePosX)
				{
					that.treatAnswer(false);
				}
			};
		}
		// with mouse
		else
		{
			touchStartPosX = event.clientX;
			fruitLegumeContainer.onmousemove = function(event)
			{
				touchMovePosX = event.clientX;
			};

			window.onmouseup = function()
			{
				fruitLegumeContainer.onmousemove = null;
				window.onmouseup = null;

				if (touchStartPosX + 50 < touchMovePosX)
				{
					that.treatAnswer(true);
				}
				else if (touchStartPosX - 50 > touchMovePosX)
				{
					that.treatAnswer(false);
				}
			};			
		}
	}

	refreshFinalScore()
	{
		this.countTime(10);
		document.getElementById("scoreDaysRemaining").innerText = this.numberDaysRemaining;
		document.getElementById("flsScoreFinal").innerText = this.score + this.numberDaysRemaining;
		this.refreshGameLoop = window.requestAnimationFrame(this.refreshFinalScore.bind(this));
	}

	callFinalAnwsersBoard()
	{
		let flsContainer = document.getElementById("flsContainer");
		let finalAnwsersBoardContainer = createElem("div", ["id", "class"], ["flsFinalAnwsersBoardContainer","flsFinalAnwsersBoardContainer"]);
		flsContainer.appendChild(finalAnwsersBoardContainer);

		// months board
		let titleBoardContainer = createElem("div", ["id", "class"], ["titleBoardContainer","boardContainer"]);
		titleBoardContainer.innerHTML = '<span class="flsWidthImgDummy"></span><span></span><span class="flsCellColor"></span><span></span><span class="flsCellColor"></span><span></span><span class="flsCellColor"></span><span></span><span class="flsCellColor"></span><span></span><span class="flsCellColor"></span><span></span><span class="flsCellColor"></span>';
		finalAnwsersBoardContainer.appendChild(titleBoardContainer);

		// img and choices
		for (let rowNum = this.finalResult[0].length - 1; rowNum >= 0; rowNum--)
		{
			let finalAnwserContainer = createElem("div", ["id", "class"], ["flsFinalAnwserContainer" + rowNum,"flsFinalAnwserContainer"]);
			let finalAnwserImg = this.fruitsLegumesList[this.finalResult[0][rowNum]]["img"];
			let finalAnswerId = "flsFinalAnwserImg" + this.finalResult[0][rowNum];

			finalAnwserImg.setAttribute("id", finalAnswerId);
			finalAnwserImg.setAttribute("class", "flsFinalAnwserImg");
			finalAnwserContainer.appendChild(finalAnwserImg);

			for (let colNum = 0; colNum < 12; colNum++)
			{
				let newCell = document.createElement("span");
				let addClass = "";

				if (this.monthList[colNum] == this.monthList[this.finalResult[1][rowNum]])
				{
					let monthChoosed;
					if (this.finalResult[2][rowNum] == true)
					{
						monthChoosed = this.goodAnswerImg;
					}
					else
					{
						monthChoosed = this.badAnswerImg;						
					}
					monthChoosed.setAttribute("id", "");
					monthChoosed.setAttribute("class", "flsMonthChoosed");
					newCell.appendChild(monthChoosed.cloneNode(true));
				}
				for (let k = this.fruitsLegumesList[this.finalResult[0][rowNum]]["monthToEat"].length - 1; k >= 0; k--)
				{
					if (this.monthList[colNum] == this.fruitsLegumesList[this.finalResult[0][rowNum]]["monthToEat"][k])
					{
						addClass = "flsMonthToEat";
						break;
					}
				}
				if (colNum % 2 != 0)
				{
					addClass += " flsCellColor";
				}
				newCell.setAttribute("class", addClass)
				finalAnwserContainer.appendChild(newCell);
			}

			finalAnwsersBoardContainer.appendChild(finalAnwserContainer);
		}
		
		document.getElementById("flsPassBonusButton").innerText = "continuer";
	}

	callFinalScreen()
	{
		let flsContainer = document.getElementById("flsContainer");
		let scoreFinalContainer = createElem("div", ["id", "class"], ["flsScoreFinalContainer","flsScoreFinalContainer"]);
		let scoreDaysHTML = '<span id="scoreDaysRemaining" class="scoreDaysRemaining"></span>';
		let scoreFinalHTML = '<span id="flsScoreFinal" class="flsScoreFinal">' + this.score + '</span>';

		flsContainer.appendChild(scoreFinalContainer);
		scoreFinalContainer.innerHTML = '<span class="flsScoreFinalText">score: </span>' + this.score + ' + ' + scoreDaysHTML + ' nombre de jours restants = ' + scoreFinalHTML;

		flsContainer.classList.add("flsContainerFinalScore");
		flsContainer.style.overflow = "auto";
		flsContainer.style.overflowX = "hidden";

		this.callFinalAnwsersBoard();
		this.refreshFinalScore();
	}

	refreshGame()
	{
		if (this.endOfGame == false)//endOfGame status change in treatAnswer()
		{
			// refresh canvas
			for (let i = this.canvasList.length - 1; i >= 0; i --)
			{
				this.canvasList[i].clearRect(0, 0, this.canvasList[i].width, this.canvasList[i].height);
			}

			// game cycle
			this.countTime(150);
			this.choseRandFruitLegume();
			this.refreshGameLoop = window.requestAnimationFrame(this.refreshGame.bind(this));
		}
		else
		{
			// end of game
			window.cancelAnimationFrame(this.refreshGameLoop);
			document.getElementById("flsFruitLegumeContainer").remove();
			document.getElementById("flsScoreContainer").remove();
			this.callFinalScreen();
		}
	}

	detectKey(that, event)
	{
		let answer;
		if (event.keyCode == 37)
		{
			answer = false;
		}
		else if (event.keyCode == 39)
		{
			answer = true;
		}
		that.treatAnswer(answer);
	}

	initCommands()
	{
		let fruitLegumeContainer = document.getElementById("flsFruitLegumeContainer");
		let that = this;
		let startEvent;
		// on tactile
		if ('ontouchstart' in document.documentElement)
		{
			startEvent = "touchstart";
		}
		// on computer
		else
		{
			startEvent = "mousedown";
		}
		document.addEventListener("keydown", this.detectKey.bind(that, this), false);
		fruitLegumeContainer.addEventListener(startEvent, this.chooseAnswer.bind(that, this), false);
	}

	initCanvas(canvas, canvasName)
	{
		if (typeof canvasName == "string")
		{
			canvas.width = window.innerWidth;
			canvas.height = window.innerHeight;
			canvasName = canvas.getContext("2d");
			this.canvasList.push(canvasName);
		}
		else
		{
			for (let i = canvas.length - 1; i >= 0; i--)
			{
				canvas[i].width = window.innerWidth;
				canvas[i].height = window.innerHeight;
				canvasName[i] = canvas[i].getContext("2d");
				this.canvasList.push(canvasName[i]);
			}
		}
	}

	launch()
	{
		//create html content
		let flsContainer = document.getElementById("flsContainer");

		//Score
		let fruitlegsaisUi = createElem("div", ["id", "class"], ["flsUiContainer", "flsUiContainer"]);
		let flsScoreContainer = createElem("div", ["id", "class"], ["flsScoreContainer", "flsScoreContainer"]);
		flsScoreContainer.innerText = "score: ";
		let flsScore = createElem("span", "id", "flsScore");
		flsScore.innerText = "0";

		flsScoreContainer.appendChild(flsScore);
		fruitlegsaisUi.appendChild(flsScoreContainer);
		flsContainer.appendChild(fruitlegsaisUi);

		//Date
		let flsDateSeasonContainer = createElem("div", ["id", "class"], ["flsDateSeasonContainer", "flsDateSeasonContainer flsBgPrintemps"]);
		let flsDummyBoxFlex = createElem("span", ["class"], ["flsDummyBoxFlex"]);
		let flsSeason = createElem("span", ["id", "class"], ["flsSeason", "flsSeason"]);
		let flsDateContainer = createElem("span", ["id", "class"], ["flsDateContainer", "flsDateContainer"]);
		let flsDateDay = createElem("span", ["id", "class"], ["flsDateDay", "flsDateDay"]);
		let flsDateMonth = createElem("span", ["id", "class"], ["flsDateMonth", "flsDateMonth"]);

		flsDummyBoxFlex.innerHTML += "Belgique";
		flsDateDay.innerText = this.currentDay;
		flsDateMonth.innerText = this.monthList[this.currentMonth];
		flsSeason.innerHTML += "Printemps";

		flsDateSeasonContainer.appendChild(flsDummyBoxFlex);
		flsDateSeasonContainer.appendChild(flsSeason);
		flsDateContainer.appendChild(flsDateDay);
		flsDateContainer.appendChild(flsDateMonth);
		flsDateSeasonContainer.appendChild(flsDateContainer);
		fruitlegsaisUi.appendChild(flsDateSeasonContainer);

		//Current Fruit/Legume
		let flsFruitLegumeContainer = createElem("div", ["id", "class"], ["flsFruitLegumeContainer", "flsFruitLegumeContainer"]);
		flsContainer.appendChild(flsFruitLegumeContainer);

		//Canvas
		let flsCanvasContainer = createElem("div", ["id", "class"], ["flsCanvasContainer", "flsCanvasContainer"]);
		let flsCanvasMain = createElem("canvas", ["id", "class"], ["flsCanvasMain", "flsCanvasMain"]);

		flsCanvasContainer.appendChild(flsCanvasMain);
		flsContainer.appendChild(flsCanvasContainer);

		this.initCanvas(flsCanvasMain, "flsCtxMain");
		this.initCommands();
		window.requestAnimationFrame(this.refreshGame.bind(this)); 
	}

	launchMainMenu()
	{
		const req = new XMLHttpRequest();
		req.onreadystatechange = function(event)
		{
		    if (this.readyState === XMLHttpRequest.DONE)
		    {
		        if (this.status === 200)
		        {
		            let flsContainer = document.getElementById('flsContainer');
					flsContainer.innerHTML = req.responseText;

					let flsButtonsContainer = createElem("div", ["id", "class"], ["flsButtonsContainer", "flsButtonsContainer"]);
					let flsLaunchGameButton = createElem("button", ["id", "class"], ["flsLaunchGameButton", "buttonDefault flsLaunchGameButton"]);
					flsLaunchGameButton.innerText = "commencer"
					let flsPassBonusButton = createElem("button", ["id", "class"], ["flsPassBonusButton", "buttonDefault flsPassBonusButton"]);
					flsPassBonusButton.innerText = "passer"

					flsButtonsContainer.appendChild(flsLaunchGameButton);
					flsButtonsContainer.appendChild(flsPassBonusButton);
					flsContainer.appendChild(flsButtonsContainer);

					flsLaunchGameButton.onclick = function()
					{
						flsButtonsContainer.classList.add("flexEnd");
						document.getElementById("flsTuto").remove();
						flsLaunchGameButton.remove();
						fruitlegsais.launch();
					}
					flsPassBonusButton.onclick = function()
					{
						document.getElementById("flsContainer").classList.toggle("disabled");
						fruitlegsais.closeGame();
					}
				}
		    }
		};
		req.open('GET', 'view/st_game_flstuto.html', true);
		req.send(null);
		/*let flsTutoContainer = createElem("div", "class", "flsTutoContainer");
		let flsTutoTitle = createElem("h2", "class", "flsTutoTitle");
		flsTutoTitle.innerText = "Partie Bonus";
		let flsTutoContent = createElem("p", "class", "flsTutoContent");
		flsTutoContent.innerHTML = "Bonjour,</br> Un petit Tuto illustré sera présent sur cette page.";
		flsTutoContainer.appendChild(flsTutoTitle);
		flsTutoContainer.appendChild(flsTutoContent);
		flsContainer.appendChild(flsTutoContainer);*/
	}

	closeGame()
	{
		window.cancelAnimationFrame(this.refreshGameLoop);
		let flsContainerChilds = document.querySelectorAll("#flsContainer div");
		for (let i = flsContainerChilds.length - 1; i >= 0; i--)
		{
	        maximizeIntroductionQuestions();
			flsContainerChilds[i].remove();
			fruitlegsais = null;
		}
	}
};

/*
window.addEventListener("load", function()
{
		fruitlegsais = new Fruilegsais();
		fruitlegsais.launchMainMenu();
});*/