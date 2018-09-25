/*  
 *  in = array with objects
 *  out = array
 */
let reverse = 1;
let convertObjectsPropertyToArray = function(objs, key, byWhat)
{
    let array = [];
    for (let i = objs.length - 1; i >= 0; i--)
    {
      if (byWhat == "nickname")
      {
          array.push(objs[i][key]);
      }
      else if (byWhat == "stats")
      {
         array.push( objs[i]["stats"]["average"][key]);
      }
    }
    array.sort();
    if (reverse == 1)
    {
        array.reverse();
    }
    reverse *= -1;
    return array;
}

// change array order who contain objects
function sortObjectsByProperty (array, order, key, byWhat)
{
    array.sort(function (a, b)
    {
      if (byWhat == "nickname")
      {
          return order.indexOf(a[key]) - order.indexOf(b[key]);
      }
      else if (byWhat == "stats")
      {
          return order.indexOf(a["stats"]["average"][key]) - order.indexOf(b["stats"]["average"][key]);
      }
    });

};

// -- CREATE DOM ELEMENTS --
let createDomElem = function (type, attributes)
{
  let newElem = document.createElement(type);
  for (let i = attributes[0].length - 1; i >= 0; i--)
  {
    newElem.setAttribute(attributes[0][i], attributes[1][i]);
  }
  return newElem;
}

// -- GEO --
let convertAngleToRadians = function(angle)
{
  return angle * (Math.PI / 180);
}
let convertRadiansToAngle = function(radians)
{
  return radians * (180 / Math.PI);
}
let givePythagoreSide = function(hypotenuse, side)
{
    return (Math.sqrt((hypoLong * hypoLong) - (side * side)));
}
let givePlanetCoordinates = function(pivotX, pivotY, ray, radians)
{
    let posX = pivotX + (ray * Math.cos(radians));
    let posY = pivotY + (ray * Math.sin(radians));
    planetCoordinates = [posX, posY];
    return planetCoordinates;
}
