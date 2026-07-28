(function () {
  "use strict";

  const dataEl = document.getElementById("eval-data");
  if (!dataEl) return;

  const { preguntas, recomendaciones, grupos } = JSON.parse(dataEl.textContent);
  const form = document.getElementById("form-evaluacion");
  const dashboard = document.getElementById("eval-dashboard");

  const DIMENSIONES = ["C", "I", "D"];
  const NOMBRES = { C: "Confidencialidad", I: "Integridad", D: "Disponibilidad" };

  let chartBarras = null;
  let chartCircular = null;

  function semaforo(pct) {
    if (pct >= 80) return { color: "var(--risk-low)", nivel: "Verde", texto: "Nivel adecuado de implementación de controles." };
    if (pct >= 60) return { color: "var(--risk-mid)", nivel: "Amarillo", texto: "Existen oportunidades de mejora." };
    return { color: "var(--risk-crit)", nivel: "Rojo", texto: "Nivel alto de riesgo y necesidad de acciones correctivas." };
  }

  function calcular(respuestas) {
    // respuestas: { [preguntaId]: 0|1|2 }
    const obtenido = { C: 0, I: 0, D: 0 };
    const maximo = { C: 0, I: 0, D: 0 };
    const detallePreguntas = [];

    preguntas.forEach((p) => {
      const puntos = respuestas[p.id];
      DIMENSIONES.forEach((dim) => {
        const peso = p.w[dim] || 0;
        obtenido[dim] += puntos * peso;
        maximo[dim] += 2 * peso; // 2 = puntaje máximo por pregunta
      });

      // score "efectivo" de la pregunta ponderado por su peso total, para detectar debilidades
      const pesoTotal = p.w.C + p.w.I + p.w.D;
      detallePreguntas.push({
        id: p.id,
        texto: p.texto,
        grupo: p.grupo,
        puntos,
        pesoTotal,
        debilidad: pesoTotal > 0 ? (puntos / 2) : 1, // 0 = totalmente débil, 1 = totalmente cubierto
      });
    });

    const pct = {};
    DIMENSIONES.forEach((dim) => {
      pct[dim] = maximo[dim] > 0 ? Math.round((obtenido[dim] / maximo[dim]) * 100) : 0;
    });

    const global = Math.round((pct.C + pct.I + pct.D) / 3);

    return { pct, global, detallePreguntas };
  }

  function renderDimensionCards(pct) {
    const container = document.getElementById("eval-dimension-cards");
    container.innerHTML = "";

    DIMENSIONES.forEach((dim) => {
      const s = semaforo(pct[dim]);
      const col = document.createElement("div");
      col.className = "col-md-4";
      col.innerHTML = `
        <div class="eval-dim-card" style="--dim-color:${s.color}">
          <span class="eval-dim-tag">${NOMBRES[dim]}</span>
          <div class="eval-dim-pct">${pct[dim]}%</div>
          <span class="eval-badge" style="background:${s.color}">${s.nivel}</span>
          <p class="eval-dim-interp">${s.texto}</p>
        </div>`;
      container.appendChild(col);
    });
  }

  function renderGlobal(global) {
    const s = semaforo(global);
    document.getElementById("eval-global-pct").textContent = global + "%";
    document.getElementById("eval-global-pct").style.color = s.color;
    const badge = document.getElementById("eval-global-badge");
    badge.textContent = s.nivel;
    badge.style.background = s.color;
    document.getElementById("eval-global-text").textContent = s.texto;
  }

  function renderCharts(pct) {
    const ctxBarras = document.getElementById("chart-barras").getContext("2d");
    const ctxCircular = document.getElementById("chart-circular").getContext("2d");

    const colores = DIMENSIONES.map((d) => semaforo(pct[d]).color.startsWith("var")
      ? getComputedStyle(document.documentElement).getPropertyValue(semaforo(pct[d]).color.match(/--[\w-]+/)[0]).trim()
      : semaforo(pct[d]).color);

    if (chartBarras) chartBarras.destroy();
    if (chartCircular) chartCircular.destroy();

    chartBarras = new Chart(ctxBarras, {
      type: "bar",
      data: {
        labels: DIMENSIONES.map((d) => NOMBRES[d]),
        datasets: [{
          label: "% de cumplimiento",
          data: DIMENSIONES.map((d) => pct[d]),
          backgroundColor: colores,
          borderRadius: 6,
        }],
      },
      options: {
        scales: {
          y: { beginAtZero: true, max: 100, ticks: { color: "#9AA7C2" }, grid: { color: "#23314B" } },
          x: { ticks: { color: "#9AA7C2" }, grid: { display: false } },
        },
        plugins: { legend: { display: false } },
      },
    });

    const globalPct = Math.round((pct.C + pct.I + pct.D) / 3);
    chartCircular = new Chart(ctxCircular, {
      type: "doughnut",
      data: {
        labels: ["Cumplimiento", "Brecha"],
        datasets: [{
          data: [globalPct, 100 - globalPct],
          backgroundColor: [semaforo(globalPct).color.startsWith("var")
            ? getComputedStyle(document.documentElement).getPropertyValue("--risk-" + (globalPct >= 80 ? "low" : globalPct >= 60 ? "mid" : "crit")).trim()
            : semaforo(globalPct).color, "#1B2740"],
          borderWidth: 0,
        }],
      },
      options: {
        cutout: "70%",
        plugins: { legend: { labels: { color: "#9AA7C2" } } },
      },
    });
  }

  function renderRecomendaciones(pct) {
    const lista = document.getElementById("eval-recos-list");
    lista.innerHTML = "";

    // Dimensiones con brecha relevante (por debajo de 80%), de peor a mejor
    const debiles = DIMENSIONES
      .filter((d) => pct[d] < 80)
      .sort((a, b) => pct[a] - pct[b]);

    if (debiles.length === 0) {
      lista.innerHTML = "<li>No se detectaron brechas significativas: se recomienda mantener el monitoreo periódico de los controles vigentes.</li>";
      return;
    }

    debiles.forEach((d) => {
      const li = document.createElement("li");
      li.innerHTML = `<strong>${NOMBRES[d]} (${pct[d]}%):</strong> ${recomendaciones[d]}`;
      lista.appendChild(li);
    });
  }

  function renderControlesDebiles(detalle) {
    const lista = document.getElementById("eval-weak-list");
    lista.innerHTML = "";

    const debiles = detalle
      .filter((p) => p.puntos < 2 && p.pesoTotal > 0)
      .sort((a, b) => (a.puntos - b.puntos) || (b.pesoTotal - a.pesoTotal))
      .slice(0, 8);

    if (debiles.length === 0) {
      lista.innerHTML = "<li>Todos los controles evaluados están completamente implementados.</li>";
      return;
    }

    debiles.forEach((p) => {
      const estado = p.puntos === 0 ? "No cumple" : "Cumple parcialmente";
      const li = document.createElement("li");
      li.innerHTML = `<span class="eval-weak-estado estado-${p.puntos}">${estado}</span> ${p.texto}`;
      lista.appendChild(li);
    });
  }

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const respuestas = {};
    preguntas.forEach((p) => {
      const input = form.querySelector(`input[name="p${p.id}"]:checked`);
      respuestas[p.id] = input ? parseInt(input.value, 10) : null;
    });

    if (Object.values(respuestas).some((v) => v === null)) {
      // El atributo required de cada radio ya evita esto en navegadores modernos,
      // pero se valida por si acaso.
      alert("Por favor responde todas las preguntas antes de calcular los resultados.");
      return;
    }

    const { pct, global, detallePreguntas } = calcular(respuestas);

    // Cada bloque se aísla: si uno falla (p.ej. el CDN de Chart.js no cargó),
    // el resto del panel igual se muestra en vez de quedar todo en blanco.
    try { renderGlobal(global); } catch (err) { console.error("Error en renderGlobal:", err); }
    try { renderDimensionCards(pct); } catch (err) { console.error("Error en renderDimensionCards:", err); }
    try {
      if (typeof Chart === "undefined") {
        throw new Error("Chart.js no se cargó (revisa la conexión o el CDN en evaluacion-riesgos.php).");
      }
      renderCharts(pct);
    } catch (err) {
      console.error("Error en renderCharts:", err);
      const chartsRow = document.getElementById("chart-barras")?.closest(".row");
      if (chartsRow) chartsRow.insertAdjacentHTML("beforebegin",
        '<p class="eval-submit-hint" style="color:var(--risk-crit)">No se pudieron cargar los gráficos (Chart.js). El resto de los resultados sí está disponible abajo.</p>');
    }
    try { renderRecomendaciones(pct); } catch (err) { console.error("Error en renderRecomendaciones:", err); }
    try { renderControlesDebiles(detallePreguntas); } catch (err) { console.error("Error en renderControlesDebiles:", err); }

    dashboard.hidden = false;
    dashboard.scrollIntoView({ behavior: "smooth", block: "start" });
  });
})();
