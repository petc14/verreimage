/**
 * =============================================
 * CONFIGURATEUR.JS - Verre & Image - 2025
 * - Logique pour le configurateur de plaque avancé
 * =============================================
 */
document.addEventListener('DOMContentLoaded', function() {
    // --- State Management ---
    const state = {
        currentStep: 1,
        options: {
            taille: '30x22',
            fixation: 'plaque_seule',
            verification: true,
            designer: false,
        },
        prices: {
            taille: 175,
            fixation: 0,
            verification: 10,
            designer: 0,
        }
    };

    // --- 3D Scene (Three.js) ---
    let scene, camera, renderer, plaque, base;
    
    // --- 2D Canvas for Personalization (Fabric.js) ---
    let fabricCanvas, texture;

    function init3D() {
        const container = document.getElementById('canvas-container');
        if (!container) return;

        // Scene setup
        scene = new THREE.Scene();
        camera = new THREE.PerspectiveCamera(50, container.clientWidth / container.clientHeight, 0.1, 1000);
        camera.position.set(0, 0, 5.5);
        
        // Renderer setup
        renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.setSize(container.clientWidth, container.clientHeight);
        container.appendChild(renderer.domElement);

        // Initialize 2D canvas for texture
        const fabricCanvasEl = document.getElementById('fabric-canvas');
        fabricCanvasEl.width = 800;
        fabricCanvasEl.height = 600;
        fabricCanvas = new fabric.Canvas('fabric-canvas');
        texture = new THREE.CanvasTexture(fabricCanvas.getElement());
        texture.anisotropy = renderer.capabilities.getMaxAnisotropy();

        // Plaque 3D setup
        const plaqueGeometry = new THREE.BoxGeometry(3, 2.25, 0.1);
        const plaqueMaterial = new THREE.MeshStandardMaterial({
            map: texture,
            roughness: 0.1,
            metalness: 0.2,
            side: THREE.DoubleSide
        });
        plaque = new THREE.Mesh(plaqueGeometry, plaqueMaterial);
        scene.add(plaque);
        
        // Base setup
        const baseGeometry = new THREE.BoxGeometry(3.5, 0.4, 0.6);
        const baseMaterial = new THREE.MeshStandardMaterial({ color: 0x555555, roughness: 0.5 });
        base = new THREE.Mesh(baseGeometry, baseMaterial);
        base.position.y = -1.325;
        scene.add(base);

        // Lighting
        const ambientLight = new THREE.AmbientLight(0xffffff, 1.2);
        scene.add(ambientLight);

        // Animation loop
        function animate() {
            requestAnimationFrame(animate);
            texture.needsUpdate = true; // Crucial for updating the 3D model with 2D changes
            renderer.render(scene, camera);
        }
        animate();

        window.addEventListener('resize', onWindowResize, false);
        setup3DControls();
    }

    function setup3DControls() {
        document.getElementById('zoom-in')?.addEventListener('click', () => { camera.position.z = Math.max(3, camera.position.z - 0.5); });
        document.getElementById('zoom-out')?.addEventListener('click', () => { camera.position.z = Math.min(10, camera.position.z + 0.5); });
        document.getElementById('rotate-left')?.addEventListener('click', () => { plaque.rotation.y -= Math.PI / 16; base.rotation.y -= Math.PI / 16;});
        document.getElementById('rotate-right')?.addEventListener('click', () => { plaque.rotation.y += Math.PI / 16; base.rotation.y += Math.PI / 16;});
    }

    function onWindowResize() {
        const container = document.getElementById('canvas-container');
        if (container && renderer) {
            camera.aspect = container.clientWidth / container.clientHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(container.clientWidth, container.clientHeight);
        }
    }

    // --- 2D Canvas Functions ---
    function updateFabricBackground(imageURL) {
        fabric.Image.fromURL(imageURL, (img) => {
            fabricCanvas.setBackgroundImage(img, fabricCanvas.renderAll.bind(fabricCanvas), {
                scaleX: fabricCanvas.width / img.width,
                scaleY: fabricCanvas.height / img.height,
            });
        });
    }

    function addTextToCanvas(text) {
        const textObj = new fabric.IText(text, {
            left: fabricCanvas.width / 2,
            top: fabricCanvas.height / 2,
            fontFamily: 'Montserrat',
            fontSize: 40,
            fill: 'white',
            originX: 'center',
            originY: 'center',
            shadow: 'rgba(0,0,0,0.5) 2px 2px 2px'
        });
        fabricCanvas.add(textObj);
        fabricCanvas.setActiveObject(textObj);
    }

    function addImageToCanvas(src) {
         fabric.Image.fromURL(src, (img) => {
            img.scaleToWidth(200);
            img.set({left: fabricCanvas.width / 2, top: fabricCanvas.height / 2, originX: 'center', originY: 'center'});
            fabricCanvas.add(img);
        });
    }

    // --- UI Update & Logic ---
    function updateUI() {
        document.querySelectorAll('.step').forEach(step => step.classList.toggle('active', parseInt(step.dataset.step) === state.currentStep));
        document.querySelectorAll('.panel-step').forEach(panel => panel.classList.toggle('active', parseInt(panel.id.split('-')[2]) === state.currentStep));
        
        document.getElementById('btn-back').disabled = (state.currentStep === 1);
        document.getElementById('btn-continue').textContent = (state.currentStep === 4) ? 'Commander' : 'Continuer';
        calculateTotalPrice();
    }

    function calculateTotalPrice() {
        let total = Object.values(state.prices).reduce((sum, price) => sum + price, 0);
        document.getElementById('total-price').textContent = `${total.toFixed(2).replace('.', ',')}€`;
    }

    // --- Event Listeners ---
    function setupEventListeners() {
        // Step navigation
        document.getElementById('btn-continue').addEventListener('click', () => {
            if (state.currentStep < 4) {
                state.currentStep++;
                updateUI();
            } else {
                alert('Ajout au panier (simulation).');
            }
        });

        document.getElementById('btn-back').addEventListener('click', () => {
            if (state.currentStep > 1) {
                state.currentStep--;
                updateUI();
            }
        });
        
        document.querySelectorAll('.step').forEach(step => {
           step.addEventListener('click', function() {
               state.currentStep = parseInt(this.dataset.step);
               updateUI();
           });
        });

        // Option selection
        document.querySelectorAll('.option-group[data-group]').forEach(group => {
            group.querySelectorAll('.option-box').forEach(box => {
                box.addEventListener('click', function() {
                    const groupName = group.dataset.group;
                    group.querySelectorAll('.option-box').forEach(otherBox => otherBox.classList.remove('selected'));
                    this.classList.add('selected');
                    state.options[groupName] = this.dataset.value;
                    state.prices[groupName] = parseFloat(this.dataset.price);
                    calculateTotalPrice();
                });
            });
        });

        // Background selection
        document.querySelectorAll('#fonds-container .option-box').forEach(box => {
            box.addEventListener('click', function() {
                updateFabricBackground(this.dataset.imgSrc);
            });
        });
        
        // Text input
        document.getElementById('text-input-1').addEventListener('input', (e) => {
            // This is a simple implementation. A real one would manage multiple text objects.
            const textObjects = fabricCanvas.getObjects('i-text');
            if(textObjects.length === 0) {
                 addTextToCanvas(e.target.value);
            } else {
                 textObjects[0].set('text', e.target.value);
                 fabricCanvas.renderAll();
            }
        });

        // Photo upload
        const uploadZone = document.getElementById('upload-zone');
        const photoUploadInput = document.getElementById('photo-upload');
        uploadZone.addEventListener('click', () => photoUploadInput.click());
        photoUploadInput.addEventListener('change', function(e) {
            if (e.target.files) {
                for (const file of e.target.files) {
                    addImageToCanvas(URL.createObjectURL(file));
                }
            }
        });

        // Options Etape 4
        document.querySelectorAll('.checkbox-option').forEach(option => {
            option.addEventListener('click', function(e){
                const checkbox = this.querySelector('input[type="checkbox"]');
                const groupName = this.dataset.group;
                if(e.target !== checkbox) checkbox.checked = !checkbox.checked;
                this.classList.toggle('selected', checkbox.checked);
                state.prices[groupName] = checkbox.checked ? parseFloat(checkbox.dataset.price) : 0;
                if (groupName === 'designer') {
                    document.getElementById('designer-comment').style.display = checkbox.checked ? 'block' : 'none';
                }
                calculateTotalPrice();
            });
        });
    }

    // --- Initialisation ---
    init3D();
    setupEventListeners();
    updateUI();
    updateFabricBackground('IMAGES/Fleur/ciel bleu.jpg'); // Set a default background
});
