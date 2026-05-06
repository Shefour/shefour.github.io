const stylesMap: { [key: string]: string } = {
  "style1": "/style-1.css",
  "style2": "/style-2.css",
  "style3": "/style-3.css"
};

let currentStyleKey: string = "style1";

const head = document.querySelector("head");
if (!head) {
  throw new Error("Nie udało się znaleźć elementu head");
}

let link = document.createElement("link");
link.rel = "stylesheet";
link.href = stylesMap[currentStyleKey];
head.appendChild(link);

function changeStyle(chosenStyleKey: string) {
  if (stylesMap[chosenStyleKey]) {
    link.remove(); // można było podmienić href, ale zadanie każe usuwać i dodawać na nowo

    link = document.createElement("link");
    link.rel = "stylesheet";
    link.href = stylesMap[chosenStyleKey];

    head?.appendChild(link);
    currentStyleKey = chosenStyleKey;
  }
}

const container = document.createElement("div");
container.id = "stylesbtn-container";

container.style.position = "absolute";
container.style.top = "10px";
container.style.left = "10px";
container.style.zIndex = "5";
container.style.display = "flex";
container.style.gap = "5px";

Object.keys(stylesMap).forEach((styleKey) => {
  const btn = document.createElement("button");
  btn.textContent = "Zmień na " + styleKey;
  btn.onclick = () => {
    changeStyle(styleKey);
  };
  container.appendChild(btn);
});

document.body.appendChild(container);