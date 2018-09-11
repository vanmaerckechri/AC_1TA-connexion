// SCORES
window.addEventListener("load", function()
{
    let displayScoresBar = function(statsTitle)
    {
        let statsContainer = document.querySelectorAll(".step_scores");
        for (let i = statsContainer.length - 1; i >= 0; i--)
        {
            statsContainer[i].classList.remove("disabled_v2");
        }

        let createFluid = function(barsTag)
        {
            for (let i = barsTag.length - 1; i >= 0; i--)
            {
                let fluidForScore = document.createElement("span");
                fluidForScore.setAttribute("class", "fluidForScore");
                barsTag[i].appendChild(fluidForScore);

                let score = barsTag[i].innerText * 50;
                barsTag[i].style.color = "transparent";

                fluidForScore.style.width = score + "%";
            }
        }

        if (document.querySelector(".statsPlanetContainer"))
        {
            let statsBarsPlanet = document.querySelectorAll(".statsPlanetContainer .statsBar");
            createFluid(statsBarsPlanet);
        }
        if (document.querySelector(".statsLocalAverageContainer"))
        {
            let statsBarsLocalAverage = document.querySelectorAll(".statsLocalAverageContainer .statsBar");
            createFluid(statsBarsLocalAverage);
        }
        if (document.querySelector(".statsLocalPreviousGameContainer"))
        {
            let statsBarsLocalByTheme = document.querySelectorAll(".statsLocalPreviousGameContainer .statsBar");
            createFluid(statsBarsLocalByTheme);
        }
        /*let closeButton = document.getElementById("scoresContainerClose");
        closeButton.addEventListener("click", function()
        {
            document.getElementById("mainMenuContainer").classList.toggle("disabled_v2");
            document.getElementById("step_scores").remove();
        }, false);*/
    }
    displayScoresBar();
}, false);