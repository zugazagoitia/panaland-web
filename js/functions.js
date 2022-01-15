/*

         ██████  ██████  ███    ███ ███    ███  ██████  ███    ██
        ██      ██    ██ ████  ████ ████  ████ ██    ██ ████   ██
        ██      ██    ██ ██ ████ ██ ██ ████ ██ ██    ██ ██ ██  ██
        ██      ██    ██ ██  ██  ██ ██  ██  ██ ██    ██ ██  ██ ██
         ██████  ██████  ██      ██ ██      ██  ██████  ██   ████

 */

Chart.defaults.color = getComputedStyle(document.documentElement).getPropertyValue('--text-main');

const ourColors= ['#d7191c','#fdae61','#abd9e9','#2c7bb6'];

const algorithmicColors= [
    '#008080',
    '#ffa500',
    '#00ff00',
    '#0000ff',
    '#ff1493'
]
const algorithmicPatterns= pattern.generate(algorithmicColors);

const colors = ["#6ada56", "#31d69a", "#2d8aa9", "#972397",
    "#79524f", "#683a6e", "#37354a", "#d196d0",
    "#a38489", "#786d7f"];

const inverseColors = ["#786d7f", "#a38489", "#d196d0",
    "#37354a", "#683a6e", "#79524f", "#972397",
    "#2d8aa9", "#31d69a", "#6ada56"];

const patterns = pattern.generate(colors);
const alt_patterns = pattern.generate(inverseColors);



/**
 * Orders the supplied JSON data, and returns it as an array
 * @param parsedDailyJSON
 * @returns {*[]}
 */
function orderDailyStats(parsedDailyJSON){
    let temparr = [];
    for (let d in parsedDailyJSON) {
        temparr.push(parsedDailyJSON[d]);
    }
    return temparr.sort(function (a, b) {
        return a.id - b.id;
    });
}

/**
 * Displays server information on a card
 * @returns {Promise<void>}
 */
async function displayServerInfo() {
    let result = await server_info;

    console.log(result);
    if ('error' in result) {
        document.getElementById("portada-server-online").textContent = "Offline";
        document.getElementById("portada-server-version").textContent = "desconocida";

    } else {
        document.getElementById("portada-server-online").textContent = "Online";
        document.getElementById("portada-server-version").textContent = result.version.name;
        document.getElementById("portada-server-numplayers").textContent = result.players.online + '/' + result.players.max;


        for (let p of result.players.sample) {
            let uuid = p.id.replaceAll('-', '');
            let anchorElement = document.createElement('a');
            let divElement = document.createElement('div');
            let imgElement = document.createElement('img');
            anchorElement.classList.add("level-item", "has-text-centered");
            anchorElement.setAttribute("href", '?pag=perfil&user=' + p.name);

            imgElement.setAttribute('src', "https://crafatar.com/renders/head/" + uuid + "?size=150&overlay&default=MHF_Steve");
            imgElement.setAttribute('width', '80px');
            imgElement.setAttribute('title', p.name);

            let username = document.createElement('p');
            username.innerHTML = p.name;
            username.classList.add("heading");

            divElement.appendChild(imgElement);
            divElement.appendChild(username);

            anchorElement.appendChild(divElement);

            document.getElementById("portada-server-images").appendChild(anchorElement);
        }
    }


}

/*

         ██████ ██   ██  █████  ██████  ████████      ██
        ██      ██   ██ ██   ██ ██   ██    ██        ███
        ██      ███████ ███████ ██████     ██         ██
        ██      ██   ██ ██   ██ ██   ██    ██         ██
         ██████ ██   ██ ██   ██ ██   ██    ██         ██

 */


/**
 * Creates the required data arrays for the chart
 * @param parsedJSON
 * @returns {{kills: *[], imagesURL: *[], names: *[], deaths: *[]}}
 */
function killsDeathsChartData(parsedJSON) {
    let deaths = [];
    let imagesURL = [];
    let kills = [];
    let names = [];


    for (let p in parsedJSON) {
        kills.push(parsedJSON[p].kill);
        deaths.push(parsedJSON[p].death);
        imagesURL.push({
            width: 20,
            height: 20,
            src: 'https://crafatar.com/renders/head/' + parsedJSON[p].uuid + '?size=150&amp;overlay&amp;default=MHF_Steve'
        });
        names.push(parsedJSON[p].username);
    }

    return {deaths, kills, imagesURL, names};
}

/**
 * Creates the charts.js chart
 * @param chart
 * @param chartData
 * @param backgroundColors
 * @param borderColors
 * @returns {Promise<void>}
 */
async function createKillsDeathsChart(chart,chartData,backgroundColors,borderColors) {
    let delayed;
    new Chart(chart, {
        type: 'bar',
        data: {
            labels: chartData.names,
            datasets: [{
                label: 'Muertes',
                data: chartData.deaths,
                backgroundColor: backgroundColors[0],
                borderColor: borderColors[3],
                borderWidth: 1,
                yAxisID: 'y'
            }, {
                label: 'Kills',
                data: chartData.kills,
                backgroundColor: backgroundColors[2],
                borderColor: borderColors[3],
                borderWidth: 1,
                yAxisID: 'y1',
            }
            ]
        },
        options: {
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                },
            },
            animation: {
                onComplete: () => {
                    delayed = true;
                },
                delay: (context) => {
                    let delay = 0;
                    if (context.type === 'data' && context.mode === 'default' && !delayed) {
                        delay = context.dataIndex * 300 + context.datasetIndex * 100;
                    }
                    return delay;
                },
            }
        }
    })
}


/*


         ██████ ██   ██  █████  ██████  ████████     ██████
        ██      ██   ██ ██   ██ ██   ██    ██             ██
        ██      ███████ ███████ ██████     ██         █████
        ██      ██   ██ ██   ██ ██   ██    ██        ██
         ██████ ██   ██ ██   ██ ██   ██    ██        ███████


 */

/**
 * Creates the required data arrays for the chart
 * @param parsedJSON
 * @param tiempoTotal
 * @returns {{names: *[], tiempo: *[]}}
 */
function playtimeChartData(parsedJSON,tiempoTotal) {
    let tiempo = [];
    let names = [];

    for (let p in parsedJSON) {
        tiempo.push(parseInt((parsedJSON[p].playtime) / 3600).toFixed(2));
        names.push(parsedJSON[p].username);
    }

    document.getElementById('total-time').innerText = "Total: "+tiempoTotal;
    return {tiempo,names};
}

/**
 * Creates the charts.js chart
 * @param chart
 * @param chartData
 * @param backgroundColors
 * @param borderColors
 * @returns {Promise<Chart>}
 */
async function createPlaytimeChart(chart,chartData,backgroundColors,borderColors) {
    let delayed;
    return new Chart(chart, {
        type: 'pie',
        data: {
            labels: chartData.names,
            datasets: [{
                label: 'Muertes por Jugador',
                data: chartData.tiempo,
                backgroundColor: backgroundColors,
                borderColor: borderColors[3],
                borderWidth: 1
            }]
        },
        options:{
            animation: {
                onComplete: () => {
                    delayed = true;
                },
                delay: (context) => {
                    let delay = 0;
                    if (context.type === 'data' && context.mode === 'default' && !delayed) {
                        delay = context.dataIndex * 300 + context.datasetIndex * 100;
                    }
                    return delay;
                },
            }
        }
    })
}

/*


         ██████ ██   ██  █████  ██████  ████████     ██████
        ██      ██   ██ ██   ██ ██   ██    ██             ██
        ██      ███████ ███████ ██████     ██         █████
        ██      ██   ██ ██   ██ ██   ██    ██             ██
         ██████ ██   ██ ██   ██ ██   ██    ██        ██████


 */

/**
 * Creates the required data arrays for the chart
 * @param orderedDailyStats
 * @returns {{broken: *[], placed: *[], dates: *[]}}
 */
function brokenBlocksChartData(orderedDailyStats) {

    let broken = [];
    let placed = [];
    let dates = [];

    for (let item in orderedDailyStats) {
        broken.push(orderedDailyStats[item].block_break);
        placed.push(orderedDailyStats[item].block_place);
        let tmpdate = new Date(orderedDailyStats[item].date);
        dates.push(tmpdate.getDate() + "/" + (tmpdate.getMonth() + 1));
    }
    return {broken, placed, dates};
}

/**
 * Creates the charts.js chart
 * @param chart
 * @param chartData
 * @param backgroundColors
 * @param borderColors
 * @returns {Promise<Chart|number>}
 */
async function createBrokenBlocksChart(chart,chartData,backgroundColors,borderColors) {
    let broken = chartData.broken;
    let placed = chartData.placed;
    let dates = chartData.dates;
    let data = broken;

    let totalDuration = 50;
    let delayBetweenPoints = totalDuration / data.length;
    let previousY = (ctx) => ctx.index === 0 ? ctx.chart.scales.y.getPixelForValue(100) : ctx.chart.getDatasetMeta(ctx.datasetIndex).data[ctx.index - 1].getProps(['y'], true).y;
    let animation = {
        x: {
            type: 'number',
            easing: 'linear',
            duration: delayBetweenPoints,
            from: NaN, // the point is initially skipped
            delay(ctx) {
                if (ctx.type !== 'data' || ctx.xStarted) {
                    return 0;
                }
                ctx.xStarted = true;
                return ctx.index * delayBetweenPoints;
            }
        },
        y: {
            type: 'number',
            easing: 'linear',
            duration: delayBetweenPoints,
            from: previousY,
            delay(ctx) {
                if (ctx.type !== 'data' || ctx.yStarted) {
                    return 0;
                }
                ctx.yStarted = true;
                return ctx.index * delayBetweenPoints;
            }
        }
    };
    return new Chart(chart, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Bloques puestos',
                data: placed,
                fill: true,
                backgroundColor: backgroundColors[0],
                borderColor: borderColors[6],
                tension: 0.1,
                yAxisID: 'y'

            }, {
                label: 'Bloques rotos',
                data: broken,
                fill: true,
                backgroundColor: backgroundColors[2],
                borderColor: borderColors[6],
                tension: 0.1,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                },
            },
            animation: animation
        }
    })
}

/*

         ██████ ██   ██  █████  ██████  ████████     ██   ██
        ██      ██   ██ ██   ██ ██   ██    ██        ██   ██
        ██      ███████ ███████ ██████     ██        ███████
        ██      ██   ██ ██   ██ ██   ██    ██             ██
         ██████ ██   ██ ██   ██ ██   ██    ██             ██

 */

/**
 * Creates the required data arrays for the chart
 * @param orderedDailyStats
 * @returns {{dates: *[], pTime: *[]}}
 */
function playedTimeEvolutionChartData(orderedDailyStats) {
    let pTime = [];
    let dates = [];

    for (let item in orderedDailyStats) {
        pTime.push(orderedDailyStats[item].playtime);
        let tmpdate = new Date(orderedDailyStats[item].date);
        dates.push(tmpdate.getDate() + "/" + (tmpdate.getMonth() + 1));
    }
    return {pTime, dates};
}

/**
 * Creates the charts.js chart
 * @param chart
 * @param chartData
 * @param backgroundColor
 * @param borderColor
 * @returns {Promise<Chart|number>}
 */
async function createPlayedTimeEvolutionChart(chart, chartData, backgroundColor, borderColor) {

    let data = chartData.pTime;
    let dates = chartData.dates;
    let totalDuration = 50;
    let delayBetweenPoints = totalDuration / data.length;
    let previousY = (ctx) => ctx.index === 0 ? ctx.chart.scales.y.getPixelForValue(100) : ctx.chart.getDatasetMeta(ctx.datasetIndex).data[ctx.index - 1].getProps(['y'], true).y;
    let animation = {
        x: {
            type: 'number',
            easing: 'linear',
            duration: delayBetweenPoints,
            from: NaN, // the point is initially skipped
            delay(ctx) {
                if (ctx.type !== 'data' || ctx.xStarted) {
                    return 0;
                }
                ctx.xStarted = true;
                return ctx.index * delayBetweenPoints;
            }
        },
        y: {
            type: 'number',
            easing: 'linear',
            duration: delayBetweenPoints,
            from: previousY,
            delay(ctx) {
                if (ctx.type !== 'data' || ctx.yStarted) {
                    return 0;
                }
                ctx.yStarted = true;
                return ctx.index * delayBetweenPoints;
            }
        }
    };
    return new Chart(chart, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Tiempo Jugado',
                data: data,
                fill: true,
                backgroundColor: backgroundColor,
                borderColor: borderColor,
                tension: 0.1,

            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: animation
        }
    })
}
