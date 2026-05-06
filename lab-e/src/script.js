// przekonwertowana kopia z typescripta, aby działało na github pages
var stylesMap = {
    "style1": "public/style-1.css",
    "style2": "public/style-2.css",
    "style3": "public/style-3.css"
};
var currentStyleKey = "style1";
var head = document.querySelector("head");
if (!head) {
    throw new Error("Nie udało się znaleźć elementu head");
}
var link = document.createElement("link");
link.rel = "stylesheet";
link.href = stylesMap[currentStyleKey];
head.appendChild(link);
function changeStyle(chosenStyleKey) {
    if (stylesMap[chosenStyleKey]) {
        link.remove(); // można było podmienić href, ale zadanie każe usuwać i dodawać na nowo
        link = document.createElement("link");
        link.rel = "stylesheet";
        link.href = stylesMap[chosenStyleKey];
        head === null || head === void 0 ? void 0 : head.appendChild(link);
        currentStyleKey = chosenStyleKey;
    }
}
var container = document.createElement("div");
container.id = "stylesbtn-container";
container.style.position = "absolute";
container.style.top = "10px";
container.style.left = "10px";
container.style.zIndex = "5";
container.style.display = "flex";
container.style.gap = "5px";
Object.keys(stylesMap).forEach(function (styleKey) {
    var btn = document.createElement("button");
    btn.textContent = "Zmień na " + styleKey;
    btn.onclick = function () {
        changeStyle(styleKey);
    };
    container.appendChild(btn);
});
document.body.appendChild(container);
