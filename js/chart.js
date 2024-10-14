const DOUGNUT_CHART = document.getElementById('doughnut')
const BAR_GRAPH = document.getElementById('bar')

if (DOUGNUT_CHART !== null && BAR_GRAPH !== null) {

    let data;

    let xhr = new XMLHttpRequest()

    xhr.onreadystatechange = function () {
        if (this.readyState == 4) {
            let data = JSON.parse(this.responseText)
            loadGraph(data)
        }
    }

    xhr.open("GET", "/labFiles/blog_page/formHandlers/adminHandler.php?graphData=fetch");
    xhr.send()
}


function loadGraph(graphData) {

    const doughnutData = {
        labels: graphData[1],
        datasets: [{
            label: '# of Posts',
            data: graphData[0],
            borderWidth: 1
        }]
    }


    new Chart(DOUGNUT_CHART, {
        type: 'doughnut',
        data: doughnutData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const barData = {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [{
            label: '# of Signups',
            data: [],
            borderWidth: 1
        }]
    }

    new Chart(BAR_GRAPH, {
        type: 'bar',
        data: barData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}