(function () {
  "use strict";

  const dataEl = document.getElementById("eval-data");
  if (!dataEl) return;

  const { controles, recomendaciones, grupos, rangos_madurez } = JSON.parse(dataEl.textContent);
  const form = document.getElementById("form-evaluacion");
  const dashboard = document.getElementById("eval-dashboard");

  const DIMENSIONES = ["C", "I", "D"];
  const NOMBRES = { C: "Confidencialidad", I: "Integridad", D: "Disponibilidad" };

  let chartBarras = null;
  let chartCircular = null;

  function nivelMadurez(pct) {
    const r = rangos_madurez.find((r) => pct >= r.min && pct <= r.max);
    return r ? r.nivel : 0;
  }

  function labelMadurez(nivel) {
    const r = rangos_madurez.find((r) => r.nivel === nivel);
    return r ? r.label : "";
  }

  function cumplimientoControl(respuestasControl) {
    const aplicables = respuestasControl.filter((r) => r !== "NA");
    if (aplicables.length === 0) return null;
    const sies = aplicables.filter((r) => r === "Si").length;
    return (sies / aplicables.length) * 100;
  }

  function respuestasDeControl(control, formEl) {
    return control.preguntas.map((p) => {
      const checked = (formEl || form).querySelector(`input[name="p${p.id}_resp"]:checked`);
      return checked ? checked.value : null;
    });
  }

  function actualizarMadurezEnVivo(controlId) {
    const control = controles.find((c) => c.id == controlId);
    const valorEl = form.querySelector(`[data-madurez-display-for="${controlId}"] .eval-control-madurez-value`);
    if (!control || !valorEl) return;

    const resp = respuestasDeControl(control);
    if (resp.some((r) => r === null)) {
      valorEl.textContent = "Pendiente";
      return;
    }
    const pct = cumplimientoControl(resp);
    if (pct === null) {
      valorEl.textContent = "No aplica";
      return;
    }
    const nivel = nivelMadurez(pct);
    valorEl.textContent = `Nivel ${nivel} — ${labelMadurez(nivel)} (${Math.round(pct)}% de "Sí")`;
  }

  form.querySelectorAll(".resp-radio").forEach((radio) => {
    radio.addEventListener("change", function () {
      actualizarMadurezEnVivo(this.dataset.control);
    });
  });

  function semaforo(pct) {
    if (pct <= 20) return { color: "var(--risk-low)", nivel: "Verde", texto: "Nivel de exposición al riesgo bajo." };
    if (pct <= 45) return { color: "var(--risk-mid)", nivel: "Amarillo", texto: "Exposición moderada: existen oportunidades de mejora." };
    return { color: "var(--risk-crit)", nivel: "Rojo", texto: "Exposición alta: se requieren acciones correctivas." };
  }

  function calcularControles(respuestas) {
    return controles.map((c) => {
      const resp = c.preguntas.map((p) => respuestas[p.id]);
      const pct = cumplimientoControl(resp);
      const madurez = pct === null ? null : nivelMadurez(pct);
      return { ...c, pctCumplimiento: pct, madurez };
    });
  }

  function riesgoPorDimension(controlesCalc) {
    const pct = {};
    DIMENSIONES.forEach((dim) => {
      let num = 0, den = 0;
      controlesCalc.forEach((c) => {
        const peso = c.w[dim] || 0;
        if (peso === 0 || c.madurez === null) return;
        const factor = c.peso * peso;
        num += (1 - c.madurez / 5) * factor;
        den += factor;
      });
      pct[dim] = den > 0 ? Math.round((num / den) * 100) : 0;
    });
    const global = Math.round((pct.C + pct.I + pct.D) / 3);
    return { pct, global };
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

  function renderControlesDebiles(controlesCalc) {
    const lista = document.getElementById("eval-weak-list");
    lista.innerHTML = "";
    const debiles = controlesCalc
      .filter((c) => c.madurez !== null && c.madurez < 3)
      .sort((a, b) => (a.madurez - b.madurez) || (b.peso - a.peso))
      .slice(0, 8);

    if (debiles.length === 0) {
      lista.innerHTML = "<li>Todos los controles evaluados alcanzan un nivel de madurez adecuado (3 o más).</li>";
      return;
    }
    debiles.forEach((c) => {
      const li = document.createElement("li");
      li.innerHTML = `<span class="eval-weak-estado estado-${c.madurez <= 1 ? 0 : 1}">${c.codigo} · Nivel ${c.madurez} — ${labelMadurez(c.madurez)}</span> ${c.nombre}`;
      lista.appendChild(li);
    });
  }

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const respuestas = {};
    controles.forEach((c) => {
      c.preguntas.forEach((p) => {
        const checked = form.querySelector(`input[name="p${p.id}_resp"]:checked`);
        respuestas[p.id] = checked ? checked.value : null;
      });
    });

    if (Object.values(respuestas).some((v) => v === null)) {
      alert("Por favor responde todas las preguntas (Sí / No / No aplica) antes de calcular los resultados.");
      return;
    }

    const controlesCalc = calcularControles(respuestas);
    const { pct, global } = riesgoPorDimension(controlesCalc);

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
    try { renderControlesDebiles(controlesCalc); } catch (err) { console.error("Error en renderControlesDebiles:", err); }

    dashboard.hidden = false;
    dashboard.scrollIntoView({ behavior: "smooth", block: "start" });
  });
})();