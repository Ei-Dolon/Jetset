document.addEventListener("DOMContentLoaded", function (event) {
    const nftChart = document.getElementById("nftChart");
    const stakingsChart = document.getElementById("stakingsChart");
    const crowfundingChart = document.getElementById("crowfundingChart");
    const tokensChart = document.getElementById("tokensChart");
  
    new Chart(nftChart, {
      type: "doughnut",
      data: {
        // labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
        datasets: [
          {
            label: "El textop que quiera acá",
            data: [12, 19, 3],
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: "top",
          },
          title: {
            display: false,
          },
        },
      },
    });

    new Chart(stakingsChart, {
        type: "doughnut",
        data: {
          // labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
          datasets: [
            {
              label: "El textop que quiera acá",
              data: [30, 19, 20],
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: "top",
            },
            title: {
              display: false,
            },
          },
        },
      });

      new Chart(crowfundingChart, {
        type: "doughnut",
        data: {
          // labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
          datasets: [
            {
              label: "El textop que quiera acá",
              data: [19, 5, 9],
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: "top",
            },
            title: {
              display: false,
            },
          },
        },
      });

      new Chart(tokensChart, {
        type: "doughnut",
        data: {
          // labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
          datasets: [
            {
              label: "El textop que quiera acá",
              data: [10, 25, 3],
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: "top",
            },
            title: {
              display: false,
            },
          },
        },
      });

});
  
