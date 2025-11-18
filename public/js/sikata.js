document.addEventListener("DOMContentLoaded", () => {
    // Elements
    const topicsEl = document.getElementById("topics");
    const topicChosenEl = document.getElementById("topicChosen");
    const quizEl = document.getElementById("quiz");
    const questionText = document.getElementById("questionText");
    const optionsEl = document.getElementById("options");
    const qnum = document.getElementById("qnum");
    const qtotal = document.getElementById("qtotal");
    const scoreEl = document.getElementById("score");
    const nextBtn = document.getElementById("nextBtn");
    const backBtn = document.getElementById("backBtn");
    const explanationEl = document.getElementById("explanation");
    const infoEl = document.getElementById("gameInfo");
    const quizHeaderEl = document.getElementById("quizHeader");
    const topicTitleEl = document.getElementById("topicTitle");

    // State
    let apiTopics = [];
    let currentTopic = null;
    let currentCase = null;
    let selectedIndexes = new Set();
    // Stage 2 state
    let currentMode = "identify"; // identify | start-stage2 | stage2-page | done-summary | done-final
    let identifyScore = 0;
    let lastIdentifyResult = null; // persist Stage 1 result for returning from Stage 2
    let correctionsData = [];
    let selectedCorrections = new Map(); // index -> correction_id
    let currentCorrectionPage = 0; // page index for Stage 2 per-question flow
    let finalResult = null; // store corrections result for final article page

    const suits = [
        { cls: "s", rot: -12, dx: -80, z: 3 },
        { cls: "k", rot: 0, dx: 0, z: 2 },
        { cls: "t", rot: 12, dx: 80, z: 1 },
    ];

    // Helpers
    function setLoading(el, loading = true) {
        if (!el) return;
        el.dataset.loading = loading ? "1" : "0";
    }
    function htmlEscape(s) {
        return s.replace(
            /[&<>]/g,
            (c) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;" }[c])
        );
    }
    function updateProgress() {
        if (!currentCase) {
            qnum.textContent = "0";
            qtotal.textContent = "0";
            scoreEl.textContent = "0";
            return;
        }
        qnum.textContent = String(selectedIndexes.size);
        qtotal.textContent = String(currentCase.sentences.length);
    }

    // Fetch topics from API
    async function loadTopics() {
        try {
            const res = await fetch("/api/sikata/topics");
            const data = await res.json();
            if (!data.topics) throw new Error("Invalid topics payload");
            apiTopics = data.topics; // [{id,slug,name}]
            renderTopics();
        } catch (err) {
            nextBtn.textContent = "Redaktur Pintar"; // Stage 2 trigger
            if (topicsEl)
                topicsEl.innerHTML =
                    '<div style="color:#b00020">Gagal memuat topik. Coba muat ulang.</div>';
        }
    }

    function renderTopics() {
        if (!topicsEl) return;
        topicsEl.innerHTML = "";
        topicsEl.style.display = "flex";
        setInfo("select");
        // Ensure fixed order: PS (pelecehan-seksual), S (sara), E (ekonomi)
        const order = ["pelecehan-seksual", "sara", "ekonomi"];
        const topicsOrdered = [...apiTopics].sort((a, b) => {
            const ia = order.indexOf(a.slug);
            const ib = order.indexOf(b.slug);
            return (ia === -1 ? 99 : ia) - (ib === -1 ? 99 : ib);
        });
        topicsOrdered.forEach((t, i) => {
            const s = suits[i % suits.length];
            const card = document.createElement("div");
            card.className = `playing-card ${s.cls}`;
            const base = `translateX(${s.dx}px) rotate(${s.rot}deg)`;
            card.style.transform = base;
            card.dataset.base = base;
            card.dataset.dx = String(s.dx);
            card.dataset.rot = String(s.rot);
            card.style.zIndex = s.z;
            card.tabIndex = 0;
            // Abbreviation per topic slug: PS (pelecehan-seksual), S (sara), E (ekonomi)
            let ch = "T";
            if (t && typeof t.slug === "string") {
                if (t.slug === "pelecehan-seksual") ch = "PS";
                else if (t.slug === "sara") ch = "S";
                else if (t.slug === "ekonomi") ch = "E";
                else if (t.name && t.name.trim())
                    ch = t.name.trim().charAt(0).toUpperCase();
            }
            card.innerHTML = `
        <div class="pc-inner">
          <div class="pc-face front">
                        <div class="pc-corner tl">${ch}</div>
                        <div class="pc-center">${ch}</div>
                        <div class="pc-corner br">${ch}</div>
            <div class="pc-label">${htmlEscape(t.name)}</div>
          </div>
          <div class="pc-face back">
            <div style="text-align:center">
              <div style="font-weight:700;color:#2d5fbf;margin-bottom:6px">Topik</div>
              <div style="font-size:16px;color:#1b4a7a">${htmlEscape(
                  t.name
              )}</div>
            </div>
          </div>
        </div>`;
            card.addEventListener("click", () => startTopic(t, card));
            topicsEl.appendChild(card);
        });
        setupSpreadInteractions(topicsEl);
    }

    function setupSpreadInteractions(container) {
        if (!container) return;
        const isTouch = window.matchMedia("(pointer: coarse)").matches;
        const cards = Array.from(container.querySelectorAll(".playing-card"));
        const reset = () => {
            cards.forEach((c) => {
                c.style.transform = c.dataset.base || "";
                c.classList.remove("spread-target");
                c.style.zIndex = c.style.zIndex || "";
            });
        };
        if (isTouch) {
            reset();
            return;
        }
        const getSpread = () => {
            const v = getComputedStyle(document.documentElement)
                .getPropertyValue("--spread")
                .trim();
            const n = parseFloat(v.replace("px", ""));
            return isNaN(n) ? 120 : n;
        };
        const spread = (target) => {
            const dist = getSpread();
            const targetDx = parseFloat(target.dataset.dx || "0");
            cards.forEach((c) => {
                const dx = parseFloat(c.dataset.dx || "0");
                const rot = parseFloat(c.dataset.rot || "0");
                let tx = dx;
                if (c !== target) {
                    tx =
                        dx + (dx < targetDx ? -dist : dx > targetDx ? dist : 0);
                }
                const isTarget = c === target;
                const ty = isTarget ? -8 : 0;
                c.style.transform =
                    `translateX(${tx}px) rotate(${rot}deg) translateY(${ty}px)` +
                    (isTarget
                        ? ` scale(${
                              getComputedStyle(document.documentElement)
                                  .getPropertyValue("--hover-scale")
                                  .trim() || "1.04"
                          })`
                        : "");
                c.classList.toggle("spread-target", isTarget);
                if (isTarget) c.style.zIndex = "9";
            });
        };
        cards.forEach((c) => {
            c.addEventListener("mouseenter", () => spread(c));
            c.addEventListener("mouseleave", reset);
            c.addEventListener("focusin", () => spread(c));
            c.addEventListener("blur", reset);
        });
    }

    async function startTopic(topic, cardEl) {
        try {
            // Flip animation
            if (cardEl) {
                cardEl.classList.add("flipped");
            }
            const reduce = window.matchMedia(
                "(prefers-reduced-motion: reduce)"
            ).matches;
            const delay = reduce ? 0 : 750;
            await new Promise((r) => setTimeout(r, delay));

            if (topicChosenEl) {
                topicChosenEl.textContent = `Topik dipilih: ${topic.name}`;
                topicChosenEl.style.display = "block";
            }
            if (topicsEl) topicsEl.style.display = "none";

            // Call start API
            setLoading(quizEl, true);
            const res = await fetch("/api/sikata/start", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ topic_slug: topic.slug }),
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.error || "Gagal memulai sesi");

            currentTopic = topic;
            currentCase = data.case; // {id,title,sentences:[{index,text}]}
            selectedIndexes = new Set();
            if (quizEl) quizEl.style.display = "block";
            if (quizHeaderEl) quizHeaderEl.style.display = "none"; // hide progress header for Stage 1
            if (topicTitleEl) topicTitleEl.style.display = "none"; // hide 'Pilih Topik'
            if (explanationEl) {
                explanationEl.style.display = "none";
                explanationEl.innerHTML = "";
            }
            if (nextBtn) {
                nextBtn.disabled = true;
                nextBtn.textContent = "Submit"; // Stage 1 submit label
                currentMode = "identify";
            }
            if (backBtn) {
                backBtn.style.display = "none";
            }
            setInfo("topic", { title: topic.name });
            renderSentences();
        } catch (err) {
            console.error(err);
            if (topicChosenEl) {
                topicChosenEl.style.display = "block";
                topicChosenEl.textContent = "Gagal memulai kuis. Coba lagi.";
            }
            if (topicsEl) {
                topicsEl.style.display = "flex";
            }
        } finally {
            setLoading(quizEl, false);
        }
    }

    function renderSentences() {
        if (!currentCase) return;
        if (questionText) {
            questionText.innerHTML = `
                            <div style="font-weight:800;color:#2d5fbf;margin-bottom:6px">SESI SOROT BERITA</div>
                            <div style="font-size:13px;color:#1b4a7a;margin-bottom:10px">Tandai kalimat yang melanggar etika jurnalistik.</div>
                            <div style="font-weight:700;margin-bottom:4px">Judul</div>
              <div style="background:#fff;border:2px solid #e7eefc;border-radius:10px;padding:8px 10px;margin-bottom:10px;line-height:1.5">${htmlEscape(
                  currentCase.title || ""
              )}</div>
              <div style="font-weight:700;margin-bottom:8px">Isi Berita Lengkap</div>
              <div class="article-body" style="position:relative;background:#fff;border:2px solid #cfe0ff;border-radius:12px;padding:12px;line-height:1.6;font-size:14px;max-height:260px;overflow:auto"></div>
              <div style="margin-top:12px;font-weight:700">Pilih kalimat yang menyalahi etika (multi-pilih)</div>
            `;
            const articleBody = questionText.querySelector(".article-body");
            if (articleBody) {
                articleBody.innerHTML = "";
                currentCase.sentences.forEach((s) => {
                    const span = document.createElement("span");
                    span.className = "sentence";
                    span.dataset.index = String(s.index);
                    span.textContent = `(${s.index}) ${s.text} `;
                    articleBody.appendChild(span);
                });
            }
        }
    // (Removed stray misplaced nextBtn.textContent line inserted by earlier patch)
        currentCase.sentences.forEach((s) => {
            const o = document.createElement("div");
            o.className = "opt";
            o.dataset.index = String(s.index);
            o.innerHTML = `<strong>Kalimat ${s.index}</strong>`;
            o.addEventListener("click", () => toggleSelect(o, s.index));
            optionsEl.appendChild(o);
        });
        updateProgress();
        setInfo("identify");
    }

    function toggleSelect(el, idx) {
        if (el.classList.contains("correct") || el.classList.contains("wrong"))
            return; // locked after submit
        if (selectedIndexes.has(idx)) {
            selectedIndexes.delete(idx);
            el.classList.remove("selected");
        } else {
            selectedIndexes.add(idx);
            el.classList.add("selected");
        }
        if (nextBtn) nextBtn.disabled = selectedIndexes.size === 0;
        updateProgress();
    }

    // Removed obsolete duplicate nextBtn listener (now unified earlier for multi-stage flow)

    // Unified next button handler (identify -> stage2 per page -> submit corrections -> final done)
    async function confirmSubmitDialog(opts) {
        const { title, text, confirmText } = opts || {};
        if (window.Swal && typeof window.Swal.fire === "function") {
            const res = await window.Swal.fire({
                title: title || "Kirim Jawaban?",
                text: text || "Yakin ingin mengirim jawaban ini?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: confirmText || "Ya, kirim",
                cancelButtonText: "Batal",
            });
            return !!res.isConfirmed;
        }
        return window.confirm(text || "Yakin ingin mengirim jawaban ini?");
    }

    if (nextBtn) {
        nextBtn.addEventListener("click", async () => {
            if (nextBtn.disabled) return;
            if (currentMode === "identify") {
                const ok = await confirmSubmitDialog({
                    title: "Kirim Jawaban Sorot Berita?",
                    text: "Kirim pilihan kalimat yang bermasalah?",
                });
                if (!ok) return;
                console.log("[SIKATA] Submit identify confirmed");
                submitIdentify();
            } else if (currentMode === "start-stage2") {
                console.log("[SIKATA] Start Stage2 clicked");
                startStage2();
            } else if (currentMode === "stage2-page") {
                const isLast =
                    currentCorrectionPage >= correctionsData.length - 1;
                if (isLast) {
                    const ok = await confirmSubmitDialog({
                        title: "Kirim Jawaban Redaktur Pintar?",
                        text: "Nilai jawaban koreksi sekarang?",
                    });
                    if (!ok) return;
                    console.log("[SIKATA] Submit corrections confirmed");
                    submitCorrections();
                } else {
                    console.log(
                        "[SIKATA] Stage2 page advance clicked page=" +
                            currentCorrectionPage
                    );
                    goNextCorrectionPage();
                }
            } else if (currentMode === "done-summary") {
                renderFinalArticlePage();
            } else if (currentMode === "done-final") {
                // Finish flow and reset to topics
                resetToTopics();
            }
        });
    }

    async function submitIdentify() {
        try {
            if (!currentCase) return;
            nextBtn.disabled = true;
            const payload = {
                case_id: currentCase.id,
                selected: Array.from(selectedIndexes.values()),
            };
            nextBtn.textContent = "Menilai...";
            const res = await fetch("/api/sikata/identify", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(payload),
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.error || "Gagal menilai jawaban");
            renderIdentifyResult(data);
        } catch (err) {
            console.error(err);
            if (explanationEl) {
                explanationEl.style.display = "block";
                explanationEl.innerHTML =
                    '<span style="color:#b00020">Terjadi kesalahan menilai. Coba lagi.</span>';
            }
            nextBtn.textContent = "Kunci Jawaban";
            currentMode = "identify";
            nextBtn.disabled = selectedIndexes.size === 0;
        }
    }

    function renderIdentifyResult(result) {
        lastIdentifyResult = result;
        // Lock options & color them
        const correctSet = new Set(result.correct_indexes || []);
        const fpSet = new Set(result.false_positive_indexes || []);
        Array.from(optionsEl.children).forEach((node) => {
            const idx = parseInt(node.dataset.index || "0", 10);
            node.classList.remove("selected");
            if (correctSet.has(idx)) node.classList.add("correct");
            if (fpSet.has(idx)) node.classList.add("wrong");
        });
        // Show explanations: only problematic sentences from DB (not full article)
        if (explanationEl) {
            explanationEl.style.display = "block";
            const idxToText = new Map(
                (currentCase?.sentences || []).map((s) => [s.index, s.text])
            );
            const rawMap = result.violations_by_index || {};
            const indexes = Object.keys(rawMap)
                .map((k) => parseInt(k, 10))
                .sort((a, b) => a - b);
            const list = indexes
                .map((idx) => {
                    const items = rawMap[String(idx)] || [];
                    const itemsHtml = items
                        .map((e) => {
                            const basis = e.legal_basis
                                ? `<div style="color:#2d5fbf;font-size:12px;margin-top:2px">Dasar: ${htmlEscape(
                                      e.legal_basis
                                  )}</div>`
                                : "";
                            const title = e.violation_title
                                ? htmlEscape(e.violation_title)
                                : "Pelanggaran";
                            const desc = e.description
                                ? htmlEscape(e.description)
                                : "";
                            const frag = e.snippet
                                ? `<div style=\"font-style:italic;color:#3c5e96;background:#f5f9ff;border:1px solid #dfeaff;border-radius:8px;padding:6px 8px;margin:6px 0\">“${htmlEscape(
                                      e.snippet
                                  )}”</div>`
                                : "";
                            return `${frag}<div style=\"margin-bottom:6px\"><strong>${title}</strong><div>${desc}</div>${basis}</div>`;
                        })
                        .join("");
                    return `
                        <div style="margin-bottom:12px;padding-bottom:10px;border-bottom:1px dashed #e2e8ff">
                          <div style="font-weight:700;color:#1b4a7a;margin-bottom:4px">Kalimat ${idx}</div>
                          ${itemsHtml}
                        </div>`;
                })
                .join("");
            explanationEl.innerHTML = `<div style="font-weight:800;color:#2d5fbf;margin-bottom:8px">Ringkasan Sorot Berita</div>${
                list ||
                "<div>Tidak ada pelanggaran yang tercatat di basis data.</div>"
            }`;
        }
        // Score display reuse header progress area
        identifyScore = result.score_identify || 0;
        if (scoreEl) {
            scoreEl.textContent = String(identifyScore);
        }
        if (quizHeaderEl) quizHeaderEl.style.display = "none"; // keep hidden
        if (backBtn) {
            backBtn.style.display = "inline-block";
        }

        // Highlight violation sentences in the article and attach tooltips
        attachViolationHighlights(result);

        // Prepare Stage 2
        if (nextBtn) {
            nextBtn.disabled = false;
            // Updated label per requirement: use "Redaktur Pintar" as Stage 2 trigger
            nextBtn.textContent = "Redaktur Pintar";
            currentMode = "start-stage2";
            console.log(
                "[SIKATA] Ready to start Stage2, mode=start-stage2 (button=Redaktur Pintar)"
            );
        }
        setInfo("identifyResult");
    }

    function attachViolationHighlights(result) {
        const articleBody = questionText
            ? questionText.querySelector(".article-body")
            : null;
        if (!articleBody) return;
        const allViolations = new Set(result.all_violation_indexes || []);
        const correctSet = new Set(result.correct_indexes || []);
        const vmap = result.violations_by_index || {};

        // Ensure single tooltip element exists inside article body for local positioning
        let tooltip = articleBody.querySelector(".vio-tooltip");
        if (!tooltip) {
            tooltip = document.createElement("div");
            tooltip.className = "vio-tooltip";
            tooltip.style.display = "none";
            articleBody.appendChild(tooltip);
        }

        const isTouch = window.matchMedia("(pointer: coarse)").matches;
        let currentTipTarget = null;
        let onScrollHandler = null;
        const hide = () => {
            tooltip.style.display = "none";
            currentTipTarget = null;
            if (onScrollHandler) {
                articleBody.removeEventListener("scroll", onScrollHandler);
                onScrollHandler = null;
            }
        };
        const positionTooltip = (target) => {
            if (!target) return;
            // Add spacer once (to allow scroll beyond last sentence so tooltip above/below fits)
            if (!articleBody.querySelector(".tooltip-spacer")) {
                const spacer = document.createElement("div");
                spacer.className = "tooltip-spacer";
                spacer.style.height = "140px";
                spacer.style.width = "100%";
                spacer.style.pointerEvents = "none";
                articleBody.appendChild(spacer);
            }
            if (tooltip.style.display !== "block")
                tooltip.style.display = "block";
            const tWidth = tooltip.offsetWidth || 280;
            const tHeight = tooltip.offsetHeight || 60;
            const containerWidth = articleBody.clientWidth;
            // Use offsetTop relative to scrolling container
            const sentenceTop = target.offsetTop;
            const sentenceLeft = target.offsetLeft;
            let left = sentenceLeft;
            if (left + tWidth > containerWidth - 8)
                left = containerWidth - tWidth - 8;
            if (left < 8) left = 8;
            const viewTop = articleBody.scrollTop;
            const viewBottom = viewTop + articleBody.clientHeight;
            // Preferred positions
            const aboveTop = sentenceTop - tHeight - 8;
            const belowTop = sentenceTop + target.offsetHeight + 8;
            let top;
            // Decide placement considering visible window inside container
            const canPlaceAbove = aboveTop >= viewTop;
            const canPlaceBelow = belowTop + tHeight <= viewBottom;
            if (canPlaceAbove) {
                top = aboveTop;
                tooltip.setAttribute("data-pos", "above");
            } else if (canPlaceBelow) {
                top = belowTop;
                tooltip.setAttribute("data-pos", "below");
            } else {
                // Need scroll then re-evaluate. Prefer above if sentence near bottom.
                if (belowTop + tHeight > viewBottom) {
                    articleBody.scrollTo({
                        top: belowTop + tHeight - articleBody.clientHeight + 20,
                        behavior: "smooth",
                    });
                } else if (aboveTop < viewTop) {
                    articleBody.scrollTo({
                        top: Math.max(aboveTop - 20, 0),
                        behavior: "smooth",
                    });
                }
                // After scroll, reposition
                setTimeout(() => positionTooltip(target), 200);
                return;
            }
            tooltip.style.left = `${left}px`;
            tooltip.style.top = `${top}px`;
            // Ensure fully visible; if clipped at top after scroll, adjust.
            const finalViewTop = articleBody.scrollTop;
            const finalViewBottom = finalViewTop + articleBody.clientHeight;
            const tipBottom = top + tHeight + 4;
            if (top < finalViewTop) {
                articleBody.scrollTo({
                    top: top - 12 < 0 ? 0 : top - 12,
                    behavior: "smooth",
                });
                setTimeout(() => positionTooltip(target), 180);
            } else if (tipBottom > finalViewBottom) {
                const need = tipBottom - finalViewBottom + 12;
                articleBody.scrollTo({
                    top: finalViewTop + need,
                    behavior: "smooth",
                });
                setTimeout(() => positionTooltip(target), 180);
            }
        };
        const showAt = (target, html) => {
            tooltip.innerHTML = html;
            tooltip.style.display = "block";
            currentTipTarget = target;
            positionTooltip(target);
            if (!onScrollHandler) {
                onScrollHandler = () => {
                    if (tooltip.style.display === "block" && currentTipTarget) {
                        positionTooltip(currentTipTarget);
                    }
                };
                articleBody.addEventListener("scroll", onScrollHandler);
            }
        };

        // Build content helper
        const renderItems = (arr) => {
            if (!arr || !arr.length)
                return "<div>Terdapat pelanggaran pada kalimat ini.</div>";
            return arr
                .map((e) => {
                    const basis = e.legal_basis
                        ? `<div style="color:#2d5fbf;margin-top:2px">Dasar: ${htmlEscape(
                              e.legal_basis
                          )}</div>`
                        : "";
                    const title = e.violation_title
                        ? htmlEscape(e.violation_title)
                        : "Pelanggaran";
                    const desc = e.description ? htmlEscape(e.description) : "";
                    return `<div class="v-item" style="margin-bottom:6px"><strong>${title}</strong><div>${desc}</div>${basis}</div>`;
                })
                .join("");
        };

        // Cleanup previous marks
        articleBody.querySelectorAll(".sentence").forEach((el) => {
            el.classList.remove(
                "violation-highlight",
                "correct-user",
                "missed-user"
            );
        });

        // Mark and attach events
        allViolations.forEach((idx) => {
            const node = articleBody.querySelector(
                `.sentence[data-index="${idx}"]`
            );
            if (!node) return;
            node.classList.add("violation-highlight");
            if (correctSet.has(idx)) node.classList.add("correct-user");
            else node.classList.add("missed-user");
            const items = vmap[String(idx)] || vmap[idx] || [];
            const html = renderItems(items);
            if (isTouch) {
                node.addEventListener("click", (e) => {
                    e.stopPropagation();
                    if (tooltip.style.display === "block") hide();
                    else showAt(node, html);
                });
            } else {
                node.addEventListener("mouseenter", () => showAt(node, html));
                node.addEventListener("mouseleave", hide);
            }
        });

        // Global hide on outside click
        document.addEventListener(
            "click",
            (e) => {
                const within = tooltip.contains(e.target);
                const onSentence = !!(
                    e.target &&
                    e.target.closest &&
                    e.target.closest(".sentence")
                );
                if (!within && !onSentence) hide();
            },
            { once: true }
        );
    }

    async function startStage2() {
        try {
            nextBtn.disabled = true;
            nextBtn.textContent = "Memuat...";
            console.log(
                "[SIKATA] Fetching corrections for case",
                currentCase?.id
            );
            const res = await fetch("/api/sikata/corrections", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ case_id: currentCase.id }),
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.error || "Gagal memuat koreksi");
            correctionsData = data.corrections || [];
            selectedCorrections = new Map();
            currentCorrectionPage = 0;
            if (correctionsData.length === 0) {
                nextBtn.textContent = "Tidak Ada Koreksi";
                currentMode = "done";
                console.log("[SIKATA] No corrections data received");
                return;
            }
            // Enter Stage 2 mode before rendering so button label updates immediately
            currentMode = "stage2-page";
            renderCorrectionPage();
            updateStage2Button();
            if (backBtn) {
                backBtn.style.display = "inline-block";
            }
            updateBackButtonForStage2();
            console.log(
                "[SIKATA] Stage2 started, total pages=",
                correctionsData.length
            );
        } catch (e) {
            console.error(e);
            if (explanationEl) {
                explanationEl.style.display = "block";
                explanationEl.innerHTML =
                    '<span style="color:#b00020">Gagal memuat opsi koreksi.</span>';
            }
            // Keep trigger label consistent on failure retry
            nextBtn.textContent = "Redaktur Pintar";
            currentMode = "start-stage2";
            console.log("[SIKATA] startStage2 failed", e);
        }
    }

    function renderCorrectionPage() {
        if (!correctionsData.length) return;
        const grp = correctionsData[currentCorrectionPage];
        if (questionText) {
            questionText.innerHTML = `
              <div style="font-weight:800;color:#2d5fbf;margin-bottom:4px">REDAKTUR PINTAR</div>
              <div style="font-size:13px;color:#1b4a7a;margin-bottom:10px">Koreksi kalimat satu per satu. Pilih versi revisi yang paling etis dan akurat.</div>
              <div style="font-weight:600;color:#1b4a7a;margin-bottom:6px">Kalimat ${
                  grp.index
              }</div>
              <div style="font-style:italic;color:#3c5e96;background:#f5f9ff;border:1px solid #dfeaff;border-radius:8px;padding:6px 8px;margin-bottom:10px">“${htmlEscape(
                  grp.original || ""
              )}”</div>
              <div style="font-size:12px;color:#2d5fbf;margin-bottom:6px">${
                  currentCorrectionPage + 1
              } / ${correctionsData.length}</div>
            `;
        }
        if (optionsEl) optionsEl.innerHTML = "";
        const abc = ["A", "B", "C", "D"]; // support up to 4
        const list = document.createElement("div");
        list.style.display = "flex";
        list.style.flexDirection = "column";
        list.style.gap = "6px";
        grp.options.forEach((opt, idx) => {
            const id = `corr-${grp.index}-${opt.id}`;
            const row = document.createElement("label");
            row.setAttribute("for", id);
            row.style.display = "flex";
            row.style.alignItems = "flex-start";
            row.style.gap = "8px";
            row.className = "opt";
            const input = document.createElement("input");
            input.type = "radio";
            input.name = `corr-${grp.index}`;
            input.id = id;
            input.value = String(opt.id);
            input.checked = selectedCorrections.get(grp.index) === opt.id;
            input.addEventListener("change", () => {
                selectedCorrections.set(grp.index, opt.id);
                validateCurrentCorrection();
            });
            const label = document.createElement("div");
            label.innerHTML = `<strong>${abc[idx] || ""}.</strong> ${htmlEscape(
                opt.text
            )}`;
            row.appendChild(input);
            row.appendChild(label);
            list.appendChild(row);
        });
        optionsEl.appendChild(list);
        if (explanationEl) {
            explanationEl.style.display = "none";
            explanationEl.innerHTML = "";
        }
        validateCurrentCorrection();
        updateBackButtonForStage2();
        setInfo("stage2Page", { index: grp.index, page: currentCorrectionPage + 1, total: correctionsData.length });
    }

    function validateCurrentCorrection() {
        if (!nextBtn) return;
        const grp = correctionsData[currentCorrectionPage];
        const answered = selectedCorrections.has(grp.index);
        nextBtn.disabled = !answered;
        updateStage2Button();
    }

    function updateStage2Button() {
        if (!nextBtn) return;
        if (currentMode !== "stage2-page") return;
        if (currentCorrectionPage < correctionsData.length - 1) {
            nextBtn.textContent = "Next";
        } else {
            nextBtn.textContent = "Submit";
        }
        if (quizHeaderEl) quizHeaderEl.style.display = "none"; // hide during Stage 2 pages
    }

    function updateBackButtonForStage2() {
        if (!backBtn) return;
        if (currentMode !== "stage2-page") return;
        // On first question: show "Kembali ke Sorot Berita"; on others: "Back"
        backBtn.textContent =
            currentCorrectionPage === 0 ? "Kembali ke Sorot Berita" : "Back";
    }

    function goBackToSorotBerita() {
        if (!currentCase || !lastIdentifyResult) return;
        // Re-render Stage 1 article and explanations using the stored result
        if (optionsEl) optionsEl.innerHTML = "";
        renderSentences();
        renderIdentifyResult(lastIdentifyResult);
    }

    function goNextCorrectionPage() {
        if (currentCorrectionPage < correctionsData.length - 1) {
            currentCorrectionPage++;
            renderCorrectionPage();
        } else {
            submitCorrections();
        }
    }

    async function submitCorrections() {
        try {
            nextBtn.disabled = true;
            nextBtn.textContent = "Menilai...";
            const answers = Array.from(selectedCorrections.entries()).map(
                ([idx, cid]) => ({ index: idx, correction_id: cid })
            );
            const res = await fetch("/api/sikata/correct", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ case_id: currentCase.id, answers }),
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.error || "Gagal menilai koreksi");
            finalResult = data;
            renderCorrectionsResult(data);
            currentMode = "done-summary";
            // nextBtn text updated in summary renderer
        } catch (e) {
            console.error(e);
            updateStage2Button();
            nextBtn.disabled = false;
        }
    }

    function renderCorrectionsResult(result) {
        // Full summary page after submission
        if (questionText) {
            questionText.innerHTML = `<div style="font-weight:800;color:#2d5fbf;margin-bottom:6px">RANGKUMAN KOREKSI</div><div style="font-size:13px;color:#1b4a7a;margin-bottom:10px">Semua hasil pilihanmu, jawaban benar, serta penjelasan.</div>`;
        }
        if (optionsEl) optionsEl.innerHTML = "";
        const byIndex = new Map();
        (result.results || []).forEach((r) => byIndex.set(r.index, r));
        correctionsData.forEach((grp) => {
            const r = byIndex.get(grp.index);
            const panel = document.createElement("div");
            panel.className = "panel";
            panel.style.marginBottom = "12px";
            const chosenText = r?.chosen?.text || "(Tidak dipilih)";
            const isCorrect = r?.chosen?.is_correct;
            const correctText = r?.correct?.text || "";
            const rationale = r?.chosen?.rationale || "";
            const correctRationale = r?.correct?.rationale || "";
            panel.innerHTML = `
              <div style="font-weight:700;color:#1b4a7a;margin-bottom:4px">Kalimat ${
                  grp.index
              }</div>
              <div style="font-style:italic;color:#3c5e96;background:#f5f9ff;border:1px solid #dfeaff;border-radius:8px;padding:6px 8px;margin-bottom:6px">“${htmlEscape(
                  grp.original || ""
              )}”</div>
              <div style="margin-bottom:6px"><strong>Pilihanmu:</strong> <span style="color:${
                  isCorrect ? "#2fbf7a" : "#ff6b6b"
              }">${htmlEscape(chosenText)}</span></div>
              <div style="margin-bottom:6px"><strong>Jawaban Benar:</strong> ${htmlEscape(
                  correctText
              )}
              <div style="font-size:12px;color:#1b4a7a"><strong>Penjelasan Jawaban Benar:</strong> ${htmlEscape(
                  correctRationale
              )}</div>
            `;
            optionsEl.appendChild(panel);
        });
        // No scoring here; moved to Stage 3
        if (nextBtn) {
            nextBtn.textContent = "Lihat Skor & Berita Final";
            nextBtn.disabled = false;
        }
        if (backBtn) {
            backBtn.style.display = "inline-block";
            backBtn.textContent = "Kembali ke Topik";
        }
        // Defer score update to Stage 3
        setInfo("summary");
    }

    function renderFinalArticlePage() {
        if (!finalResult) return;
        if (questionText) {
            questionText.innerHTML = `<div style="font-weight:800;color:#2d5fbf;margin-bottom:6px">BERITA FINAL YANG BENAR</div><div style="font-size:13px;color:#1b4a7a;margin-bottom:10px">Referensi naskah berita yang benar untuk topik ini.</div>`;
        }
        if (optionsEl) optionsEl.innerHTML = "";
        if (explanationEl) {
            const finalTitle = finalResult.final_title
                ? htmlEscape(finalResult.final_title)
                : "";
            const finalArticleRaw = finalResult.final_article || "";
            // Normalize both actual newlines and escaped \n sequences to <br>
            const finalArticle = htmlEscape(finalArticleRaw)
                .replace(/\\r\\n/g, "<br>")
                .replace(/\\n/g, "<br>")
                .replace(/\r?\n/g, "<br>");
            const total =
                (identifyScore || 0) + (finalResult.score_correct || 0);
                        const maxTotal = finalResult.max_score_total || 100;
                        const pct = Math.max(0, Math.min(100, Math.round((total / maxTotal) * 100)));
                        const radius = 52;
                        const circumference = 2 * Math.PI * radius;
                        const dashoffset = circumference * (1 - pct / 100);
                        explanationEl.style.display = "block";
                        explanationEl.innerHTML = `
                                                        <div style="display:flex;justify-content:center;margin-bottom:10px">
                                                            <div style="position:relative;width:140px;height:140px">
                                                                <svg viewBox="0 0 120 120" style="width:100%;height:100%;transform:rotate(-90deg)" role="img" aria-label="Skor ${pct}%">
                                                                    <circle cx="60" cy="60" r="${radius}" stroke="#eef2f6" stroke-width="14" fill="none" />
                                                                    <circle cx="60" cy="60" r="${radius}" stroke="#2fbf7a" stroke-width="14" fill="none" stroke-linecap="round" stroke-dasharray="${circumference}" stroke-dashoffset="${dashoffset}" />
                                                                </svg>
                                                                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:28px;color:#2fbf7a">${total}</div>
                                                            </div>
                                                        </div>
                                                        <div style="font-weight:700;color:#2d5fbf;margin:-2px 0 10px;text-align:center">Total Skor • ${total}</div>
                                                        <div style="margin-top:4px;padding:14px;border:2px solid #d6e6ff;background:#fafcff;border-radius:12px">
                                                                <div style="font-weight:800;color:#1b4a7a;margin-bottom:4px">Berita Final yang Benar</div>
                                                                <div style="font-weight:600;color:#2d5fbf;margin-bottom:6px">${finalTitle}</div>
                                                                <div style="line-height:1.55;font-size:14px;color:#1b4a7a">${finalArticle}</div>
                                                        </div>`;
            if (scoreEl) scoreEl.textContent = String(total);
        }
        if (nextBtn) {
            nextBtn.textContent = "Selesai";
            nextBtn.disabled = false;
        }
        if (backBtn) backBtn.style.display = "none"; // hide back on final page
        if (quizHeaderEl) quizHeaderEl.style.display = "none"; // hide header Stage 3
        currentMode = "done-final";
        const total = (identifyScore || 0) + (finalResult?.score_correct || 0);
        setInfo("final", { total });
    }

    async function confirmExitToTopics() {
        if (window.Swal && typeof window.Swal.fire === "function") {
            const res = await window.Swal.fire({
                title: "Keluar ke Topik?",
                text: "Progres sesi ini akan ditutup. Yakin?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, keluar",
                cancelButtonText: "Batal",
            });
            return !!res.isConfirmed;
        }
        return window.confirm(
            "Keluar ke Topik? Progres sesi ini akan ditutup."
        );
    }

    function resetToTopics() {
        // Reset state to topics
        currentTopic = null;
        currentCase = null;
        selectedIndexes.clear();
        correctionsData = [];
        selectedCorrections.clear();
        currentCorrectionPage = 0;
        finalResult = null;
        lastIdentifyResult = null;
        if (quizEl) quizEl.style.display = "none";
        if (topicChosenEl) {
            topicChosenEl.style.display = "none";
            topicChosenEl.textContent = "";
        }
        if (topicsEl) {
            topicsEl.style.display = "flex";
        }
        if (quizHeaderEl) quizHeaderEl.style.display = "flex"; // restore header for new session
        if (topicTitleEl) topicTitleEl.style.display = "block"; // show 'Pilih Topik'
        document
            .querySelectorAll(".playing-card")
            .forEach((n) => n.classList.remove("flipped"));
        currentMode = "identify";
        loadTopics();
        setInfo("select");
    }

    if (backBtn) {
        backBtn.addEventListener("click", async () => {
            if (currentMode === "stage2-page") {
                if (currentCorrectionPage > 0) {
                    currentCorrectionPage--;
                    renderCorrectionPage();
                    validateCurrentCorrection();
                    return;
                } else {
                    // On first Stage 2 question: go back to Sorot Berita (Stage 1 summary)
                    goBackToSorotBerita();
                    setInfo("identifyResult");
                    return;
                }
            }
            // At Stage 2 summary, confirm before leaving to topics
            if (currentMode === "done-summary") {
                const ok = await confirmExitToTopics();
                if (!ok) return;
                resetToTopics();
                return;
            }
            // In Sorot Berita session: confirm before leaving to topics
            if (currentMode === "identify" || currentMode === "start-stage2") {
                const ok = await confirmExitToTopics();
                if (!ok) return;
                resetToTopics();
                return;
            }
            // Other modes fallback
            resetToTopics();
        });
    }

    // ——— Info panel helper ———
    function setInfo(state, data = {}) {
        if (!infoEl) return;
        let html = "";
        switch (state) {
            case "select":
                html =
                    "Pilih salah satu kartu topik di sebelah kanan. Arahkan kursor untuk melihat judul topik, lalu klik untuk memulai.";
                break;
            case "topic":
                html = `Topik dipilih: <strong>${(data.title || "").replace(/</g,'&lt;')}</strong>. Sorot kalimat yang melanggar etika, bisa memilih lebih dari satu, lalu klik Submit.`;
                break;
            case "identify":
                html =
                    "Sesi Sorot Berita: tandai kalimat yang bermasalah (multi-pilih), kemudian klik Submit untuk dinilai.";
                break;
            case "identifyResult":
                html =
                    "Ringkasan Sorot Berita: tinjau penjelasan di kanan. Lanjutkan ke Redaktur Pintar untuk memilih revisi kalimat terbaik.";
                break;
            case "stage2Page":
                html = `Redaktur Pintar: pilih versi revisi terbaik untuk Kalimat ${data.index}. Halaman ${data.page}/${data.total}.`;
                break;
            case "summary":
                html =
                    "Rangkuman Koreksi: lihat pilihanmu dan jawaban benar. Klik ‘Lihat Skor & Berita Final’.";
                break;
            case "final":
                html = `Berita Final: baca naskah berita yang benar. Total skor: <strong>${data.total ?? ''}</strong>. Klik Selesai untuk menutup sesi.`;
                break;
            default:
                html = infoEl.textContent || "Pilih topik untuk memulai.";
        }
        infoEl.innerHTML = html;
    }

    // Init
    loadTopics();
});
