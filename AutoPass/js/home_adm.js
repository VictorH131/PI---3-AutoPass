
    const entradasDia = <?= json_encode($grafDiaEnt) ?>;
    const saidasDia = <?= json_encode($grafDiaSai) ?>;

    const entradasSemana = <?= json_encode(array_values($grafSemanaEnt)) ?>;
    const saidasSemana = <?= json_encode(array_values($grafSemanaSai)) ?>;

    const entradasMes = <?= json_encode(array_values($grafMesEnt)) ?>;
    const saidasMes = <?= json_encode(array_values($grafMesSai)) ?>;

    const labelsDia = Array.from({ length: 24 }, (_, i) => i + "h");
    const labelsSemana = ["Dia1", "Dia2", "Dia3", "Dia4", "Dia5", "Dia6", "Dia7"];
    const labelsMes = Array.from({ length: 31 }, (_, i) => (i + 1).toString());

    const ctx = document.getElementById('graficoFluxo');

    let chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labelsDia,
            datasets: [
                {
                    label: 'Entradas',
                    data: entradasDia,
                    borderColor: '#00a884',
                    backgroundColor: 'rgba(0,168,132,0.12)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 0
                },
                {
                    label: 'Saídas',
                    data: saidasDia,
                    borderColor: '#ff5b5b',
                    backgroundColor: 'rgba(255,91,91,0.10)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0,
                    ticks: {
                        precision: 0
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    /* BOTÕES */
    btnDia.onclick = () => {
        setActive(btnDia);

        chart.data.labels = labelsDia;
        chart.data.datasets[0].data = entradasDia;
        chart.data.datasets[1].data = saidasDia;
        chart.update();
    };

    btnSemana.onclick = () => {
        setActive(btnSemana);

        chart.data.labels = labelsSemana;
        chart.data.datasets[0].data = entradasSemana;
        chart.data.datasets[1].data = saidasSemana;
        chart.update();
    };

    btnMes.onclick = () => {
        setActive(btnMes);

        chart.data.labels = labelsMes;
        chart.data.datasets[0].data = entradasMes;
        chart.data.datasets[1].data = saidasMes;
        chart.update();
    };

    function setActive(btn) {
        document.querySelectorAll(".btn-group button").forEach(b => {
            b.classList.remove("btn-dark");
            b.classList.add("btn-outline-dark");
        });

        btn.classList.add("btn-dark");
        btn.classList.remove("btn-outline-dark");
    }