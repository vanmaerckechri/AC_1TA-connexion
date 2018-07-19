window.addEventListener('load', function()
{
// -- INIT --
    let renderer, scene, camera, gameIsLaunched;

    renderer = new THREE.WebGLRenderer({ antialias: false, alpha: true });
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    // If WebGL does not work on your browser you can use the Canvas rendering engine => renderer = new THREE.CanvasRenderer();
    renderer.setSize(window.innerWidth, window.innerHeight);
    document.getElementById('container').appendChild(renderer.domElement);

    scene = new THREE.Scene();

// -- EARTHS --
    let createPlanets = function()
    {
        let planet;
        let geometry = new THREE.SphereGeometry(100, 64, 64);
        let diffuseMap = new THREE.TextureLoader().load('assets/img/earth_hd_diffuse.png');
        let bumpMap = new THREE.TextureLoader().load('assets/img/earth_hd_bump.png');
        //let specularMap = new THREE.TextureLoader().load('assets/img/earth_specular.tif');
        let material = new THREE.MeshPhongMaterial
        ({
            color: 0xffffff,
            map: diffuseMap,
            bumpMap: bumpMap,
            bumpScale: 1,
            specularMap: bumpMap,
            transparent: false
        })
        planet = new THREE.Mesh(geometry, material);
        planet.position.set(0, -100, 0);
        planet.geometry.computeVertexNormals();
        planet.receiveShadow = true;
        planet.name = "myPlanet";

        // Clouds
        geometry = new THREE.SphereGeometry(101, 64, 64);
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
        geometry = new THREE.SphereGeometry(102, 64, 64);
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
        scene.add(planet);
    }
    createPlanets();
    
// -- CAMERA --
    camera = new THREE.PerspectiveCamera(50, window.innerWidth / window.innerHeight, 1, 250);
    camera.position.set(100, 0, 100);
    camera.lookAt(0, 0, 0);
    scene.add(camera);

// -- LIGHT --
    // Frontlight
    let directLight = new THREE.DirectionalLight(0xffffff, 1.5);
    directLight.position.set(-100, 50, 100);
    directLight.target.position.set(0, 0, 0);
    directLight.castShadow = true;
    scene.add(directLight);

    // BackLight
    let backLight = new THREE.DirectionalLight( 0xffffff, 5 );
    backLight.position.set(0, -50, 0);
    backLight.target.position.set(0, 0, 0);
    scene.add(backLight);

// -- LAUNCH GAME --
    let launchGame = function()
    {
        // add an breakable animation...
        // and launch the game!
        window.location.href = "index.php?action=game";
    }
    let disabledPlanetHover = function(atmo)
    {
        atmo.material.opacity = 0.08;
        document.body.style.cursor = "auto";
        document.onclick = null;
    }
    let hoverPlanetToLaunchGame = function(event)
    {
        if (gameIsLaunched != true)
        {
            let mouse = new THREE.Vector2();
            mouse.x = event.touches != undefined ? (event.touches[0].clientX / window.innerWidth) * 2 - 1 : (event.clientX / window.innerWidth ) * 2 - 1;
            mouse.y = event.touches != undefined ? -(event.touches[0].clientY / window.innerHeight) * 2 + 1 : -(event.clientY / window.innerHeight ) * 2 + 1;
            let raycaster = new THREE.Raycaster();
            raycaster.setFromCamera(mouse, camera);
            let intersects = raycaster.intersectObjects(scene.children);
            if (intersects[0] && intersects[0].object.name == "myPlanet")
            {      
                scene.children[0].children[1].material.opacity = 0.20;
                document.body.style.cursor = "pointer";
                document.onclick = function()
                {
                    gameIsLaunched = true;
                    disabledPlanetHover(scene.children[0].children[1]);
                    launchGame();
                };
            }
            else
            {
                disabledPlanetHover(scene.children[0].children[1]);
            }
        }
    }

// -- ANIMATION LOOP --
    let animate = function()
    {
        renderer.render(scene, camera);
        scene.children[0].rotation.y += 0.0002;
        scene.children[0].children[0].rotation.y += 0.0001;
        requestAnimationFrame(animate);
    }

    animate();
    document.addEventListener("mousemove", hoverPlanetToLaunchGame, false);

// -- NEXT MENU(LOCAL BACKGROUND) --
});