let player = {}
let initPlayer = function() 
{
    player = {
        pacmanImg00: new Image(),
        pacmanImg01: new Image(),
        pacman_bottom00: new Image(),
        pacman_bottom01: new Image(),
        pacman_left00: new Image(),
        pacman_left01: new Image(),
        pacman_top00: new Image(),
        pacman_top01: new Image(),

        size: tileSize * 3,
        posY: tileSize * 39,
        posX: tileSize * 19,
        row: 39,
        col: 19,
        topPressed: false,
        rightPressed: false,
        bottomPressed: false,
        leftPressed: false,
        spacePressed: false,
        spaceStopPressed: true,
        moving: false,
        movingTempo: null,
        movingSpeed: tileSize / 4,
        animationImg: [],
        animationIndex: 0,
        alive: 1,
        pointsByGhost: 100,
        haveGarbage: false,
        garbage: false,
        garbageType: false,
        garbagePosition: null
    };
    player["pacmanImg00"].src = 'assets/img/pacman00.svg';
    player["pacmanImg01"].src = 'assets/img/pacman01.svg';
    player["pacman_bottom00"].src = 'assets/img/pacman_bottom00.svg';
    player["pacman_bottom01"].src = 'assets/img/pacman_bottom01.svg';
    player["pacman_left00"].src = 'assets/img/pacman_left00.svg';
    player["pacman_left01"].src = 'assets/img/pacman_left01.svg';
    player["pacman_top00"].src = 'assets/img/pacman_top00.svg';
    player["pacman_top01"].src = 'assets/img/pacman_top01.svg';
    player["animationImg"] = [player["pacmanImg00"], player["pacmanImg01"], player["pacman_bottom00"], player["pacman_bottom01"], player["pacman_left00"], player["pacman_left01"], player["pacman_top00"], player["pacman_top01"]];
};

let launchGameOver = function()
{
    document.getElementById("gameOver").style.display = "block";
    let waitForGameOverInfos = setTimeout(function()
    {
        let gameOverDiv = document.getElementById("gameOver");
        let gameOverInfos = document.createElement("div");
        gameOverInfos.setAttribute("class", "gameOverInfos");
        gameOverInfos.innerText = "Appuie sur une touche ou clique sur l'écran pour passer à la suite du questionnaire.";
        gameOverDiv.appendChild(gameOverInfos);
        document.addEventListener("keydown", closeGame, false);
        window.addEventListener("click", closeGame, false);
    }, 1500);
}

let launchBonus = function()
{
    player["pointsByGhost"] = 10;
    clearTimeout(bonusTempo);
    for (let i = ghosts.length - 1; i >= 0; i--)
    {
        ghosts[i]["alive"] = 1; 
        if (ghosts[i]["state"] != "start" && ghosts[i]["state"] != "dead")
        {        
            ghosts[i].state = "afraid";
            ghosts[i]["display"] = "afraid"
            ghosts[i]["path"] = [];
        }
    }
    bonusTempo = setTimeout(function()
    {
        clearTimeout(bonusTempo);   
        for (let i = ghosts.length - 1; i >= 0; i--)
        {
            if (ghosts[i]["alive"] == 1 && ghosts[i]["state"] == "afraid")
            {     
                ghosts[i].state = "afraidFlash";
                ghosts[i]["display"] = "afraidFlash"
            }
        } 
        bonusTempo = setTimeout(function()
        {
            clearTimeout(bonusTempo);
            for (let i = ghosts.length - 1; i >= 0; i--)
            {    
                clearInterval(ghosts[i].afraidFlashTempo);
                if (ghosts[i]["state"] != "start")
                {
                    ghosts[i]["path"] = [];
                }
                if (ghosts[i]["state"] != "dead")
                {   
                    ghosts[i]["display"] = "normal"
                    ghosts[i].state = "hunt";
                    ghosts[i].afraidFlashTempo = false;
                }
            }
        },3000);

    },5000);
}

let cleanFood = function(row, col)
{
    if (player["haveGarbage"] == false)
    {
        clearInterval(mapBoards[row][col].foodNegatifTime);
        player["haveGarbage"] = true;
        mapBoards[player["row"]][player["col"]].garbageHere = false;
        mapBoards[player["row"]][player["col"]].garbage = false;
        let centerRow = row + 1;
        let centerCol = col + 1;
        let distance = 2;
        let distanceMax = 8;
        mapBoards[row][col].foodNegatifTime = setInterval(function()
        {
            let rowToChange = -1*distance;
            let colToChange = -1*distance;
            mapBoards[centerRow - distance][centerCol].foodPositif = true;
            for (let i = ((distance+1) * (distance+1)) - 1; i >= 0; i--)
            {
                if (mapBoards[centerRow + rowToChange][centerCol + colToChange].foodPoints == -10)
                {
                    mapBoards[centerRow + rowToChange][centerCol + colToChange].foodPoints = -5;                    
                }
                else if (mapBoards[centerRow + rowToChange][centerCol + colToChange].foodPoints == -5)
                {
                    mapBoards[centerRow + rowToChange][centerCol + colToChange].foodPoints = -1;                    
                }
                else if (mapBoards[centerRow + rowToChange][centerCol + colToChange].foodPoints == -1)
                {
                    mapBoards[centerRow + rowToChange][centerCol + colToChange].foodPoints = 1;                   
                }                    
                if (colToChange < distance)
                {
                    colToChange += 2;
                }
                else
                {
                    colToChange = -1*distance;
                    rowToChange += 2;
                }
            }
            updateFood();
            if (distance < distanceMax)
            {
                distance += 2;
            }
            if (mapBoards[centerRow - 2][centerCol].foodPoints == 1 && mapBoards[centerRow - 4][centerCol].foodPoints == 1 && mapBoards[centerRow - 8][centerCol].foodPoints == 1)
            {
                clearInterval(mapBoards[row][col].foodNegatifTime); 
            }
        }, 2000)
    }
}

let playerRecyclingGarbage = function()
{
    let row = player["row"];
    let col = player["col"];
    if (typeof mapBoards[row][col] != "undefined" && typeof mapBoards[row][col + 1] != "undefined" && typeof mapBoards[row][col + 2] != "undefined" && typeof mapBoards[row][col - 1] != "undefined" && typeof mapBoards[row][col - 2] != "undefined")
    {
        if (player["garbageType"] == "pmc" && (mapBoards[row][col].type == "4" || mapBoards[row + 1][col].type == "4" || mapBoards[row + 2][col].type == "4"))
        {
            player["garbage"] = false;
            player["garbageType"] = false;
            player["haveGarbage"] = false;
            /*updateScore(100, true);
            displayPointsOnMapBoard(20, player["posX"], player["posY"], 1000);*/
        }
        else if (player["garbageType"] == "colore" && (mapBoards[row][col].type == "5" || mapBoards[row][col + 1].type == "5" || mapBoards[row][col + 2].type == "5"))
        {
            player["garbage"] = false;
            player["garbageType"] = false;
            player["haveGarbage"] = false;
            /*updateScore(100, true);
            displayPointsOnMapBoard(20, player["posX"], player["posY"], 1000);*/
        }
        else if (player["garbageType"] == "incolore" && mapBoards[row][col].type == "6")
        {
            player["garbage"] = false;
            player["garbageType"] = false;
            player["haveGarbage"] = false;
            /*updateScore(100, true);
            displayPointsOnMapBoard(20, player["posX"], player["posY"], 1000);*/
        }  
        else if (player["garbageType"] == "carton" && (mapBoards[row][col].type == "7" || mapBoards[row + 1][col].type == "7" || mapBoards[row + 2][col].type == "7" || mapBoards[row][col + 1].type == "7" || mapBoards[row][col + 2].type == "7"))
        {
            player["garbage"] = false;
            player["garbageType"] = false;
            player["haveGarbage"] = false;
            /*updateScore(100, true);
            displayPointsOnMapBoard(20, player["posX"], player["posY"], 1000);*/
        }
    }
}

let displayPointsOnMapBoard = function(points, posX, posY, time)
{
    let displayPoints = setInterval(function()
    {
        ctxFood.font = "18px Arial";
        ctxFood.fillStyle = "white";
        ctxFood.fillText(points, posX, posY);
    }, 17)
    let stopDisplayPoints = setTimeout(function()
    {
        clearInterval(displayPoints);
    }, time);
}

let takeGarbage = function(row, col)
{
    if (player["haveGarbage"] == false)
    {
        let garbagesListIndex = mapBoards[row][col]["garbage"];
        player["garbage"] = garbagesImages[garbagesListIndex];
        if (garbagesImages[garbagesListIndex].src.includes("carton") == true)
        {
            player["garbageType"] = "carton";
        }
        else if (garbagesImages[garbagesListIndex].src.includes("pmc") == true)
        {
            player["garbageType"] = "pmc";
        }
        else if (garbagesImages[garbagesListIndex].src.includes("incolore") == true)
        {
            player["garbageType"] = "incolore";
        }
        else if (garbagesImages[garbagesListIndex].src.includes("colore") == true)
        {
            player["garbageType"] = "colore";
        }
    }
    cleanFood(row, col);
}

let updateScore = function(points)
{
    let score = document.querySelector("#score");
    score.innerText = parseInt(score.innerText) + points;
    if (parseInt(score.innerText) < 0)
    {
        score.innerText = 0;
    }
}

let takeFood = function(row, col)
{
    if (mapBoards[row][col].type == 1 && mapBoards[row][col].foodTime == false)
    {
        mapBoards[row][col].foodTime =  setTimeout(function()
        {
            mapBoards[row][col].foodTime = false;
            updateFood();
        }, 20000);
        ctxFood.clearRect(0, 0, canvasPlayer.width, canvasPlayer.height);
        updateFood();
        updateScore(mapBoards[row][col].foodPoints);
    }
    else if (mapBoards[row][col].type == 2 && mapBoards[row][col].bonus == true)
    {
        mapBoards[row][col].bonus = false;
        ctxFood.clearRect(0, 0, canvasPlayer.width, canvasPlayer.height);
        updateFood();
        updateScore(5);
        launchBonus();
    }
}

// -- KEYS --

let keyDownHandler = function(e)
{
    if (player.alive == 1)
    {
        //joueur 1
        if(e.keyCode == 39)
        {
            player.rightPressed = true;
        }
        else if(e.keyCode == 37)
        {
            player.leftPressed = true;
        }
        else if(e.keyCode == 40)
        {
            player.bottomPressed = true;
        }
        else if(e.keyCode == 38)
        {
            player.topPressed = true;
        }
        else if(e.keyCode == 32 && player.spaceStopPressed == true)
        {
            player.spacePressed = true;
            player.spaceStopPressed = false;
        }
    }
}

let keyUpHandler = function(e)
{
    if (player.alive == 1)
    {
        //joueur 1
        if(e.keyCode == 39)
        {
            player.rightPressed = false;
        }
        else if(e.keyCode == 37)
        {
            player.leftPressed = false;
        }
        else if(e.keyCode == 40)
        {
            player.bottomPressed = false;
        }
        else if(e.keyCode == 38)
        {
            player.topPressed = false;
        }
        else if(e.keyCode == 32)
        {
            player.spacePressed = false;
            player.spaceStopPressed = true;
        }
    }
}

// -- MOVES --

let collisions = function()
{

}

let drawPlayer = function()
{
    let playerPosArrayCol = player.posX / tileSize;
    let playerPosArrayRow = player.posY / tileSize;
    // MOVE TOP
    if (player.moving == false && player.topPressed == true)
    {
        if (typeof mapBoards[playerPosArrayRow - 1][playerPosArrayCol] != "undefined")
        {
            if (mapBoards[playerPosArrayRow - 1][playerPosArrayCol].wall < 1 && mapBoards[playerPosArrayRow - 1][playerPosArrayCol + 1].wall < 1 && mapBoards[playerPosArrayRow - 1][playerPosArrayCol + 2].wall < 1)
            {
                    let oldPosY = player.posY;
                    player.garbagePosition = "top";
                    player.moving = true;
                    player.movingTempo = setInterval(function()
                    {
                        player.posY -= player.movingSpeed;
                        if (player.posY == oldPosY - 2 * tileSize)
                        {
                            player.animationIndex = player.animationIndex == 6 ? player.animationIndex + 1 : 6;
                            takeFood(playerPosArrayRow - 1, playerPosArrayCol + 1)
                            player.moving = false;
                            player.row = player.posY / tileSize;
                            player.col = player.posX / tileSize;
                            clearInterval(player.movingTempo);
                        }
                    },17);
            }
        }
    }
    // MOVE BOTTOM
    if (player.moving == false && player.bottomPressed == true)
    {
        if (typeof mapBoards[playerPosArrayRow + 3][playerPosArrayCol] != "undefined")
        {
            if (mapBoards[playerPosArrayRow + 3][playerPosArrayCol].wall < 1 && mapBoards[playerPosArrayRow + 3][playerPosArrayCol + 1].wall < 1 && mapBoards[playerPosArrayRow + 3][playerPosArrayCol + 2].wall < 1)
            {
                    let oldPosY = player.posY;
                    player.garbagePosition = "bottom";
                    player.moving = true;
                    player.movingTempo = setInterval(function()
                    {   
                        player.posY += player.movingSpeed;
                        if (player.posY == oldPosY + 2 * tileSize)
                        {
                            player.animationIndex = player.animationIndex == 2 ? player.animationIndex + 1 : 2;
                            takeFood(playerPosArrayRow + 3, playerPosArrayCol + 1)
                            player.moving = false;
                            player.row = player.posY / tileSize;
                            player.col = player.posX / tileSize;
                            clearInterval(player.movingTempo);
                        }
                    },17);
            }
        }
    }
    // MOVE RIGHT
    if (player.moving == false && player.rightPressed == true)
    {
        if (typeof mapBoards[playerPosArrayRow][playerPosArrayCol + 3] != "undefined")
        {
            if (mapBoards[playerPosArrayRow][playerPosArrayCol + 3].wall < 1 && mapBoards[playerPosArrayRow + 1][playerPosArrayCol + 3].wall < 1 && mapBoards[playerPosArrayRow + 2][playerPosArrayCol + 3].wall < 1)
            {
                let oldPosX = player.posX;
                player.garbagePosition = "right";
                player.moving = true;
                player.movingTempo = setInterval(function()
                {
                    player.posX += player.movingSpeed;
                    if (player.posX == oldPosX + 2 * tileSize)
                    {
                        player.animationIndex = player.animationIndex == 0 ? player.animationIndex + 1 : 0;
                        takeFood(playerPosArrayRow + 1, playerPosArrayCol + 3)
                        player.moving = false;
                        player.row = player.posY / tileSize;
                        player.col = player.posX / tileSize;
                        clearInterval(player.movingTempo);
                    }
                },17);
            }
        }
        else
        {
            player.posX = -1*tileSize;
        }
    }
    // MOVE LEFT
    if (player.moving == false && player.leftPressed == true)
    {        
        if (typeof mapBoards[playerPosArrayRow][playerPosArrayCol - 1] != "undefined")
        {
            if (mapBoards[playerPosArrayRow][playerPosArrayCol - 1].wall < 1 && mapBoards[playerPosArrayRow + 1][playerPosArrayCol - 1].wall < 1 && mapBoards[playerPosArrayRow + 2][playerPosArrayCol - 1].wall < 1)
            {
                let oldPosX = player.posX;
                player.garbagePosition = "left";
                player.moving = true;
                player.movingTempo = setInterval(function()
                {
                    player.posX -= player.movingSpeed;
                    if (player.posX == oldPosX - 2 * tileSize)
                    {
                        player.animationIndex = player.animationIndex == 4 ? player.animationIndex + 1 : 4;
                        takeFood(playerPosArrayRow + 1, playerPosArrayCol - 1)
                        player.moving = false;
                        player.row = player.posY / tileSize;
                        player.col = player.posX / tileSize;
                        clearInterval(player.movingTempo);
                    }
                },17);
            }
        }
        else
        {
            player.posX = (tileNumberByCol * tileSize) - (2 * tileSize);
        }
    }
    if (player["haveGarbage"] == false && typeof mapBoards[player["row"]] != "undefined" && typeof mapBoards[player["row"]][player["col"]] != "undefined" && mapBoards[player["row"]][player["col"]].garbageHere == true)
    {
        takeGarbage(player["row"], player["col"]);
    }
    if (player["haveGarbage"] == true)
    {
        playerRecyclingGarbage(player["row"], player["col"]);
    }
    // display player
    ctxPlayer.drawImage(player.animationImg[player.animationIndex], player.posX, player.posY, player.size, player.size);
    // display garbage on player
    if (player["haveGarbage"] == true)
    {
        if (player.garbagePosition == "right")
        {
            ctxPlayer.drawImage(player["garbage"], player.posX - tileSize, player.posY + (tileSize / 2), tileSize * 2, tileSize * 2);
        }
        else if (player.garbagePosition == "bottom")
        {
            ctxPlayer.drawImage(player["garbage"], player.posX + (tileSize / 2), player.posY - tileSize, tileSize * 2, tileSize * 2);
        }
        else if (player.garbagePosition == "left")
        {
            ctxPlayer.drawImage(player["garbage"], player.posX + (tileSize * 2), player.posY + (tileSize / 2), tileSize * 2, tileSize * 2);
        }
        else if (player.garbagePosition == "top")
        {
            ctxPlayer.drawImage(player["garbage"], player.posX + (tileSize / 2), player.posY + (tileSize * 2), tileSize * 2, tileSize * 2);
        }
    }
}

// -- LOAD PAD FOR MOBILES --
function loadPadMobiles()
{
    let padTop = document.querySelector(".mobilePadTop");
    let padRight = document.querySelector(".mobilePadRight");
    let padBottom = document.querySelector(".mobilePadBottom");
    let padLeft = document.querySelector(".mobilePadLeft");

    document.getElementById("mobilePad").style.display = "block";

    let handleUpMobile = function(event)
    {
        if (event.target == padTop)
        {
            player["topPressed"] = false;
        }
        else if (event.target == padRight)
        {
             player["rightPressed"] = false;           
        }
        else if (event.target == padBottom)
        {
             player["bottomPressed"] = false;           
        }        
        else if (event.target == padLeft)
        {
             player["leftPressed"] = false;           
        }
    }

    let handleDownMobile = function(event)
    {
        event.preventDefault();
        if (player["alive"] == 1)
        {
            if (event.target == padTop)
            {
                player["topPressed"] = true;
            }
            else if (event.target == padRight)
            {
                 player["rightPressed"] = true;           
            }
            else if (event.target == padBottom)
            {
                 player["bottomPressed"] = true;           
            }        
            else if (event.target == padLeft)
            {
                 player["leftPressed"] = true;           
            }
        }
    }

    padTop.addEventListener("touchstart", handleDownMobile, false);
    padRight.addEventListener("touchstart", handleDownMobile, false);
    padBottom.addEventListener("touchstart", handleDownMobile, false);
    padLeft.addEventListener("touchstart", handleDownMobile, false);
    padTop.addEventListener("touchend", handleUpMobile, false);
    padRight.addEventListener("touchend", handleUpMobile, false);
    padBottom.addEventListener("touchend", handleUpMobile, false);
    padLeft.addEventListener("touchend", handleUpMobile, false);
}