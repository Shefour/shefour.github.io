const apiKey = "055914b5b49d50c1581b1ea226f8bffd";
const cityInput = document.getElementById("citySearch");
const currentDiv = document.getElementById("currentOutput");
const forecastDiv = document.getElementById("forecastOutput");

const bgMap = {
  "01": "01.jpg",
  "02": "02.jpg",
  "03": "03.jpg",
  "04": "04.jpg",
  "10": "10.jpg",
  "11": "11.jpg",
  "13": "13.jpg",
  "50": "50.jpg"
};

// preoloading
Object.values(bgMap).forEach(file => {
  const img = new Image();
  img.src = `assets/images/bg/${file}`;
});

function changeBackground(iconCode) {
  const prefix = iconCode.substring(0, 2);
  const file = bgMap[prefix] || "01.jpg";
  const path = `assets/images/bg/${file}`;

  const overlay = document.getElementById('bg-overlay');

  overlay.style.backgroundImage = `url('${path}')`;
  overlay.style.opacity = "1";

  setTimeout(() => {
    document.body.style.backgroundImage = `url('${path}')`;
    overlay.style.opacity = "0";
  }, 1200);
}

const searchForm = document.getElementById('searchForm');

searchForm.addEventListener('submit', function(event) {
    event.preventDefault();
    const city = cityInput.value.trim();
    if (city === "") {
        return;
    }

    getCurrentWeather(city);
    getForecast(city);
});

// wymagane do API Current Weather: https://openweathermap.org/current za pomocą XMLHttpRequest
function getCurrentWeather(city) {
  const xhr = new XMLHttpRequest();
  const url = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric`;
  xhr.open("GET", url);
  xhr.onload = () => {
    if (xhr.status === 200) {
      const data = JSON.parse(xhr.responseText);
      console.log("API: ", data);
      renderCurrent(data);
    }
  };
  xhr.send();
}

// wymagane do API 5 day forecast: https://openweathermap.org/forecast5 za pomocą Fetch API
async function getForecast(city) {
  const url = `https://api.openweathermap.org/data/2.5/forecast?q=${city}&appid=${apiKey}&units=metric`;
  const res = await fetch(url);
  if (res.ok) renderForecast(await res.json());
}

function renderCurrent(data) {
  const icon = data.weather[0].icon;
  changeBackground(icon);
  currentDiv.innerHTML = `
        <div class="panel current-grid">
            <div>
                <h2>${data.name}</h2>
                <p class="temp">${data.main.temp.toFixed(1)}°C</p>
                <p style="margin:2px 0; font-size: 0.9rem;">RealFeel: ${data.main.feels_like.toFixed(1)}°C</p>
                <span style="text-transform: capitalize; opacity: 0.8; font-size: 0.85rem;">${data.weather[0].description}</span>
            </div>
            <img src="https://openweathermap.org/img/wn/${icon}@4x.png" style="width:90px">
        </div>`;
}

function renderForecast(data) {
    let html = '<div class="panel" style="margin-bottom:0;"><h3 style="margin:0 0 10px 0; font-size:1.1rem;">Forecast</h3>';
    const forecastList = data.list.slice(0, 5);
    forecastList.forEach(function(item, index) {
        const timeLabel = item.dt_txt.substring(5, 16);
        const iconCode = item.weather[0].icon;
        html += `
            <div class="forecast-row">
                <div>
                    <strong>${timeLabel}</strong>
                    <span> | ${item.main.temp.toFixed(1)}°C</span><br>
                    <small style="opacity: 0.7;">${item.weather[0].description}</small>
                </div>
                <img src="https://openweathermap.org/img/wn/${iconCode}.png">
            </div>`;
        if (index < 4) {
            html += '<hr style="opacity:0.1; margin:2px 0;">';
        }
    });
    forecastDiv.innerHTML = html + '</div>';
}

function updateClock() {
  const now = new Date();
  const hours = now.getHours();
  const minutes = now.getMinutes().toString().padStart(2, '0');

  document.getElementById('timeDisplay').textContent = `${hours}:${minutes}`;

  let message = "";
  if (hours >= 5 && hours < 10) message = "Good morning";
  else if (hours >= 10 && hours < 12) message = "Good late morning";
  else if (hours >= 12 && hours < 18) message = "Good afternoon";
  else message = "Good night";

  document.getElementById('greetingDisplay').textContent = message;
}

updateClock();
setInterval(updateClock, 60000);
