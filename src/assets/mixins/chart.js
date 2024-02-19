import moment from 'moment';

export default {
    methods: {
        init: function (data) {
            let minValue = null;
            let maxValue = null;
            let minDate = null;
            let maxDate = null;
            let shops = [];
            let lowestPrices = {};
            for (const [key, value] of Object.entries(data.data.shopping)) {
                const color = this.getColor();
                let shopping = {};
                shopping.label = value.name;
                shopping.fill = false;
                shopping.borderColor = color;
                shopping.backgroundColor = color;
                shopping.data = [];
                value.positions.forEach(function (position) {
                    if (minValue === null || minValue > position.price) {
                        minValue = position.price;
                    }
                    if (maxValue === null || maxValue < position.price) {
                        maxValue = position.price;
                    }
                    if (minDate === null || minDate > position.date) {
                        minDate = position.date;
                    }
                    if (maxDate === null || maxDate < position.date) {
                        maxDate = position.date;
                    }
                    if (lowestPrices[position.date] === undefined || lowestPrices[position.date] > position.price) {
                        lowestPrices[position.date] = position.price;
                    }
                    shopping.data.push({
                        y: position.price,
                        t: moment.unix(position.date).format('YYYY-MM-DD')
                    });
                });
                shops.push(shopping);
            }
            if (Object.keys(lowestPrices).length > 1) {
                shops.push(this.fillLowestPrices(lowestPrices));
            }
            this.renderChart({datasets: shops}, this.fillOptions(data.data.unit, minValue, maxValue, moment.unix(minDate), moment.unix(maxDate)));
        },
        getColor: function () {
            return '#' + (Math.random() * 0xFFFFFF << 0).toString(16);
        },
        fillLowestPrices: function (positions) {
            const color = this.getColor();
            let shopping = {};
            shopping.label = 'Najniższa cena';
            shopping.fill = false;
            shopping.borderColor = color;
            shopping.backgroundColor = color;
            shopping.showLine = true;
            shopping.pointRadius = 0;
            shopping.data = [];
            for (const [key, value] of Object.entries(positions)) {
                shopping.data.push({
                    y: value,
                    t: moment.unix(key).format('YYYY-MM-DD')
                });
            }
            return shopping;
        },
        fillOptions: function (unit, minValue, maxValue, minDate, maxDate) {
            let yMin;
            let yMax;
            let xMin;
            let xMax;
            if (minValue === maxValue) {
                yMin = minValue - minValue * 0.8;
                yMax = minValue + minValue * 0.8;
            } else {
                yMin = minValue - (maxValue - minValue) * 0.8;
                yMax = maxValue + (maxValue - minValue) * 0.8;
            }
            yMin = Math.floor(yMin);
            yMax = Math.ceil(yMax);
            if (minDate.unix() === maxDate.unix()) {
                xMin = minDate.add(-2, 'days').format('YYYY-MM-DD');
                xMax = maxDate.add(2, 'days').format('YYYY-MM-DD');
            } else {
                xMin = minDate.clone().add(Math.floor(minDate.diff(maxDate, 'days') * 0.8), 'days').format('YYYY-MM-DD');
                xMax = maxDate.clone().add(Math.ceil(maxDate.diff(minDate, 'days') * 0.8), 'days').format('YYYY-MM-DD');
            }
            return {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                            unit: 'day'
                        },
                        ticks: {
                            max: `${xMax}`,
                            min: `${xMin}`
                        }
                    }],
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: `zł / ${unit}`
                        },
                        ticks: {
                            max: yMax,
                            min: yMin
                        }
                    }]
                },
                tooltips: {
                    enabled: true,
                    callbacks: {
                        label: ((tooltipItems, data) => {
                            return `${data.datasets[tooltipItems.datasetIndex].label} - ${tooltipItems.value} zł [${tooltipItems.label}]`;
                        })
                    }
                }
            };
        }
    }
};