// Code from Greg Trowbridge <3 => http://gregtrowbridge.com/a-basic-pathfinding-algorithm/

let gridRowSize;
let gridColSize;
let gridGabarit;
// Start location will be in the following format:
// [distanceFromTop, distanceFromLeft]
let findShortestPath = function(startCoordinates, grid) 
{
  let distanceFromTop = startCoordinates[0];
  let distanceFromLeft = startCoordinates[1];

  // Each "location" will store its coordinates
  // and the shortest path required to arrive there
  let location = 
  {
    distanceFromTop: distanceFromTop,
    distanceFromLeft: distanceFromLeft,
    path: [],
    status: 'Start'
  };

  // Initialize the queue with the start location already inside
  let queue = [location];

  // Loop through the grid searching for the goal
  while (queue.length > 0) 
  {
    // Take the first location off the queue
    let currentLocation = queue.shift();

    // Explore North
    let newLocation = exploreInDirection(currentLocation, 'North', grid);
    if (newLocation.status === 'Goal') 
    {
      return newLocation.path;
    } 
    else if (newLocation.status === 'Valid') 
    {
      queue.push(newLocation);
    }

    // Explore East
    newLocation = exploreInDirection(currentLocation, 'East', grid);
    if (newLocation.status === 'Goal') 
    {
      return newLocation.path;
    } 
    else if (newLocation.status === 'Valid') 
    {
      queue.push(newLocation);
    }

    // Explore South
    newLocation = exploreInDirection(currentLocation, 'South', grid);
    if (newLocation.status === 'Goal') 
    {
      return newLocation.path;
    } 
    else if (newLocation.status === 'Valid') 
    {
      queue.push(newLocation);
    }

    // Explore West
    newLocation = exploreInDirection(currentLocation, 'West', grid);
    if (newLocation.status === 'Goal') 
    {
      return newLocation.path;
    } 
    else if (newLocation.status === 'Valid')
    {
      queue.push(newLocation);
    }
  }

  // No valid path found
  return false;

};

// This function will check a location's status
// (a location is "valid" if it is on the grid, is not an "obstacle",
// and has not yet been visited by our algorithm)
// Returns "Valid", "Invalid", "Blocked", or "Goal"
let locationStatus = function(location, grid) 
{
  let dft = location.distanceFromTop;
  let dfl = location.distanceFromLeft;

  if (location.distanceFromLeft < 0 ||
      location.distanceFromLeft >= gridRowSize ||
      location.distanceFromTop < 0 ||
      location.distanceFromTop >= gridColSize) {

    // location is not on the grid--return false
    return 'Invalid';
  } 
  else if (grid[dft][dfl] === 'Goal') 
  {
    return 'Goal';
  } 
  else if (grid[dft][dfl] !== 'Empty') 
  {
    // location is either an obstacle or has been visited
    return 'Blocked';
  } 
  else 
  {
    return 'Valid';
  }
};


// Explores the grid from the given location in the given
// direction
let exploreInDirection = function(currentLocation, direction, grid) {
  let newPath = currentLocation.path.slice();
  newPath.push(direction);

  let dft = currentLocation.distanceFromTop;
  let dfl = currentLocation.distanceFromLeft;

  if (direction === 'North') 
  {
    dft -= 1;
  } 
  else if (direction === 'East') 
  {
    dfl += 1;
  } 
  else if (direction === 'South') 
  {
    dft += 1;
  } 
  else if (direction === 'West') 
  {
    dfl -= 1;
  }

  let newLocation = {
    distanceFromTop: dft,
    distanceFromLeft: dfl,
    path: newPath,
    status: 'Unknown'
  };
  newLocation.status = locationStatus(newLocation, grid);

  // If this new location is valid, mark it as 'Visited'
  if (newLocation.status === 'Valid') 
  {
    grid[newLocation.distanceFromTop][newLocation.distanceFromLeft] = 'Visited';
  }

  return newLocation;
};


// OK. We have the functions we need--let's run them to get our shortest path!

// Create a 4x4 grid
// Represent the grid as a 2-dimensional array
let initGrid = function()
{
  gridRowSize = Math.ceil(tileNumberByRow / 2);
  gridColSize = Math.ceil(tileNumberByCol / 2);
  gridGabarit = [];
	for (let i=0; i<gridRowSize; i++)
	{
		  gridGabarit[i] = [];
		  for (let j=0; j<gridColSize; j++)
		  {
			  	if (mapBoards[i*2][j*2].wall != 2)
			  	{
			    	gridGabarit[i][j] = 'Empty';
			    }
			    else
			    {
			    	gridGabarit[i][j] = 'Obstacle';    	
			    }
		  }
	}
}

// Think of the first index as "distance from the top row"
// Think of the second index as "distance from the left-most column"

// This is how we would represent the grid with obstacles above


let calculPath = function(ghost, rowOrigin, colOrigin, rowFocus, ColFocus)
{
	let grid = JSON.parse(JSON.stringify(gridGabarit));
	grid[rowOrigin][colOrigin] = "Start";
	grid[rowFocus][ColFocus] = "Goal";
	ghost.path = findShortestPath([rowOrigin,colOrigin], grid);
}