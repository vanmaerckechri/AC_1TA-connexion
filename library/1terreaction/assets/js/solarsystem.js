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
            let planetCoordinates = givePlanetCoordinates(0, 0, ray, convertAngleToRadians(angle));
            angle += (360 / planetsLength);
            if (i < planetsLengthIndex)
            {
                let geometry = new THREE.SphereGeometry(600, 16, 16);
                let diffuseMap = new THREE.TextureLoader().load('assets/img/earth_diffuse.jpg');
                let normalMap = new THREE.TextureLoader().load('assets/img/earth_normalmap.tif');
                let bumpMap = new THREE.TextureLoader().load('assets/img/earth_bump.jpg');
                let specularMap = new THREE.TextureLoader().load('assets/img/earth_specular.tif');
                let material = new THREE.MeshPhongMaterial
                ({
                    color: 0xffffff,
                    map: diffuseMap,
                    bumpMap: bumpMap,
                    bumpScale: 20,
                    //normalMap: normalMap,
                    specularMap: specularMap,
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
                diffuseMap = new THREE.TextureLoader().load('assets/img/earth_clouds_diffuse.jpg');
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
            planet.idCr = planetsList[i].id;
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
        if (document.querySelector('.populationContainer'))
        {
            document.querySelector('.populationContainer').remove();
            document.querySelector('.deleteButton').remove();
            planetInfosContainer.classList.add("disabled");
        }
        else if (!planetInfosContainer.classList.contains("disabled"))
        {
            planetInfosContainer.classList.add("disabled");
            document.querySelector('.freeClassroomsContainer').classList.add("disabled");
        }
        hoverPlanet(event);
        if (document.querySelector('.deleteValidationContainer'))
        {
            document.querySelector('.deleteValidationContainer').remove();
        }
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

    let loadStatsPannel = function(studentsList)
    {
        // Population Infos
        let planetInfosContainer = document.querySelector('.planetInfosContainer');
        let planetInfosTitle = document.querySelector('.planetInfosTitle');
        planetInfosTitle.innerText = "Statistiques";
        let studentsContainer = document.createElement("ul");
        studentsContainer.setAttribute("class", "populationContainer");

        let refresh = function(col)
        {
            studentsContainer.remove();
            let order = convertObjectsPropertyToArray(studentsList, col);
            sortObjectsByProperty(studentsList, order, col);
            loadStatsPannel(studentsList);
        }

        // statsPlanetAverage Temporaire (php qui s'en occupera)
        let statsPlanetAverage = 
        {
            stats_envi: 1,
            stats_sante: 1,
            stats_social: 1,
        };
        if (typeof studentsList == "undefined")
        {
            studentsList = [];
        }
        for (let i = studentsList.length; i >= 0; i--)
        {
            let statsDbTitle = ["stats_envi", "stats_sante", "stats_social", "stats_average"];
            let statsTitle = ["Environnement", "Santé", "Social", "Moyenne"];
            let student = document.createElement("li");
            let studentName = document.createElement("p");
            let statsContainer = createDomElem("span", [["class"], ["statsContainer"]]);
            let statsStudentAverage = 0;
            if (i == studentsList.length)
            {
                // Nickname Title
                studentName.innerText = "Habitants";
                studentName.onclick = function() {refresh("nickname");};
            }
            else
            {
                // Nickname
                studentName.innerText = studentsList[i].nickname
            }
            for (let j = 0, statsLength = statsDbTitle.length; j < statsLength; j++)
            {
                let stats = document.createElement("span");
                stats.setAttribute("class", "stats");
                if (i == studentsList.length)
                {
                    // Stats Title
                    stats.innerText = statsTitle[j];
                    stats.onclick = function() {refresh(statsDbTitle[j]);};
                }
                else
                {
                    // Stats (! tout ce qui est average est temporaire, ça sera fait côté php)
                    if (j < statsLength - 1)//TEMP
                    {
                        // students stats
                        stats.innerText = Math.round(studentsList[i][statsDbTitle[j]]*100)/100;
                        statsStudentAverage +=parseFloat(studentsList[i][statsDbTitle[j]]);//TEMP
                        // planet stats
                        statsPlanetAverage[statsDbTitle[j]] += parseFloat(studentsList[i][statsDbTitle[j]]);//TEMP
                    }
                    else
                    {
                        // student average
                        stats.innerText = Math.round(statsStudentAverage / (statsLength -1)*100)/100;//TEMP
                    }
                }
                statsContainer.appendChild(stats);
            }
            // planet stats (students average)
            if (i == 0)//TEMP ==>
            {
                let statsLength = statsDbTitle.length - 1;
                for (let j = 0, studentsLength = studentsList.length; j < statsLength; j++)
                {
                    statsPlanetAverage[statsDbTitle[j]] = Math.round(statsPlanetAverage[statsDbTitle[j]] / studentsLength);
                    statsPlanetAverage['stats_average'] += statsPlanetAverage[statsDbTitle[j]];
                }
                statsPlanetAverage['stats_average'] = Math.round(statsPlanetAverage['stats_average'] / statsLength);
            }// <== fin du temp

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
                if (planetName.classList.contains("planetNameSphereHover"))
                {
                    planetName.classList.remove("planetNameSphereHover");
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
                if (!planetName.classList.contains("planetNameSphereHover"))
                {
                    planetName.classList.add("planetNameSphereHover");
                }
                document.body.style.cursor = "pointer";
                // Select Planet
                document.onclick = function()
                {                
                    document.body.style.cursor = "auto";
                    let planetInfosTitle = document.querySelector('.planetInfosTitle');
                    let uiBackground = document.querySelector('.uiBackground');
                    // Display free classrooms list to create a planet
                    if (planetNameText == "Créer une Nouvelle Planète" && planetInfosContainer.classList.contains("disabled") && scene.children[0].busy == false)
                    {
                        let freeClassroomsContainer = document.querySelector('.freeClassroomsContainer');
                        planetInfosContainer.classList.remove("disabled");
                        freeClassroomsContainer.classList.remove("disabled");

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
                        let deleteContainer = document.querySelector('.planetDeleteContainer');
                        let deleteImg = createDomElem("img", [["class", "src"], ["deleteButton", "assets/img/delete.svg"]]);
                        deleteContainer.appendChild(deleteImg);
                        planetInfosContainer.insertBefore(deleteContainer, planetInfosTitle);
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
                        loadStatsPannel(studentsList[intersects[0].object.idCr]);
                    }
                }
            }
        }
    }

    let adaptUi = function()
    {
        let planetName = document.querySelector('.planetName');
        let planetInfosContainer = document.querySelector('.planetInfosContainer');
        planetInfosContainer.style.top = 0;
        planetInfosContainer.style.height = "calc(100vh - "+planetName.offsetHeight+"px)";        
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

    if (planetsList.length > 1)
    {
        document.addEventListener("touchstart", openRotationPlanet, false) || document.addEventListener("mousedown", openRotationPlanet, false);
    }
    document.addEventListener("touchmove", hoverPlanet, false) || document.addEventListener("mousemove", hoverPlanet, false);
    document.querySelector('.previous').addEventListener("touchstart", closePlanetInfos, false) || document.querySelector('.previous').addEventListener("mousedown", closePlanetInfos, false);
    window.addEventListener("resize", adaptUi, false);

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