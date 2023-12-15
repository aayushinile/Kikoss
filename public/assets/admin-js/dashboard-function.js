

 $(function() {
   var options = {
          series: [{
          name: 'Total Amount Received',
           xaxis: {
            categories: ['Jan','Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
         },
       
          data: [{
              x: 'Jan',
              y: 10
            },
            {
              x: 'Feb',
              y: 100
            },
            {
              x: 'Mar',
              y: 5000
            },
            {
              x: 'Apr',
              y: 1500
            },
            {
              x: 'May',
              y: 2000
            },
            {
              x: 'Jun',
              y: 100
            },
            {
              x: 'Jul',
              y: 3000
            },
            {
              x: 'Aug',
              y: 3500
            },
            {
              x: 'Sep',
              y: 3400
            },
            {
              x: 'Oct',
              y: 2000
            },
            {
              x: 'Nov',
              y: 5500
            },
            {
              x: 'Dec',
              y: 6000
            },
          ]
        }],
          chart: {
          height: 350,
          type: 'line',
          foreColor: '#000',
          
          toolbar: {
            show: false
          },
          zoom: {
            enabled: false
          }
        },

        colors: ['#4CBA08'],

        dataLabels: {
          enabled: false,

        },
        legend: {
          markers: {
            fillColors: ['#4CBA08']
          }
        },
        tooltip: {
          marker: {
            fillColors: ['#4CBA08'],
          },

        },




        stroke: {
          curve: 'smooth',
          colors:['#4CBA08'],
        },
        

        fill: {
          colors: ['#4CBA08'],
        },

        markers: {
          colors:  ['#4CBA08'],
        },
       
        xaxis: {
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          }
        },
        yaxis: {
          tickAmount: 4,
          floating: false,
          labels: {
            style: {
              colors: '#000',
            },
            offsetY: -7,
            offsetX: 0,
          },
          axisBorder: {
            show: false,
          },
          axisTicks: {
            show: false
          }
        },
        
        };

        var chart = new ApexCharts(document.querySelector("#chartBar"), options);
        chart.render();
      
  });