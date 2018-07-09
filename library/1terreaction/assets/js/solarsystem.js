let init = function()
{
    let renderer, scene, camera, camera2, mesh_earthClouds;
   // let planetNamesList = ["5ème A", "5ème B", "5ème C", "6ème A", "Créer une Nouvelle Planète"];
    let planetsLength = planetsList.length;
    let planetListIndex = 0;
    let angle = 360 / planetsLength;
    let ray = (planetsLength / 1.8) * 1000;
    // initialize the rendering engine
    // renderer = new THREE.WebGLRenderer();
    renderer = new THREE.WebGLRenderer({ antialias: false, alpha: true });
    // renderer.setSize( 800, 600 );
    // renderer.setClearColor(0x000000, 0);

    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    // If WebGL does not work on your browser you can use the Canvas rendering engine
    // renderer = new THREE.CanvasRenderer();
    renderer.setSize( window.innerWidth, window.innerHeight );
    document.getElementById('container').appendChild(renderer.domElement);

    // Init Scene
    scene = new THREE.Scene();

    // -- Dummy Pivot --
    pivot_planets = new THREE.Object3D();
    pivot_planets.position.set(0, 0, 0);
    pivot_planets.name = "pivotPlanets";

    // -- EARTHS --
    // Calcul angles between each planets
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
        return planetCoordinates
    }
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
                let specularMap = new THREE.TextureLoader().load('assets/img/earth_specular.tif');
                let material = new THREE.MeshLambertMaterial
                ({
                    color: 0xffffff,
                    map: diffuseMap,
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
                pivotPoint = new THREE.Object3D();
                planet.add(pivotPoint);
                pivot_planets.add(planet);

                // Clouds
                geometry = new THREE.SphereGeometry(610, 16, 16);
                diffuseMap = new THREE.TextureLoader().load('assets/img/earth_clouds.jpg');
                material = new THREE.MeshLambertMaterial
                ({
                    color: 'rgb(255, 255, 255)',
                    map: diffuseMap,
                    alphaMap: diffuseMap,
                    transparent: true
                })
                let mesh_earthClouds = new THREE.Mesh(geometry, material);
                mesh_earthClouds.castShadow = true;
                planet.add( mesh_earthClouds );

                // Atmosphere
                geometry = new THREE.SphereGeometry(620, 16, 16);
                material = new THREE.MeshBasicMaterial
                ({
                    color: 'blue',
                    opacity: 0.08,
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
                pivotPoint = new THREE.Object3D();
                planet.add(pivotPoint);
                pivot_planets.add(planet);
            }
            planet.name = planetsList[i].name;
            planet.idCr = planetsList[i].id;
        }
        scene.add(pivot_planets);
    }

    createPlanets();
    
   /* // Nouvel objet 3D qui a pour point de pivot la première sphère
    pivotPoint = new THREE.Object3D();
    mesh_earth.add(pivotPoint);
    // Position from pivot point to sphere 2
    //mesh_earth2.position.set(1000, 1000, 1000);
    // make the pivotpoint the sphere's parent
    pivotPoint.add(mesh_earth2);*/

    
    // -- CAMERA --
    camera = new THREE.PerspectiveCamera( 50, window.innerWidth / window.innerHeight, 1, ray * 3.5 );

    let cameraCoordinates = givePlanetCoordinates(0, 0, ray * 2.5, convertAngleToRadians(0));

    camera.position.set(cameraCoordinates[0], ray, cameraCoordinates[1]);
    camera.lookAt(0, -500, 0);
    //renderer.setSize( 800, 600 );
    scene.add(camera);

    //controle de la camera autour du centre de la scene
    /*let controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.center =  new THREE.Vector3
    (
        mesh_earth.position.x,
        mesh_earth.position.y,
        mesh_earth.position.z
    );*/

    //
    // LIGHTS

    // -- LIGHT --
    let directLightCoordinates = givePlanetCoordinates(0, 0, ray*2, convertAngleToRadians(45));

    let directLight = new THREE.DirectionalLight(0xffffff, 1.5);
    directLight.position.set(directLightCoordinates[0], 1000, directLightCoordinates[1]);
    directLight.target.position.set(0, 0, 0);
    directLight.castShadow = true;
    scene.add( directLight );

    /*// HemiLight
    hemiLight = new THREE.HemisphereLight( 0xffffff, 0xffffff, 0.1 );
    hemiLight.color.setHSL( 0.6, 1, 0.6 );
    hemiLight.groundColor.setHSL( 0.095, 0.5, 0.5 );
    hemiLight.position.set( 750, 750, 2000 );
    scene.add( hemiLight );*/

    // BackLight
    let backLightCoordinates = givePlanetCoordinates(0, 0, ray*2, convertAngleToRadians(200));
    let backLight = new THREE.DirectionalLight( 0xffffff, 10 );
    backLight.position.set(backLightCoordinates[0], -500, backLightCoordinates[1]);
    backLight.target.position.set(0, 0, 0);
    scene.add( backLight );

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

    // Capture mouse||finger position
    let mouse = new THREE.Vector2();
    mouse.oldX = 0;
    let callMouseAxisPlanet = function(event)
    {
        mouse.x = event.touches != undefined ? (event.touches[0].clientX / window.innerWidth) * 2 - 1 : (event.clientX / window.innerWidth ) * 2 - 1;
        mouse.y = event.touches != undefined ? -(event.touches[0].clientY / window.innerHeight) * 2 + 1 : -(event.clientY / window.innerHeight ) * 2 + 1;
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
    }
    // -- DETECT CLICK ON PLANET --
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
                    scene.children[0].children[i].children[2].material.opacity = 0.08;
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
                    intersects[0].object.children[2].material.opacity = 0.25;
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
                {                document.body.style.cursor = "auto";

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
                    // Display free classrooms list to create a planet
                    else if (planetNameText != "Créer une Nouvelle Planète" && planetInfosContainer.classList.contains("disabled") && scene.children[0].busy == false)
                    {
                        // delete tool
                        let deleteContainer = document.querySelector('.planetDeleteContainer');
                        let deleteImg = document.createElement('img');
                        deleteImg.setAttribute("class", "deleteButton");
                        deleteImg.setAttribute("src", "assets/img/delete.svg");
                        deleteContainer.appendChild(deleteImg);
                        planetInfosContainer.insertBefore(deleteContainer, planetInfosTitle);
                        document.querySelector('.deleteButton').onclick = function()
                        {
                            if (!document.querySelector('.deleteValidationContainer'))
                            {
                                // delete validation container
                                let deleteValidationContainer = document.createElement('div');
                                deleteValidationContainer.setAttribute("class", "deleteValidationContainer");
                                // text
                                let deleteValidationText = document.createElement('p');
                                deleteValidationText.innerText = "Êtes-vous sûr de vouloir effacer la planète \""+intersects[0].object.name+"\" ?";
                                deleteValidationContainer.appendChild(deleteValidationText);
                                // button yes
                                let deleteValidationYes = document.createElement('a');
                                deleteValidationYes.setAttribute("class", "deleteValidationYes");
                                deleteValidationYes.setAttribute("href", "admin.php?action=delplan&idcr="+intersects[0].object.idCr);
                                deleteValidationYes.innerText = "OUI?"
                                deleteValidationContainer.appendChild(deleteValidationYes);
                                // button no
                                let deleteValidationNo = document.createElement('a');
                                deleteValidationNo.setAttribute("class", "deleteValidationNo");
                                deleteValidationNo.setAttribute("href", "#");
                                deleteValidationNo.innerText = "NON?"
                                deleteValidationContainer.appendChild(deleteValidationNo);
                                deleteContainer.appendChild(deleteValidationContainer);
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
                        // title
                        planetInfosTitle.innerText = "Habitants";
                        // population
                        let studentsContainer = document.createElement("ul");
                        studentsContainer.setAttribute("class", "populationContainer")
                        for (let i = studentsList[intersects[0].object.idCr].length - 1; i >= 0; i--)
                        {
                            let student = document.createElement("li");
                            student.innerText = studentsList[intersects[0].object.idCr][i].nickname;
                            studentsContainer.appendChild(student);
                        }
                        planetInfosContainer.appendChild(studentsContainer);
                        planetInfosContainer.classList.remove("disabled");
                    }
                }
            }
        }
    }
    document.querySelector('.previous').addEventListener("touchstart", closePlanetInfos, false) || document.querySelector('.previous').addEventListener("mousedown", closePlanetInfos, false);

    /*let selectPlanet = function()
    {
        // Detect if mouse||finger position is on front object
        callMouseAxisPlanet(event);
        let raycaster = new THREE.Raycaster();
        raycaster.setFromCamera(mouse, camera);
        let intersects = raycaster.intersectObjects(scene.children[0].children);
        let planetName = document.querySelector('.planetName');
        let planetNameText = planetName.innerText;
    }*/
    
    if (planetsList.length > 1)
    {
        document.addEventListener("touchstart", openRotationPlanet, false) || document.addEventListener("mousedown", openRotationPlanet, false);
    }
    document.addEventListener("touchmove", hoverPlanet, false) || document.addEventListener("mousemove", hoverPlanet, false);

    // -- ANIMATION LOOP --
    let animate = function()
    {
        for (let i = planetsLength - 1; i >= 0; i--)
        {
            scene.children[0].children[i].rotation.y += 0.001;
        }
        renderer.render(scene, camera);
        requestAnimationFrame(animate);
    }
    animate();
    updatePlanetName();
    let adaptUi = function()
    {
        let planetName = document.querySelector('.planetName');
        let planetInfosContainer = document.querySelector('.planetInfosContainer');
        planetInfosContainer.style.bottom = planetName.offsetHeight+"px";
   }
    window.addEventListener("resize", adaptUi, false);
    adaptUi();

/*
    let closeClassroomsList = function(event)
    {
        let planetInfosContainer = document.querySelector('.planetInfosContainer');
        if (event.clientX < planetInfosContainer.offsetLeft || event.clientX > (planetInfosContainer.offsetLeft + planetInfosContainer.offsetWidth) || event.clientY < planetInfosContainer.offsetTop || event.clientY > (planetInfosContainer.offsetTop + planetInfosContainer.offsetHeight))
        {

            planetInfosContainer.style.display = "none";
        }
    }
    document.body.addEventListener("touchstart", closeClassroomsList, false) || document.addEventListener("mousedown", closeClassroomsList, false);
*/
}


window.addEventListener('load', function()
{
    init();
});
