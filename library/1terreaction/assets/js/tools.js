/*  
 *  in = array with objects
 *  out = array
 */

let reverse = 1;
let convertObjectsPropertyToArray = function(objs, key)
{
    let array = [];
    for (let i = objs.length - 1; i >= 0; i--)
    {
        array.push(objs[i][key]);
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
function sortObjectsByProperty (array, order, key)
{
    array.sort(function (a, b)
    {
        //console.log(b[key]);
        return order.indexOf(a[key]) - order.indexOf(b[key])
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