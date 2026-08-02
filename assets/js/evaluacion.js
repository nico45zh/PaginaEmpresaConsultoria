(function () {
  "use strict";

  const dataEl = document.getElementById("eval-data");
  if (!dataEl) return;

  const { preguntas, recomendaciones, grupos, niveles_madurez } = JSON.parse(dataEl.textContent);
  const form = document.getElementById("form-evaluacion");
  const dashboard = document.getElementById("eval-dashboard");

  const DIMENSIONES = ["C", "I", "D"];
  const NOMBRES = { C: "Confidencialidad", I: "Integridad", D: "Disponibilidad" };

  let chartBarras = null;
  let chartCircular = null;

  form.querySelectorAll(".resp-radio").forEach((radio) => {
    radio.addEventListener("change", function () {
      const pid = this.dataset.pid;
      const bloqueMadurez = form.querySelector(`[data-madurez-for="${pid}"]`);
      const select = bloqueMadurez.querySelector("select");
      if (this.value === "Si") {
        bloqueMadurez.hidden = false;
        select.required = true;
      } else {
        bloqueMadurez.hidden = true;
        select.required = false;
        select.value = "";
      }
    });
  });


  function semaforo(pct) {
    if (pct <= 20) return { color: "var(--risk-low)", nivel: "Verde", texto: "Nivel de exposición al riesgo bajo." };
    if (pct <= 45) return { color: "var(--risk-mid)", nivel: "Amarillo", texto: "Exposición moderada: existen oportunidades de mejora." };
    return { color: "var(--risk-crit)", nivel: "Rojo", texto: "Exposición alta: se requieren acciones correctivas." };
  }

  function calcular(respuestas) {
    const obtenido = { C: 0, I: 0, D: 0 };
    const maximo = { C: 0, I: 0, D: 0 };
    const detallePreguntas = [];

    preguntas.forEach((p) => {
      const r = respuestas[p.id];
      const madurez = r.resp === "No" ? 0 : (r.resp === "NA" ? null : r.madurez);

      DIMENSIONES.forEach((dim) => {
        const peso = p.w[dim] || 0;
        if (r.resp === "NA" || peso === 0) return;
        obtenido[dim] += peso * (5 - madurez);
        maximo[dim] += peso * 5;
      });

      const pesoTotal = p.w.C + p.w.I + p.w.D;
      detallePreguntas.push({ id: p.id, texto: p.texto, grupo: p.grupo, resp: r.resp, madurez, pesoTotal });
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

  function colorPlano(c) {
    return c.startsWith("var")
      ? getComputedStyle(document.documentElement).getPropertyValue(c.match(/--[\w-]+/)[0]).trim()
      : c;
  }

  function renderCharts(pct) {
    const ctxBarras = document.getElementById("chart-barras").getContext("2d");
    const ctxCircular = document.getElementById("chart-circular").getContext("2d");
    const colores = DIMENSIONES.map((d) => colorPlano(semaforo(pct[d]).color));

    if (chartBarras) chartBarras.destroy();
    if (chartCircular) chartCircular.destroy();

    chartBarras = new Chart(ctxBarras, {
      type: "bar",
      data: {
        labels: DIMENSIONES.map((d) => NOMBRES[d]),
        datasets: [{ label: "% de exposición al riesgo", data: DIMENSIONES.map((d) => pct[d]), backgroundColor: colores, borderRadius: 6 }],
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
        labels: ["Exposición", "Cobertura"],
        datasets: [{ data: [globalPct, 100 - globalPct], backgroundColor: [colorPlano(semaforo(globalPct).color), "#1B2740"], borderWidth: 0 }],
      },
      options: { cutout: "70%", plugins: { legend: { labels: { color: "#9AA7C2" } } } },
    });
  }

  function renderRecomendaciones(pct) {
    const lista = document.getElementById("eval-recos-list");
    lista.innerHTML = "";
    const conRiesgo = DIMENSIONES.filter((d) => pct[d] > 20).sort((a, b) => pct[b] - pct[a]);

    if (conRiesgo.length === 0) {
      lista.innerHTML = "<li>No se detectó exposición significativa: se recomienda mantener el monitoreo periódico de los controles vigentes.</li>";
      return;
    }
    conRiesgo.forEach((d) => {
      const li = document.createElement("li");
      li.innerHTML = `<strong>${NOMBRES[d]} (${pct[d]}% de exposición):</strong> ${recomendaciones[d]}`;
      lista.appendChild(li);
    });
  }

  function renderControlesDebiles(detalle) {
    const lista = document.getElementById("eval-weak-list");
    lista.innerHTML = "";
    const debiles = detalle
      .filter((p) => p.resp !== "NA" && p.madurez < 3 && p.pesoTotal > 0)
      .sort((a, b) => (a.madurez - b.madurez) || (b.pesoTotal - a.pesoTotal))
      .slice(0, 8);

    if (debiles.length === 0) {
      lista.innerHTML = "<li>Todos los controles evaluados alcanzan un nivel de madurez adecuado (3 o más).</li>";
      return;
    }
    debiles.forEach((p) => {
      const nivel = niveles_madurez[p.madurez];
      const li = document.createElement("li");
      li.innerHTML = `<span class="eval-weak-estado estado-${p.madurez <= 1 ? 0 : 1}">Nivel ${p.madurez} — ${nivel.label}</span> ${p.texto}`;
      lista.appendChild(li);
    });
  }

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const respuestas = {};
    let faltaMadurez = false;

    preguntas.forEach((p) => {
      const inputResp = form.querySelector(`input[name="p${p.id}_resp"]:checked`);
      const resp = inputResp ? inputResp.value : null;
      let madurez = null;

      if (resp === "Si") {
        const select = form.querySelector(`select[name="p${p.id}_madurez"]`);
        madurez = select && select.value !== "" ? parseInt(select.value, 10) : null;
        if (madurez === null) faltaMadurez = true;
      }
      respuestas[p.id] = { resp, madurez };
    });

    if (Object.values(respuestas).some((r) => r.resp === null)) {
      alert("Por favor responde todas las preguntas (Sí / No / No aplica) antes de calcular los resultados.");
      return;
    }
    if (faltaMadurez) {
      alert("Hay preguntas marcadas 'Sí' sin nivel de madurez seleccionado. Complétalas para continuar.");
      return;
    }

    const { pct, global, detallePreguntas } = calcular(respuestas);

    try { renderGlobal(global); } catch (err) { console.error("Error en renderGlobal:", err); }
    try { renderDimensionCards(pct); } catch (err) { console.error("Error en renderDimensionCards:", err); }
    try {
      if (typeof Chart === "undefined") throw new Error("Chart.js no se cargó.");
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