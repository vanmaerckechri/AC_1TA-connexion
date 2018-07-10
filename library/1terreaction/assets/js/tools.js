/*  
 *  in = array with objects
 *  out = array
 */
let convertObjectsPropertyToArray = function(objs, key)
{
    let array = [];
    for (let i = objs.length - 1; i >= 0; i--)
    {
        array.push(objs[i][key]);
    }
    array.sort();
    return array;
}

// change array order who contain objects
function sortObjectsByProperty (array, order, key)
{

    array.sort(function (a, b)
    {
      let propValueA = a[key], propValueB = b[key];
      //console.log(b[key]);
      if (order.indexOf(propValueA) > order.indexOf(propValueB))
      {
          return 1;
      }
      else
      {
          return -1;
      }
      
    });
    array.reverse();
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