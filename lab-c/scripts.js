let targetPiece = null;

// Miejsce z przykładu
let mymap = L.map('map').setView([53.430127, 14.564802], 18);
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap'
}).addTo(mymap);

let marker = L.marker([53.430127, 14.564802]).addTo(mymap);
marker.bindPopup("Muzeum Narodowe w Szczecinie").openPopup();

document.getElementById("getLocation").addEventListener("click", () => {
    navigator.geolocation.getCurrentPosition((pos) => {
        const lat = pos.coords.latitude;
        const lon = pos.coords.longitude;
        mymap.setView([lat, lon], 18);
        marker.setLatLng([lat, lon]).setPopupContent("You are around here!").openPopup();
    }, (err) => console.warn(`ERROR(${err.code}): ${err.message}`), { enableHighAccuracy: true });
});

//https://www.w3schools.com/TAgs/canvas_clearrect.asp
document.getElementById("saveButton").addEventListener("click", () => {
    leafletImage(mymap, (error, canvas) => {
        let rasterCanvas = document.getElementById("rasterMap");
        let context = rasterCanvas.getContext('2d');
        context.clearRect(0, 0, rasterCanvas.width, rasterCanvas.height);
        context.drawImage(canvas, 0, 0, rasterCanvas.width, rasterCanvas.height);
        createPuzzle(rasterCanvas);
    });
});

function createPuzzle(canvas) {
    const board = document.getElementById("puzzle-board");
    board.innerHTML = ""; 
    
    const rows = 4;
    const cols = 4;
    const width = canvas.width / cols; 
    const height = canvas.height / rows;
    const pieces = [];

    for (let i = 0; i < 16; i++) {
        const col = i % cols;
        const row = Math.floor(i / cols);
        
        const sx = col * width;
        const sy = row * height;

        const tempCanvas = document.createElement('canvas');
        tempCanvas.width = width;
        tempCanvas.height = height;
        
        const context = tempCanvas.getContext('2d');
        context.drawImage(canvas, sx, sy, width, height, 0, 0, width, height);

        const img = new Image();
        img.src = tempCanvas.toDataURL();
        img.className = "puzzle-piece";
        img.dataset.correctIndex = i;
        img.draggable = true;

        img.ondragstart = () => { targetPiece = img; };
        img.ondragover = (e) => e.preventDefault();
        img.ondrop = swap_handler;

        pieces.push(img);
    }

    pieces.sort(() => Math.random() - 0.5);

    pieces.forEach(p => board.appendChild(p));
}

function swap_handler(e) {
    e.preventDefault();
    if (targetPiece === this) return;

    let tempSrc = targetPiece.src;
    let tempIdx = targetPiece.dataset.correctIndex;

    targetPiece.src = this.src;
    targetPiece.dataset.correctIndex = this.dataset.correctIndex;

    this.src = tempSrc;
    this.dataset.correctIndex = tempIdx;

    checkWin();
}

function checkWin() {
    const current = document.querySelectorAll('.puzzle-piece');
    let isSolved = Array.from(current).every((piece, i) => parseInt(piece.dataset.correctIndex) === i);
    if (isSolved) {
        console.debug("Puzzle completed!")
        if (Notification.permission === "granted") {
            new Notification("Puzzle completed!", { body: "The puzzle is correctly solved!" });
        }
    }
}

function checkNotificationSupport() {
    if (Notification.permission === "granted") {
        btn.innerText = "Notifications Enabled";
        btn.style.background = "#1f8348";
        btn.disabled = true;
        return true;
    }
    else return false;
}

document.getElementById("notificationsButton").addEventListener("click", () => {
    btn = document.getElementById("notificationsButton");
    if (checkNotificationSupport()) {
        return;
        console.debug("DEBUG: notifications already enabled.");
    } else {
    Notification.requestPermission().then((permission) => {
        if (permission === "granted") {
            console.debug("DEBUG: notifications enabled.");
            checkNotificationSupport();
            new Notification("Notifications Enabled!", {
                body: "Notifications will appear here!",
            });
        } else if (permission === "denied") {
            console.debug("DEBUG: notifications denied.");
            btn.innerText = "Notifications Blocked";
            btn.style.background = "#753030";
        }
    })};
});

document.getElementById("testButton").addEventListener("click", () => {
    new Notification("Test Notification", {
        body: "This is a test notification to check if everything works!",
    });
});