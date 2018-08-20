let ghosts;

let initGhosts = function()
{
    let ghost_afraid = new Image();
    let ghost_afraidFlash = new Image();
    let ghost_dead = new Image();

    ghost_afraid.src = 'assets/img/ghost_afraid.svg';
    ghost_afraidFlash.src = 'assets/img/ghost_afraidFlash.svg';
    ghost_dead.src = 'assets/img/ghost_dead.svg';

    let ghost =
    {
        name: "",
        display: "normal",
        img: {},
        startAt: 0,
        startPath: [],
        state: "start",
        busy: true,
        afraidFlashTempo: false,
        afraidFlashSwitch: {},
        size: tileSize * 3,
        posY: tileSize * 15,
        posX: tileSize * 19,
        row: 15,
        col: 19,
        path: [],
        lastImg: 0,
        movingTempo: null,
        movingSpeedOrigin: tileSize / 8,
        movingSpeed: tileSize / 8,
        alive: 1,
        pointsByGhost: 10,
        pointsByGhostTempo: false,
        displayPointsByGhostPosX: false,
        displayPointsByGhostPosY: false,
        wantDropGarbage: false,
        garbageTime: null
    };
    ghosts = [];

    // red
    let ghost_red_left = new Image();
    ghost_red_left.src = 'assets/img/ghost_red_left.svg';
    let ghost_red_right = new Image();
    ghost_red_right.src = 'assets/img/ghost_red_right.svg';
    let ghost_red_top = new Image();
    ghost_red_top.src = 'assets/img/ghost_red_top.svg';
    let ghost_red_bottom = new Image();
    ghost_red_bottom.src = 'assets/img/ghost_red_bottom.svg';
    let red = JSON.parse(JSON.stringify(ghost));

    red["img"] = [ghost_red_top, ghost_red_right, ghost_red_bottom, ghost_red_left];
    red["startAt"] = 0;
    red["startPath"] = ["East", "North", "North", "West", "North", "North"];
    red["posY"] = tileSize * 19;
    red["posX"] = tileSize * 17;
    red["row"] = 19;
    red["col"] = 17;
    red["name"] = "red";
    ghosts.push(red);

    // green
    let ghost_green_left = new Image();
    ghost_green_left.src = 'assets/img/ghost_green_left.svg';
    let ghost_green_right = new Image();
    ghost_green_right.src = 'assets/img/ghost_green_right.svg';
    let ghost_green_top = new Image();
    ghost_green_top.src = 'assets/img/ghost_green_top.svg';
    let ghost_green_bottom = new Image();
    ghost_green_bottom.src = 'assets/img/ghost_green_bottom.svg';
    let green = JSON.parse(JSON.stringify(ghost));
    green["img"] = [ghost_green_top, ghost_green_right, ghost_green_bottom, ghost_green_left];
    green["startAt"] = 1000;
    green["startPath"] = ["North", "North", "East", "North", "North"];
    green["posY"] = tileSize * 19;
    green["posX"] = tileSize * 19;
    green["row"] = 19;
    green["col"] = 19;
    green["name"] = "green";
    green["wantDropGarbage"] = true;
    green["garbageTime"] = setInterval(function()
    {
    	green["wantDropGarbage"] = true;
    }, 10000)
    ghosts.push(green);
    /*
    // orange
    let ghost_orange_left = new Image();
    ghost_orange_left.src = 'assets/img/ghost_orange_left.svg';
    let ghost_orange_right = new Image();
    ghost_orange_right.src = 'assets/img/ghost_orange_right.svg';
    let ghost_orange_top = new Image();
    ghost_orange_top.src = 'assets/img/ghost_orange_top.svg';
    let ghost_orange_bottom = new Image();
    ghost_orange_bottom.src = 'assets/img/ghost_orange_bottom.svg';
    let orange = JSON.parse(JSON.stringify(ghost));
    orange["img"] = [ghost_orange_top, ghost_orange_right, ghost_orange_bottom, ghost_orange_left];
    orange["startAt"] = 2000;
    orange["startPath"] = ["East", "North", "North", "West", "North", "North"];
    orange["posY"] = tileSize * 19;
    orange["posX"] = tileSize * 17;
    orange["row"] = 19;
    orange["col"] = 17;
    orange["name"] = "orange";
    orange["switchMove"] = "rand";
    ghosts.push(orange);*/

    // pink
    let ghost_pink_left = new Image();
    ghost_pink_left.src = 'assets/img/ghost_pink_left.svg';
    let ghost_pink_right = new Image();
    ghost_pink_right.src = 'assets/img/ghost_pink_right.svg';
    let ghost_pink_top = new Image();
    ghost_pink_top.src = 'assets/img/ghost_pink_top.svg';
    let ghost_pink_bottom = new Image();
    ghost_pink_bottom.src = 'assets/img/ghost_pink_bottom.svg';
    let pink = JSON.parse(JSON.stringify(ghost));
    pink["img"] = [ghost_pink_top, ghost_pink_right, ghost_pink_bottom, ghost_pink_left];
    pink["startAt"] = 3000;
    pink["startPath"] = ["West", "North", "North", "East", "North", "North"];
    pink["posY"] = tileSize * 19;
    pink["posX"] = tileSize * 21;
    pink["row"] = 19;
    pink["col"] = 21;
    pink["name"] = "pink";
    pink["switchMove"] = "embush";
    ghosts.push(pink);
    for (let i = ghosts.length - 1; i >= 0; i--)
    {
        ghosts[i]["ghost_afraid"] = ghost_afraid;
        ghosts[i]["ghost_afraidFlash"] = ghost_afraidFlash;
        ghosts[i]["ghost_dead"] = ghost_dead;
    }
}

let moveRandomGhost = function(ghost)
{
    let rand = Math.floor((Math.random() * (row4Rand.length - 1)) + 1); 
    calculPath(ghost, Math.ceil(ghost.row/2), Math.ceil(ghost.col/2), row4Rand[rand], col4Rand[rand]);    
}

let searchWayForEscape = function(ghost)
{
    /*if (ghost.row < tileNumberByRow / 2)
    {
        if (ghost.col < tileNumberByCol /2)
        {

        }
    }*/
    moveRandomGhost(ghost);
}

let backAtSpawn = function(ghost)
{
    if (mapBoards[ghost.row][ghost.col].type == 3)
    {
        ghost["state"] = "start";
        ghost["display"] = "normal";
        startGhost(ghost);
        return;
    }
    if (ghost["path"].length == 0)
    {
        ghost["afraidFlashTempo"] = false;
        ghost["movingSpeed"] = tileSize / 4;
        calculPath(ghost, Math.ceil(ghost.row/2), Math.ceil(ghost.col/2), 8, 10);
        ghost["path"].push("South", "South");
    }
}

let givePointsByGhost = function(ghost)
{
    player["pointsByGhost"] *= 2;
    ghost["pointsByGhost"] = player["pointsByGhost"];
    updateScore(player["pointsByGhost"], true);
    ghost["displayPointsByGhostPosX"] = ghost["posX"] + tileSize;
    ghost["displayPointsByGhostPosY"] = ghost["posY"];
    ghost["pointsByGhostTempo"] = setTimeout(function()
    {
    	ghost["displayPointsByGhostPosX"] = false;
    	ghost["displayPointsByGhostPosY"] = false;
    }, 1000);
}

let displayPointsByGhost = function()
{
	for (let i = ghosts.length - 1; i >= 0; i--)
	{
		if (ghosts[i]["displayPointsByGhostPosX"] != false && ghosts[i]["displayPointsByGhostPosY"] != false)
		{
			ctxFood.font = "18px Arial";
			ctxFood.fillStyle = "white";
			ctxFood.fillText(ghosts[i]["pointsByGhost"], ghosts[i]["displayPointsByGhostPosX"], ghosts[i]["displayPointsByGhostPosY"]);
		}
	}
}

let checkCollisionWithPlayer = function(ghost)
{
    if (player["alive"] == 1 && ghost.posX > player.posX - (ghost.size / 2) && ghost.posX < player.posX + (player.size / 2) && ghost.posY > player.posY - (ghost.size / 2) && ghost.posY < player.posY + (player.size / 2))
    {
        if (ghost.state == "afraid" || ghost.state == "afraidFlash")
        {
            ghost["state"] = "dead";
            ghost["display"] = "dead";
            ghost["path"] = [];
            ghost["alive"] = 0;
            givePointsByGhost(ghost);
        	displayPointsByGhost(ghost);
        }
        else if (ghost.state != "afraid" && ghost.state != "afraidFlash" && ghost.state != "dead")
        {
        	player["alive"] = 0;
        	player["topPressed"] = false;
        	player["leftPressed"] = false;
        	player["rightPressed"] = false;
        	player["bottomPressed"] = false;
        	launchGameOver();
        }
    }
} 

let moveGhost = function(ghost)
{
    ghost["busy"] = true;
    if (typeof ghost["path"][0] != "undefined")
    {
        if (ghost["path"][0] == "North")
        {
            ghost["path"].splice(0, 1);
            ghost.movingTempo = setInterval(function()
            {   
                ghost["posY"] -= ghost["movingSpeed"];
                if (ghost["posY"] / tileSize == ghost["row"] - 2)
                {
                    ghost["row"] -= 2;
                    clearInterval(ghost.movingTempo);
                    ghost["busy"] = false;
                }
            },17);
        }
        else if (ghost["path"][0] == "West")
        {
            ghost["path"].splice(0, 1);
            ghost["movingTempo"] = setInterval(function()
            {   
                ghost["posX"] -= ghost["movingSpeed"];
                if (ghost["posX"] / tileSize == ghost["col"] - 2)
                {
                    ghost["col"] -= 2;
                    clearInterval(ghost.movingTempo);
                    ghost["busy"] = false;
                }
            },17);
        }
        else if (ghost["path"][0] == "East")
        {
            ghost["path"].splice(0, 1);
            ghost["movingTempo"] = setInterval(function()
            {   
                ghost["posX"] += ghost["movingSpeed"];
                if (ghost["posX"] / tileSize == ghost["col"] + 2)
                {
                    ghost["col"] += 2;
                    clearInterval(ghost.movingTempo);
                    ghost["busy"] = false;
                }
            },17);
        }
        else if (ghost["path"][0] == "South")
        {
            ghost["path"].splice(0, 1);
            ghost["movingTempo"] = setInterval(function()
            {   
                ghost["posY"] += ghost["movingSpeed"];
                if (ghost["posY"] / tileSize == ghost["row"] + 2)
                {
                    ghost["row"] += 2;
                    clearInterval(ghost.movingTempo);
                    ghost["busy"] = false;
                }
            },17);
        }
    }
    else
    {
        ghost["busy"] = false;
    }
}

let startGhost = function(ghost)
{
    if (ghost["path"].length == 0)
    {
        ghost["busy"] = true;
        ghost.movingTempo = setTimeout(function()
        {           
            ghost["movingSpeed"] = ghost["movingSpeedOrigin"];
            ghost["path"] = ghost["startPath"];
            ghost["state"] = "hunt";
            ghost["startAt"] = 1000;
            ghost["startPath"] = ["North", "North"];
            ghost["busy"] = false;
            clearTimeout(ghost.movingTempo);
        },ghost["startAt"]);
    }
}

let drawGarbage = function()
{
	let garbagePositionListRow = [7, 7, 31, 31];
	let garbagePositionListCol = [9, 29, 9, 29];	
	for (let i = garbagePositionListRow.length - 1; i >= 0; i--)
	{
		let row = garbagePositionListRow[i];
		let col = garbagePositionListCol[i];
		let garbageIndex = mapBoards[row][col].garbage;
		if (mapBoards[row][col].garbage !== false)
		{
			ctxPlayer.drawImage(garbagesImages[garbageIndex], col * tileSize, row * tileSize, tileSize * 3, tileSize * 3);
		}
	}
}

let dropGarbage = function(row, col)
{
	clearInterval(mapBoards[row][col].foodNegatifTime);
	let centerRow = row + 1;
	let centerCol = col + 1;
	let distance = 2;
	let distanceMax = 8;
	let rand = Math.floor((Math.random() * (garbagesList.length - 1)) + 0);
	mapBoards[row][col].garbage = rand;
	mapBoards[row][col].foodNegatifTime = setInterval(function()
	{
		let rowToChange = -1*distance;
		let colToChange = -1*distance;
		for (let i = ((distance+1) * (distance+1)) - 1; i >= 0; i--)
		{
			if (mapBoards[centerRow + rowToChange][centerCol + colToChange].foodPoints == 1)
			{
				mapBoards[centerRow + rowToChange][centerCol + colToChange].foodPoints = -1;					
			}
			else if (mapBoards[centerRow + rowToChange][centerCol + colToChange].foodPoints == -1)
			{
				mapBoards[centerRow + rowToChange][centerCol + colToChange].foodPoints = -5;					
			}
			else if (mapBoards[centerRow + rowToChange][centerCol + colToChange].foodPoints == -5)
			{
				mapBoards[centerRow + rowToChange][centerCol + colToChange].foodPoints = -10;					
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
		if (mapBoards[centerRow - 2][centerCol].foodPoints == -10 && mapBoards[centerRow - 4][centerCol].foodPoints == -10 && mapBoards[centerRow - 8][centerCol].foodPoints == -10)
		{
			clearInterval(mapBoards[row][col].foodNegatifTime);	
		}
	}, 4000)
}

let chooseGarbagePosition = function(ghost)
{
	let garbagePositionListRow = [7, 7, 31, 31];
	let garbagePositionListCol = [9, 29, 9, 29];
	let garbagePositionListRowFree = [];
	let garbagePositionListColFree = [];
	for (let i = garbagePositionListRow.length - 1; i >= 0; i--)
	{
		let row = garbagePositionListRow[i];
		let col = garbagePositionListCol[i];
		if (mapBoards[row][col].garbageHere == false)
		{
			garbagePositionListRowFree.push(row);
			garbagePositionListColFree.push(col);
		}
	}
	if (garbagePositionListRowFree.length > 0)
	{
		let rand = Math.floor((Math.random() * (garbagePositionListRowFree.length - 1)) + 0);
		let row = garbagePositionListRowFree[rand];
		let col = garbagePositionListColFree[rand];
        calculPath(ghost, Math.ceil(ghost.row/2), Math.ceil(ghost.col/2), Math.ceil(row/2), Math.ceil(col/2));
	}
	else
	{
		ghost["wantDropGarbage"] = false;
		ghost["state"] = "hunt";
	}
}

let manageGhosts = function()
{
    for (let i = ghosts.length - 1; i >= 0; i--)
    {
        let ghost = ghosts[i];
        checkCollisionWithPlayer(ghost);
        // moves
        if (ghost["state"] == "start" && ghost.movingTempo == null)
        {        
            startGhost(ghost);
        }

        if (ghost["busy"] == false)
        {
            if (ghost["path"].length == 0)
            {
             	if (ghost["state"] == "dropGarbage")
            	{
            		let row = ghost["posY"] / tileSize;
            		let col = ghost["posX"] / tileSize;
            		if (mapBoards[row][col].garbageHere == false)
            		{
            			mapBoards[row][col].garbageHere = true;
            			ghost["wantDropGarbage"] = false;
            			ghost["state"] = "hunt";
            			dropGarbage(row, col);
            		}
            		else
            		{
            			chooseGarbagePosition(ghost);
            		}
            	}           	
            	else if (ghost["wantDropGarbage"] == true && ghost["state"] != "start" && ghost["state"] != "afraid" && ghost["state"] != "afraidFlash" && ghost["state"] != "dead")
            	{
            		ghost["state"] = "dropGarbage";
            		chooseGarbagePosition(ghost);
            	}
                else if (ghost["state"] == "hunt")
                {
                    if (ghost["name"] == "red")
                    {
                        calculPath(ghost, Math.ceil(ghost.row/2), Math.ceil(ghost.col/2), Math.ceil(player.row/2), Math.ceil(player.col/2));
                    }
                    else if (ghost["name"] == "green")
                    {
                        moveRandomGhost(ghost);
                    }
                    else if (ghost["name"] == "pink" || ghost["name"] == "orange")
                    {
                    	if (player["row"] == 19 && player["col"] >= 0 && player["col"] <= 8 || player["row"] == 19 && player["col"] >= 33 && player["col"] <= 41)
                    	{
							if (ghost["name"] == "pink" )
							{
	                        	calculPath(ghost, Math.ceil(ghost.row/2), Math.ceil(ghost.col/2), 10, 0);
							}
							else
							{
	                        	calculPath(ghost, Math.ceil(ghost.row/2), Math.ceil(ghost.col/2), 10, 20);
							}
                    	}
                    	else
                    	{
	                    	if (ghost["switchMove"] == "rand")
	                    	{
	                        	moveRandomGhost(ghost);
	                        	ghost["switchMove"] = "embush";
	                        }
	                        else
	                        {
	                        	calculPath(ghost, Math.ceil(ghost.row/2), Math.ceil(ghost.col/2), Math.ceil(player.row/2), Math.ceil(player.col/2));
	                        	ghost["switchMove"] = "rand";
	                        }
	                    }
                    }
                }
                else if (mapBoards[ghost["row"]][ghost["col"]].type != 3 && (ghost["state"] == "afraid" || ghost["state"] == "afraidFlash"))
                {
                    searchWayForEscape(ghost);
                }
                else if (ghost["state"] == "dead")
                {
                    backAtSpawn(ghost);
                }
            }
            moveGhost(ghost);
        }
        // display
        if (ghost["display"] == "afraid")
        {
            ctxGhosts.drawImage(ghost["ghost_afraid"], ghost.posX, ghost.posY, ghost.size, ghost.size);
        }
        else if (ghost["display"] == "afraidFlash")
        {
            if (ghost.afraidFlashTempo == false)
            {
                ghost["afraidFlashSwitch"] = ghost["ghost_afraidFlash"];
                ghost.afraidFlashTempo = setInterval(function()
                {   
                    ghost["afraidFlashSwitch"] = ghost["afraidFlashSwitch"] == ghost["ghost_afraid"] ? ghost["ghost_afraidFlash"] : ghost["ghost_afraid"];
                },500);
            }
            ctxGhosts.drawImage(ghost["afraidFlashSwitch"], ghost.posX, ghost.posY, ghost.size, ghost.size);
        }
        else if (ghost["display"] == "dead")
        {
            ctxGhosts.drawImage(ghost["ghost_dead"], ghost.posX, ghost.posY, ghost.size, ghost.size);
        }
        else 
        {
        	if (ghost["path"][0] == "North")
        	{
            	ctxGhosts.drawImage(ghost["img"][0], ghost.posX, ghost.posY, ghost.size, ghost.size);
            	ghost["lastImg"] = 0;
        	}
        	else if (ghost["path"][0] == "East")
        	{
            	ctxGhosts.drawImage(ghost["img"][1], ghost.posX, ghost.posY, ghost.size, ghost.size);
            	ghost["lastImg"] = 1;
        	}
        	else if (ghost["path"][0] == "South")
        	{
            	ctxGhosts.drawImage(ghost["img"][2], ghost.posX, ghost.posY, ghost.size, ghost.size);
            	ghost["lastImg"] = 2;
        	}
        	else if (ghost["path"][0] == "West")
        	{
            	ctxGhosts.drawImage(ghost["img"][3], ghost.posX, ghost.posY, ghost.size, ghost.size);
            	ghost["lastImg"] = 3;
        	}
        	else
        	{
        		ctxGhosts.drawImage(ghost["img"][ghost["lastImg"]], ghost.posX, ghost.posY, ghost.size, ghost.size);
        	}
        }
    }

}