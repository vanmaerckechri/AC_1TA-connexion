window.addEventListener('load', function()
{
// -- INIT --
    let renderer, scene, camera, cameraCoordinates, planetsLength, planetListIndex, angle, ray;

    planetsLength = planetsList.length;
    planetListIndex = 0;
    angle = 360 / planetsLength;
    ray = (planetsLength / 1.8) * 1000;

    renderer = new THREE.WebGLRenderer({ antialias: false, alpha: true });
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    // If WebGL does not work on your browser you can use the Canvas rendering engine => renderer = new THREE.CanvasRenderer();
    renderer.setSize( window.innerWidth, window.innerHeight );
    document.getElementById('container').appendChild(renderer.domElement);

    scene = new THREE.Scene();

// -- DUMMY PIVOT --
    pivot_planets = new THREE.Object3D();
    pivot_planets.position.set(0, 0, 0);
    pivot_planets.name = "pivotPlanets";

// -- EARTHS --
    let createPlanets = function()
    {
        for (let planetsLengthIndex = planetsLength - 1, i = planetsLengthIndex; i >= 0; i--)
        {
            let imgNbr = Math.round(planetsList[i]["stats_environnement"]);
            let planetCoordinates = givePlanetCoordinates(0, 0, ray, convertAngleToRadians(angle));
            angle += (360 / planetsLength);
            if (i < planetsLengthIndex)
            {
                let geometry = new THREE.SphereGeometry(600, 16, 16);
                let diffuseMap = new THREE.TextureLoader().load('assets/img/earth_diffuse'+imgNbr+'.jpg');
                //let normalMap = new THREE.TextureLoader().load('assets/img/earth_normalmap.tif');
                let bumpMap = new THREE.TextureLoader().load('assets/img/earth_bump1.jpg');
                //let specularMap = new THREE.TextureLoader().load('assets/img/earth_specular.tif');
                let material = new THREE.MeshPhongMaterial
                ({
                    color: 0xffffff,
                    map: diffuseMap,
                    bumpMap: bumpMap,
                    bumpScale: 20,
                    //normalMap: normalMap,
                    //specularMap: specularMap,
                    //specular: 0x666666,
                    transparent: false
                })
                planet = new THREE.Mesh(geometry, material);
                planet.position.set(planetCoordinates[0], 0, planetCoordinates[1]);
                planet.geometry.computeVertexNormals();
                planet.receiveShadow = true;
                //pivot_planets.add(mesh_earth);

                pivot_planets.add(planet);

                // Clouds
                geometry = new THREE.SphereGeometry(620, 16, 16);
                diffuseMap = new THREE.TextureLoader().load('assets/img/earth_clouds_diffuse1.jpg');
                alphaMap = new THREE.TextureLoader().load('assets/img/earth_clouds_mask.jpg');
                material = new THREE.MeshLambertMaterial
                ({
                    color: 'rgb(255, 255, 255)',
                    map: diffuseMap,
                    alphaMap: alphaMap,
                    transparent: true
                })
                let mesh_earthClouds = new THREE.Mesh(geometry, material);
                mesh_earthClouds.castShadow = true;
                planet.add(mesh_earthClouds);

                // Atmosphere
                geometry = new THREE.SphereGeometry(640, 16, 16);
                material = new THREE.MeshBasicMaterial
                ({
                    color: 'blue',
                    opacity: 0.15,
                    //side: THREE.DoubleSide,
                    //side: THREE.BackSide,
                    transparent: true
                })
                mesh_earthAtmo = new THREE.Mesh(geometry, material);
                planet.add(mesh_earthAtmo);
            }
            else
            {
                let geometry = new THREE.SphereGeometry(600, 16, 16);
                let material = new THREE.MeshBasicMaterial
                ({
                    color: "rgb(0, 40, 155)",
                    wireframe: true,
                    opacity: 0.5,
                    //normalMap: normalMap,
                    //specular: 0x666666,
                    transparent: true
                })
                planet = new THREE.Mesh(geometry, material);
                if (planetsList.length == 1)
                {
                    ray = 1250;
                    planet.position.set(0, -500, 0);
                }
                else
                {
                    planet.position.set(planetCoordinates[0], 0, planetCoordinates[1]);
                }
                planet.geometry.computeVertexNormals();
                //pivot_planets.add(mesh_earth);

                pivot_planets.add(planet);
            }
            planet.name = planetsList[i].name;
            planet.idCr = planetsList[i].id_classroom;
        }
        scene.add(pivot_planets);
    }
    createPlanets();
    
// -- CAMERA --
    camera = new THREE.PerspectiveCamera( 50, window.innerWidth / window.innerHeight, 1, ray * 3.5 );
    cameraCoordinates = givePlanetCoordinates(0, 0, ray * 2.5, convertAngleToRadians(0));
    camera.position.set(cameraCoordinates[0], ray, cameraCoordinates[1]);
    camera.lookAt(0, -500, 0);
    scene.add(camera);

// -- LIGHT --
    // Frontlight
    let directLightCoordinates = givePlanetCoordinates(0, 0, ray * 2, convertAngleToRadians(45));
    let directLight = new THREE.DirectionalLight(0xffffff, 1.5);
    directLight.position.set(directLightCoordinates[0], 1000, directLightCoordinates[1]);
    directLight.target.position.set(0, 0, 0);
    directLight.castShadow = true;
    scene.add(directLight);

    // BackLight
    let backLightCoordinates = givePlanetCoordinates(0, 0, ray * 2, convertAngleToRadians(200));
    let backLight = new THREE.DirectionalLight( 0xffffff, 5 );
    backLight.position.set(backLightCoordinates[0], -500, backLightCoordinates[1]);
    backLight.target.position.set(0, 0, 0);
    scene.add(backLight);

// -- MOUSE --
    let mouse = new THREE.Vector2();
    mouse.oldX = 0;
    let callMouseAxisPlanet = function(event)
    {
        mouse.x = event.touches != undefined ? (event.touches[0].clientX / window.innerWidth) * 2 - 1 : (event.clientX / window.innerWidth ) * 2 - 1;
        mouse.y = event.touches != undefined ? -(event.touches[0].clientY / window.innerHeight) * 2 + 1 : -(event.clientY / window.innerHeight ) * 2 + 1;
    }

// -- UI --
    let updatePlanetStats = function(idCr)
    {

        // Refresh current planet stats
        let statsBarsContainer = document.getElementById("step_scores");
        let planetIndex;
        for (let idPlanet = planetsList.length - 1; idPlanet >= 0; idPlanet --)
        {
            if (planetsList[idPlanet]["id_classroom"] == idCr)
            {
                planetIndex = idPlanet;
                break;
            }
        }
        let statsBar = document.querySelectorAll(".statsBar");
        statsBar[0].innerText =  planetsList[planetIndex]["stats_environnement"];
        statsBar[1].innerText =  planetsList[planetIndex]["stats_sante"];
        statsBar[2].innerText =  planetsList[planetIndex]["stats_social"];

        // hide statsbars for planet creation

        if (document.getElementById("planetName").innerHTML == "Créer une Nouvelle Planète")
        {
            if (!statsBarsContainer.classList.contains("disabled_v2"))
            {
                statsBarsContainer.classList.add("disabled_v2");
            }
        }
        else
        {
            if (statsBarsContainer.classList.contains("disabled_v2"))
            {
                statsBarsContainer.classList.remove("disabled_v2");            
            }
        }
        // displayScoresBar() is a function from stats.js
        displayScoresBar();
    }

    let deleteElement = function(element, type)
    {
        for (let i = element.length - 1; i >= 0; i--)
        {
            let elementTarget;
            if (type[i] == "class")
            {
                elementTarget = document.querySelector('.'+element[i]);
            }
            else
            {
                elementTarget = document.getElementById(element[i]);
            }
            if (elementTarget)
            {
                elementTarget.remove();
            }
        }
    }

    let recordModifications = function()
    {
        let planetActivationList = [];
        let form = createDomElem("form", [["id", "action", "method", "class"], ["recordModifications", "admin.php", "post", "disabled"]]);
        for (let planetIndex = planetsList.length - 1; planetIndex >= 0; planetIndex--)
        {
            if (planetsList[planetIndex]["activationOriginStatus"] && planetsList[planetIndex]["activationOriginStatus"] != planetsList[planetIndex]["activation"])
            {
                let idCr = planetsList[planetIndex]["id_classroom"];
                let activation = planetsList[planetIndex]["activation"];
                planetActivationList.push([idCr, activation]);

                let inputPlanetActivationIdCrList = createDomElem("input", [["name", "value", "type"], ["inputPlanetActivationIdCrList[]", idCr, "hidden"]]);
                let inputPlanetActivationStatusList = createDomElem("input", [["name", "value", "type"], ["inputPlanetActivationStatusList[]", activation, "hidden"]]);
                form.appendChild(inputPlanetActivationIdCrList);
                form.appendChild(inputPlanetActivationStatusList);
            }
        }
        if (planetActivationList.length > 0)
        {
            document.body.appendChild(form);
            form.submit();
        }
    }

    let displayRecordModificationsButton = function()
    {
        deleteElement(["recordModifications"], ["id"]);
        for (let planetIndex = planetsList.length - 1; planetIndex >= 0; planetIndex--)
        {
            if (planetsList[planetIndex]["activationOriginStatus"] && planetsList[planetIndex]["activationOriginStatus"] != planetsList[planetIndex]["activation"])
            {
                recordModificationsButton = createDomElem("button", [["id", "class"],["recordModifications", "recordModifications buttonDefault"]]);
                recordModificationsButton.innerText = "Enregistrer les Modifications";
                document.querySelector(".leaveGameButtonContainer").appendChild(recordModificationsButton);
                recordModificationsButton.onclick = recordModifications;
                return;
            }
        }
    }

    let createThemeButtons = function(studentsList, currentTheme)
    {
        let themeButtonsContainer = document.getElementById("themeButtonsContainer");
        let indexCurrentTheme;
        themeButtonsContainer.innerHTML = "";
        // allThemesNames from "question.js" file
        for (let theme = 0, themesLength = allThemesNames.length; theme < themesLength; theme++)
        {
            let newElemAttributes = [["class"],["themeButton buttonDefault"]];
            let button = createDomElem("button", newElemAttributes);
            if (currentTheme != allThemesNames[theme])
            {
                themeName = allThemesNames[theme];
                button.innerText = themeName;
                themeButtonsContainer.appendChild(button);
            }
            else
            {  
                indexCurrentTheme = theme;
                themeName = "average";
                button.innerText = "tous les thèmes";
                themeButtonsContainer.insertBefore(button, themeButtonsContainer.firstChild);
            }
            button.onclick = loadStatsPannel.bind(this, studentsList, themeName);
        }
        // theme title
        let themeTitle = createDomElem("div", [["id", "class"],["themeTitle", "themeTitle"]]);
        themeButtonsContainer.insertBefore(themeTitle, themeButtonsContainer.firstChild);
        let themeTitleContent = currentTheme;
        if (currentTheme == "average")
        {
            themeTitleContent = "Tous les Thèmes";
        }
        themeTitle.innerText = themeTitleContent.toUpperCase();

        // theme activation button
        if (planetsList[planetListIndex]["activation"] == true)
        {
            themeActivationButton = createDomElem("div", [["id", "class"],["themeActivationButton", "activationButtonOn"]])
        }
        else
        {
            themeActivationButton = createDomElem("div", [["id", "class"],["themeActivationButton", "activationButtonOff"]])
        }
        themeButtonsContainer.insertBefore(themeActivationButton, themeButtonsContainer.firstChild);
    }

    let manageActivationPlanet = function(planet)
    {
        let changeActivationPlanetStatus = function()
        {
            if (!planet.activationOriginStatus)
            {
                planet.activationOriginStatus = planet.activation;
            }
            if (planet.activation == 1)
            {
                planet.activation = 0;
            }
            else
            {
                planet.activation = 1;
            }
            updateActivationPlanetButton();
            displayRecordModificationsButton();
        }

        let updateActivationPlanetButton = function()
        {
            if (planet.name != "Créer une Nouvelle Planète")
            {
                let planetNameContainer = document.querySelector(".planetNameContainer");
                let planetOnOffButton;
                deleteElement(["planetActivationButton"], ["id"]);
                if (planet.activation == 1)
                {
                    planetOnOffButton = createDomElem("div", [["id", "class"],["planetActivationButton", "activationButtonOn"]])
                }
                else
                {
                    planetOnOffButton = createDomElem("div", [["id", "class"],["planetActivationButton", "activationButtonOff"]])
                }
                planetNameContainer.appendChild(planetOnOffButton);
                planetOnOffButton.onclick = changeActivationPlanetStatus;
            }
            else
            {
                deleteElement(["planetActivationButton"], ["id"]);
            }
        }
        updateActivationPlanetButton();
    }

    let updatePlanetName = function(direction = false)
    {
        if (direction == "left")
        {
            planetListIndex = planetListIndex > 0 ? planetListIndex - 1 : planetsLength - 1;
        }
        else if (direction == "right")
        {
            planetListIndex = planetListIndex < planetsLength - 1 ? planetListIndex + 1 : 0;
        }
        let planetName = document.querySelector('.planetName');
        planetName.innerText = planetsList[planetListIndex].name;
        manageActivationPlanet(planetsList[planetListIndex]);
        updatePlanetStats(planetsList[planetListIndex]["id_classroom"]);
    }

    let randPlanetNameLetters = function()
    {
        let planetName = document.querySelector('.planetName');
        let randText = planetName.innerText;
        let chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        //let chars = "電电電買买買車车車紅红紅無无無東东東馬马馬風风風時时時鳥鸟鳥語语語頭头頭魚鱼魚園园園長长長島岛島愛爱愛紙纸紙書书書見见見佛佛仏德德徳拜拜拝黑黑黒冰冰氷兔兔兎妒妒妬每每毎壤壤壌步步歩巢巢巣惠惠恵鞋鞋靴莓莓苺圓圆円聽听聴實实実證证証龍龙竜賣卖売龜龟亀藝艺芸戰战戦繩绳縄關关関鐵铁鉄圖图図團团団轉转転廣广広惡恶悪豐丰豊腦脑脳雜杂雑壓压圧雞鸡鶏價价価樂乐楽氣气気廳厅庁發发発勞劳労劍剑剣歲岁歳權权権燒烧焼贊赞賛兩两両觀观観營营営處处処齒齿歯驛驿駅櫻樱桜產产産讀读読顏颜顔學学学體体体點点点麥麦麦蟲虫虫舊旧旧萬万万盜盗盗寶宝宝國国国醫医医雙双双晝昼昼觸触触來来来畫画画黃黄黄區区区";

        let randNumberOfLetter = Math.floor(Math.random() * randText.length);
        let letterIndex = Math.floor(Math.random() * randText.length);
        randText = randText.substr(0, letterIndex) + chars[Math.floor(Math.random() * chars.length)] + randText.substr(letterIndex + 1);
        planetName.innerText = randText;
    }

    let closePlanetInfos = function(event)
    {
        let planetInfosContainer = document.querySelector('.planetInfosContainer');
        // close planet infos
        if (document.querySelector('.populationContainer') || document.getElementById('questionsRepliesContainer'))
        {
            deleteElement(["populationContainer", "questionsRepliesContainer"], ["class", "id"]);
            document.querySelector('.deleteButton').remove();
            planetInfosContainer.classList.add("disabled");
        }
        else if (!planetInfosContainer.classList.contains("disabled"))
        {
            planetInfosContainer.classList.add("disabled");
            document.querySelector('.freeClassroomsContainer').classList.add("disabled");
        }
        hoverPlanet(event);
        deleteElement(["deleteValidationContainer"], ["class"]);
    }
    // Detect Click on Planet
    scene.children[0].busy = false;
    let openRotationPlanet = function(event) 
    {
        let planetInfosContainer = document.querySelector('.planetInfosContainer');
        if (planetInfosContainer.classList.contains("disabled"))
        {
            let rotatePlanet = function(event)
            {
                if (scene.children[0].busy == false)
                {
                    scene.children[0].busy = true;
                    let pathToReachToTheLeft = scene.children[0].rotation.y - convertAngleToRadians(360 / planetsLength);
                    let pathToReachToTheRight = scene.children[0].rotation.y + convertAngleToRadians(360 / planetsLength);

                    // Current mouse||finger position;
                    callMouseAxisPlanet(event);
                    // Planet rotation           
                    if (mouse.x < mouse.oldX)
                    {
                        // to the left
                        planetsMouvTempo = setInterval(function()
                        { 
                            scene.children[0].rotation.y -= .1;
                            randPlanetNameLetters();

                            if (scene.children[0].rotation.y <= pathToReachToTheLeft)
                            {
                                updatePlanetName("right");
                                scene.children[0].busy = false;
                                scene.children[0].rotation.y = pathToReachToTheLeft;
                                if (convertRadiansToAngle(scene.children[0].rotation.y) == -360)
                                {
                                    scene.children[0].rotation.y = 0;
                                }
                                clearInterval(planetsMouvTempo);
                            }
                        }, 16);
                    }
                    else
                    {
                        // to the right
                        planetsMouvTempo = setInterval(function()
                        { 
                            scene.children[0].rotation.y += .1;
                            randPlanetNameLetters();

                            if (scene.children[0].rotation.y >= pathToReachToTheRight)
                            {
                                updatePlanetName("left");
                                scene.children[0].busy = false;
                                scene.children[0].rotation.y = pathToReachToTheRight;
                                if (convertRadiansToAngle(scene.children[0].rotation.y) == 360)
                                {
                                    scene.children[0].rotation.y = 0;
                                }
                                clearInterval(planetsMouvTempo);
                            }
                        }, 16);
                    }
                }
            }
            callMouseAxisPlanet(event);
            mouse.oldX = mouse.x;
            let closeRotationPlanet = function()
            {
                document.removeEventListener("touchmove", rotatePlanet, false) || document.removeEventListener("mousemove", rotatePlanet, false);
            }
            document.addEventListener("touchmove", rotatePlanet, false) || document.addEventListener("mousemove", rotatePlanet, false);
            document.addEventListener("touchend", closeRotationPlanet, false) || document.addEventListener("mouseup", closeRotationPlanet, false);
        }
    }

    let loadStatsPannel = function(studentsList, currentTheme)
    {
        // Population Infos
        let planetInfosContainer = document.querySelector('.planetInfosContainer');
        let planetInfosTitleContainer = document.querySelector('.planetInfosTitleContainer');
        let planetInfosTitle = document.querySelector('.planetInfosTitle');

        planetInfosTitle.innerText = "Statistiques";
/*
        if (currentTheme == "average")
        {
            planetInfosTitle.innerText += " de Tous les Thèmes";
            //planetInfoscurrentThemeTitle.innerText = "Moyenne de Tous les Thèmes";
        }
        else
        {
            planetInfosTitle.innerText += " du thème: \""+currentTheme.toUpperCase()+"\"";
            //planetInfoscurrentThemeTitle.innerText = "Moyenne du Thème: \""+currentTheme+"\"";
        }
*/

        // refresh stats content
        deleteElement(["populationContainer", "questionsRepliesContainer"], ["class", "id"]);
        createThemeButtons(studentsList, currentTheme);
        let studentsContainer = createDomElem("ul", [["id", "class"], ["populationContainer", "populationContainer"]])

        // Stats Titles
        let statsDbTitle = ["stats_envi", "stats_sante", "stats_social", "stats_average"];
        let statsDbPlanetTitle = ["stats_environnement", "stats_sante", "stats_social", "stats_average"];
        let statsTitle = ["Environnement", "Santé", "Social", "Moyenne"];

        deleteElement(["previousNextPlButtonsContainer"], ["id"]);
        let previousNextPlButtonsContainer = createDomElem("div", [["id", "class"],["previousNextPlButtonsContainer","previousNextPlButtonsContainer"]]);
        planetInfosTitleContainer.appendChild(previousNextPlButtonsContainer)

        // change row order and refresh at click
        let changeRowOrder = function(col, byWhat)
        {
            studentsContainer.remove();
            let order = convertObjectsPropertyToArray(studentsList, col, byWhat, currentTheme);
            sortObjectsByProperty(studentsList, order, col, byWhat, currentTheme);
            loadStatsPannel(studentsList, currentTheme);
        }

        // display questions and replies from players
        let displayQuestionsReplies = function(studentIndex)
        {
            studentsContainer.remove();
            if (currentTheme == "average")
            {
                currentTheme = allThemesNames[0];
            }
            planetInfosTitle.innerText = "Réponses de "+studentsList[studentIndex]["nickname"];

            // translate theme name into quiz name
            let quizIndex;
            for (let themeIndex = allThemesNames.length - 1; themeIndex >= 0; themeIndex--)
            {
                if (allThemesNames[themeIndex] == currentTheme)
                {
                    quizIndex = themeIndex;
                }
            }
            // display questions / replies
            let questionsRepliesContainer = createDomElem("div", [["id", "class"], ["questionsRepliesContainer", "questionsRepliesContainer"]])
            let quizName = allThemes[quizIndex];
            let replyIndexName = 0;
            for (let quizPart = 1; quizPart < 4; quizPart++)
            {
                quizName = quizName.slice(0, quizName.length - 1);
                quizName += quizPart;
                let quiz = eval(quizName)
                deleteElement(["populationContainer", "questionsRepliesContainer"], ["class", "id"]);
                for (let questionIndex = 0; questionIndex < 3; questionIndex++)
                {
                    replyIndexName++; 
                    let replies = studentsList[studentIndex]["theme"][currentTheme] && studentsList[studentIndex]["theme"][currentTheme]["replies"] ? studentsList[studentIndex]["theme"][currentTheme]["replies"] : "-";
                    let questionReplyRow = createDomElem("div", [["class"],["questionReplyRow"]]);
                    let questionName = "question0"+(questionIndex+1);
                    let question = createDomElem("p", [["class"],["question"]]);
                    let replyName = "reply"+replyIndexName;
                    let reply = createDomElem("p", [["class"],["reply"]]);
                    let propositionName = "proposition0"+[replies[replyName]];
                    question.innerText = replyIndexName+". "+quiz[questionName]["question"]+" =>";

                    if (questionIndex == 0)
                    {
                        let intro = createDomElem("p", [["class"],["questionIntro"]]);
                        intro.innerText = quiz[questionName]["intro"];
                        questionsRepliesContainer.appendChild(intro);
                    }

                    if (replies != "-")
                    {

                        reply.innerText = quiz[questionName][propositionName]["proposition"];
                    }
                    else
                    {
                        reply.innerText = "..."
                    }
                    questionReplyRow.appendChild(question);
                    questionReplyRow.appendChild(reply);
                    questionsRepliesContainer.appendChild(questionReplyRow);
                    planetInfosContainer.appendChild(questionsRepliesContainer);
                }
            }
            createCategoryButton("questionsReplies");
            // create theme buttons and change event on it
            createThemeButtons(studentsList, currentTheme);
            let themeButtons = document.querySelectorAll(".themeButton");
            for (let buttonIndex = themeButtons.length - 1; buttonIndex >= 0; buttonIndex--)
            {
                if (themeButtons[buttonIndex].innerText == "tous les thèmes")
                {
                    themeButtons[buttonIndex].remove();
                }
                themeButtons[buttonIndex].onclick = function()
                {
                    currentTheme = themeButtons[buttonIndex].innerText;
                    displayQuestionsReplies(studentIndex);
                };
            }
            // create previous/next player buttons
            deleteElement(["previousNextPlButtonsContainer"], ["id"]);
            let previousNextPlButtonsContainer = createDomElem("div", [["id", "class"],["previousNextPlButtonsContainer","previousNextPlButtonsContainer"]]);
            let previousPlayerButton = createDomElem("button", [["id", "class"],["previousPlayerButton","previousPlayerButton buttonDefault"]]);
            previousPlayerButton.innerText = "<<<";
            let nextPlayerButton = createDomElem("button", [["id", "class"],["nextPlayerButton","nextPlayerButton buttonDefault"]]);
            nextPlayerButton.innerText = ">>>";
            previousNextPlButtonsContainer.appendChild(previousPlayerButton);
            previousNextPlButtonsContainer.appendChild(nextPlayerButton);
            planetInfosTitleContainer.appendChild(previousNextPlButtonsContainer);

            let loadAnotherStudent = function(direction)
            {
                if (direction == "next")
                {
                    if (studentIndex < studentsList.length - 1)
                    {
                        studentIndex += 1;
                    }
                    else
                    {
                        studentIndex = 0;
                    }
                }
                else
                {
                    if (studentIndex > 0)
                    {
                        studentIndex -= 1;
                    }
                    else
                    {
                        studentIndex = studentsList.length - 1;
                    }
                }
                displayQuestionsReplies(studentIndex);
            }

            previousPlayerButton.onclick = loadAnotherStudent.bind(this, "previous");
            nextPlayerButton.onclick = loadAnotherStudent.bind(this, "next");
        }
        // create category button
        let createCategoryButton = function(category)
        {
            deleteElement(["categoryButtonContainer"], ["id"]);
            let categoryButtonContainer = createDomElem("div", [["id", "class"],["categoryButtonContainer", "categoryButtonContainer"]]);
            let categoryButton = createDomElem("button", [["id", "class"],["categoryButton", "categoryButton buttonDefault"]]);
            let themeButtonsContainer = document.getElementById("themeButtonsContainer");
            categoryButtonContainer.appendChild(categoryButton);
            planetInfosContainer.insertBefore(categoryButtonContainer, themeButtonsContainer);

            if (category == "stats")
            {
                categoryButton.onclick = displayQuestionsReplies.bind(this, 0);
                categoryButton.innerText = "voir les questions/réponses";
            }
            else
            {
                categoryButton.onclick = loadStatsPannel.bind(this, studentsList, currentTheme);
                categoryButton.innerText = "voir les statistiques";
            }
        }

        // Display Stats
        createCategoryButton("stats");
        if (typeof studentsList == "undefined")
        {
            studentsList = [];
        }
        for (let row = studentsList.length; row >= 0; row--)
        {
            let student = document.createElement("li");
            let studentName = document.createElement("p");
            let statsContainer = createDomElem("span", [["class"], ["statsContainer"]]);
            let statsStudentAverage = 0;
            if (row == studentsList.length)
            {
                // Nickname Title
                studentName.innerText = "Habitants";
                studentName.onclick = function() {changeRowOrder("nickname", "nickname");};
            }
            else
            {
                // Nickname
                studentName.innerText = studentsList[row].nickname;
                studentName.onclick = displayQuestionsReplies.bind(this, row);
            }
            for (let col = 0, statsLength = statsDbTitle.length; col < statsLength; col++)
            {
                let stats = document.createElement("span");
                stats.setAttribute("class", "stats");
                if (row == studentsList.length)
                {
                    // Stats Title
                    stats.innerText = statsTitle[col];
                    stats.onclick = function() {changeRowOrder(statsDbTitle[col], "theme");};
                }
                else
                {
                    // Display stats
                    if (col < statsLength - 1)
                    {
                        // student stats
                        let currentStat = studentsList[row]["theme"][currentTheme] && studentsList[row]["theme"][currentTheme][statsDbTitle[col]] && studentsList[row]["theme"][currentTheme][statsDbTitle[col]] != "-" ? studentsList[row]["theme"][currentTheme][statsDbTitle[col]] : false;

                        if (currentStat != false)
                        {
                            stats.innerText = Math.round(currentStat*50) + "%";
                            statsStudentAverage += parseFloat(currentStat);
                        }
                        else
                        {
                            stats.innerText = "-";
                            statsStudentAverage = "-";
                        }
                    }
                    else
                    {
                        // student average for last column
                        if (studentsList[row]["theme"][currentTheme] && studentsList[row]["theme"][currentTheme]["stats_average"] != "-" && statsStudentAverage != "-")
                        {
                            statsStudentAverage = Math.round(statsStudentAverage / (statsLength -1)*50) + "%";
                            studentsList[row]["theme"][currentTheme]["stats_average"] = statsStudentAverage;
                            stats.innerText = statsStudentAverage;
                        }
                        else
                        {
                            stats.innerText = "-";
                        }
                    }
                }
                statsContainer.appendChild(stats);
            }

            student.appendChild(studentName);
            student.appendChild(statsContainer);
            studentsContainer.appendChild(student);
        }
        planetInfosContainer.appendChild(studentsContainer);
        planetInfosContainer.classList.remove("disabled");
    }

    let hoverPlanet = function(event)
    {
        // Detect if mouse||finger position is on front object
        callMouseAxisPlanet(event);
        let raycaster = new THREE.Raycaster();
        raycaster.setFromCamera(mouse, camera);
        let intersects = raycaster.intersectObjects(scene.children[0].children);
        let planetNameContainer = document.querySelector('.planetNameContainer');
        let planetName = document.querySelector('.planetName');
        let planetNameText = planetName.innerText;
        let planetInfosContainer = document.querySelector('.planetInfosContainer');
        if (planetInfosContainer.classList.contains("disabled"))
        {
            if (!intersects[0])
            {
                for (let i = 1; i < planetsLength; i++)
                {            
                    scene.children[0].children[i].children[1].material.opacity = 0.15;
                }
                scene.children[0].children[0].material.opacity = 0.5;
                if (planetNameContainer.classList.contains("planetNameSphereHover"))
                {
                    planetNameContainer.classList.remove("planetNameSphereHover");
                }
                document.body.style.cursor = "auto";
                document.onclick = null;
            }
            if (intersects != "" && scene.children[0].busy == false && intersects[0].object.name == planetNameText && planetInfosContainer.classList.contains("disabled"))
            {
                if (planetNameText != "Créer une Nouvelle Planète")
                {
                    intersects[0].object.children[1].material.opacity = 0.25;
                }
                else
                {
                    intersects[0].object.material.opacity = 0.80;
                }
                if (!planetNameContainer.classList.contains("planetNameSphereHover"))
                {
                    planetNameContainer.classList.add("planetNameSphereHover");
                }
                document.body.style.cursor = "pointer";
                // Select Planet
                document.onclick = function()
                {                
                    document.body.style.cursor = "auto";
                    let planetInfosTitle = document.getElementById('planetInfosTitle');
                    let uiBackground = document.querySelector('.uiBackground');
                    // Display free classrooms list to create a planet
                    if (planetNameText == "Créer une Nouvelle Planète" && planetInfosContainer.classList.contains("disabled") && scene.children[0].busy == false)
                    {
                        let freeClassroomsContainer = document.querySelector('.freeClassroomsContainer');
                        planetInfosContainer.classList.remove("disabled");
                        freeClassroomsContainer.classList.remove("disabled");

                        // clean old content
                        document.getElementById("themeButtonsContainer").innerHTML = "";
                        planetInfosTitle.innerHTML = "";
                        deleteElement(["categoryButtonContainer"], ["id"]);
                        deleteElement(["previousNextPlButtonsContainer"], ["id"]);                 

                        if (freeClassroomLength > 0)
                        {
                            planetInfosTitle.innerText = "Veuillez choisir l'une de vos classes!";
                        }
                        else
                        {
                            planetInfosTitle.innerText = "Aucune classe disponible!";
                        }
                    }
                    // Display Planet Infos (population, stats, etc)
                    else if (planetNameText != "Créer une Nouvelle Planète" && planetInfosContainer.classList.contains("disabled") && scene.children[0].busy == false)
                    {
                        // Delete Pannel
                        let themeButtonsContainer = document.getElementById("themeButtonsContainer");
                        let deleteContainer = document.querySelector('.planetDeleteContainer');
                        let deleteImg = createDomElem("img", [["class", "src"], ["deleteButton", "assets/img/delete.svg"]]);
                        deleteContainer.appendChild(deleteImg);
                        planetInfosContainer.insertBefore(deleteContainer, themeButtonsContainer);
                        document.querySelector('.deleteButton').onclick = function()
                        {
                            if (!document.querySelector('.deleteValidationContainer'))
                            {
                                // delete validation container
                                let deleteValidationContainer = createDomElem("div", [["class"], ["deleteValidationContainer"]]);
                                // text                              
                                let deleteValidationText = document.createElement('p');
                                deleteValidationText.innerText = "Êtes-vous sûr de vouloir effacer la planète \""+intersects[0].object.name+"\" ?";
                                deleteValidationContainer.appendChild(deleteValidationText);
                                // button yes
                                let deleteValidationYes = createDomElem("a", [["class", "href"], ["deleteValidationYes", "admin.php?action=delplan&idcr="+intersects[0].object.idCr]]);
                                deleteValidationYes.innerText = "OUI?"
                                deleteValidationContainer.appendChild(deleteValidationYes);
                                // button no
                                let deleteValidationNo = createDomElem("a", [["class", "href"], ["deleteValidationNo", "#"]]);
                                deleteValidationNo.innerText = "NON?"
                                deleteValidationContainer.appendChild(deleteValidationNo);
                                deleteContainer.insertBefore(deleteValidationContainer, deleteImg);
                                deleteValidationNo.onclick = function(event)
                                {
                                    event.preventDefault();
                                    deleteValidationContainer.remove();
                                }
                            }
                            else
                            {
                                document.querySelector('.deleteValidationContainer').remove();
                            }
                        }
                        loadStatsPannel(studentsInfos[intersects[0].object.idCr], "average");
                    }
                }
            }
        }
    }

    // -- ANIMATION LOOP --
    let animate = function()
    {
        for (let i = planetsLength - 1; i >= 0; i--)
        {
            scene.children[0].children[i].rotation.y += 0.0005;
            if (i > 0)
            {
                scene.children[0].children[i].children[0].rotation.y += 0.0001;
            }
        }
        renderer.render(scene, camera);
        requestAnimationFrame(animate);
    }

    // -- RESIZE --
    let adaptUi = function()
    {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);   
    }

    window.addEventListener("resize", adaptUi, false);

    // active rotation if we have at least 2 planets (create planet + another one)
    if (planetsList.length > 1)
    {
        document.addEventListener("touchstart", openRotationPlanet, false) || document.addEventListener("mousedown", openRotationPlanet, false);
    }
    document.addEventListener("touchmove", hoverPlanet, false) || document.addEventListener("mousemove", hoverPlanet, false);
    document.querySelector('.previous').addEventListener("touchstart", closePlanetInfos, false) || document.querySelector('.previous').addEventListener("mousedown", closePlanetInfos, false);

    // -- INIT --

    updatePlanetName();
    animate();
    adaptUi();
});

/*
    // Nouvel objet 3D qui a pour point de pivot la première sphère
    pivotPoint = new THREE.Object3D();
    mesh_earth.add(pivotPoint);
    // Position from pivot point to sphere 2
    //mesh_earth2.position.set(1000, 1000, 1000);
    // make the pivotpoint the sphere's parent
    pivotPoint.add(mesh_earth2);

    let closeClassroomsList = function(event)
    {
        let planetInfosContainer = document.querySelector('.planetInfosContainer');
        if (event.clientX < planetInfosContainer.offsetLeft || event.clientX > (planetInfosContainer.offsetLeft + planetInfosContainer.offsetWidth) || event.clientY < planetInfosContainer.offsetTop || event.clientY > (planetInfosContainer.offsetTop + planetInfosContainer.offsetHeight))
        {

            planetInfosContainer.style.display = "none";
        }
    }
    document.body.addEventListener("touchstart", closeClassroomsList, false) || document.addEventListener("mousedown", closeClassroomsList, false);

    let selectPlanet = function()
    {
        // Detect if mouse||finger position is on front object
        callMouseAxisPlanet(event);
        let raycaster = new THREE.Raycaster();
        raycaster.setFromCamera(mouse, camera);
        let intersects = raycaster.intersectObjects(scene.children[0].children);
        let planetName = document.querySelector('.planetName');
        let planetNameText = planetName.innerText;
    }

    // Move camera around the scene
    let controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.center =  new THREE.Vector3
    (
        mesh_earth.position.x,
        mesh_earth.position.y,
        mesh_earth.position.z
    );
*/