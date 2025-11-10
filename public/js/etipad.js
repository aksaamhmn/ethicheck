(function () {
    document.addEventListener("DOMContentLoaded", function () {
        const btnPasal = document.getElementById("btnPasal");
        const btnBerita = document.getElementById("btnBerita");
        const pasalSection = document.getElementById("etipadApp");
        const newsSection = document.getElementById("newsWrapper");
        let newsLoaded = false;
        let NEWS_DOCS = [];
        if (btnPasal)
            btnPasal.addEventListener("click", () => {
                btnPasal.classList.add("active");
                if (btnBerita) btnBerita.classList.remove("active");
                if (pasalSection) pasalSection.style.display = "flex";
                if (newsSection) newsSection.style.display = "none";
            });
        if (btnBerita)
            btnBerita.addEventListener("click", () => {
                btnBerita.classList.add("active");
                if (btnPasal) btnPasal.classList.remove("active");
                if (pasalSection) pasalSection.style.display = "none";
                if (newsSection) newsSection.style.display = "block";
                if (!newsLoaded) {
                    loadNews();
                    newsLoaded = true;
                }
            });

        async function loadDocs() {
            try {
                const res = await fetch("/api/etipad/docs");
                const data = await res.json();
                const listEl = document.getElementById("docList");
                if (!data.documents || !Array.isArray(data.documents)) {
                    if (listEl)
                        listEl.innerHTML =
                            '<div style="color:#b00020">Gagal memuat dokumen.</div>';
                    return;
                }
                if (!listEl) return;
                listEl.innerHTML = "";
                // Exclude Pasal items from the left list; those live under the Berita tab now
                const filtered = data.documents.filter(
                    (d) =>
                        !(
                            typeof d.slug === "string" &&
                            d.slug.startsWith("pasal-")
                        )
                );
                filtered.forEach((doc) => {
                    const btn = document.createElement("button");
                    btn.type = "button";
                    btn.textContent = doc.title;
                    btn.className = "doc-btn";
                    btn.addEventListener("click", () => openDoc(doc.slug, btn));
                    listEl.appendChild(btn);
                });
            } catch (e) {
                const el = document.getElementById("docList");
                if (el)
                    el.innerHTML =
                        '<div style="color:#b00020">Error koneksi.</div>';
            }
        }

        async function openDoc(slug, btn) {
            const viewer = document.getElementById("docViewer");
            if (viewer)
                viewer.innerHTML =
                    '<div style="display:flex;align-items:center;gap:8px"><div class="loading-spinner" style="width:22px;height:22px"></div><span>Memuat...</span></div>';
            try {
                const res = await fetch(
                    "/api/etipad/docs/" + encodeURIComponent(slug)
                );
                const data = await res.json();
                if (!res.ok) throw new Error(data.error || "Gagal");
                const doc = data.document || {};
                if (viewer) {
                    if (
                        doc.file_url &&
                        (doc.mime_type === "application/pdf" ||
                            /\.pdf($|\?)/i.test(doc.file_url))
                    ) {
                        viewer.innerHTML =
                            '<h2 style="margin:0 0 12px;font-size:20px;color:#2d5fbf">' +
                            doc.title +
                            "</h2>" +
                            '<div style="border:1px solid #d0e2ff;border-radius:8px;overflow:hidden">' +
                            '<iframe src="' +
                            doc.file_url +
                            '#view=FitH" style="width:100%;height:70vh;border:0" title="' +
                            doc.title +
                            '"></iframe>' +
                            "</div>";
                    } else {
                        const raw = doc.content || "";
                        const safe = raw.replace(
                            /[&<>]/g,
                            (c) =>
                                ({ "&": "&amp;", "<": "&lt;", ">": "&gt;" }[c])
                        );
                        const html = safe
                            .replace(/\r\n/g, "<br>")
                            .replace(/\n/g, "<br>")
                            .replace(/\r?\n/g, "<br>");
                        viewer.innerHTML =
                            '<h2 style="margin:0 0 12px;font-size:20px;color:#2d5fbf">' +
                            doc.title +
                            '</h2><div style="font-size:14px">' +
                            html +
                            "</div>";
                    }
                }
            } catch (e) {
                if (viewer)
                    viewer.innerHTML =
                        '<span style="color:#b00020">Tidak dapat memuat isi dokumen.</span>';
            }
            document
                .querySelectorAll("#docList button")
                .forEach((b) => b.classList.remove("active"));
            if (btn) btn.classList.add("active");
        }

        loadDocs();

        async function loadNews() {
            const gridEl = document.getElementById("newsGrid");
            if (!gridEl) return;
            gridEl.innerHTML =
                '<div class="loading-news">Memuat berita...</div>';
            try {
                const res = await fetch("/api/etipad/news");
                const data = await res.json();
                NEWS_DOCS = data && Array.isArray(data.news) ? data.news : [];
                gridEl.innerHTML = "";
                if (NEWS_DOCS.length === 0) {
                    gridEl.innerHTML =
                        '<div style="color:#5b7fb5">Belum ada data.</div>';
                    return;
                }
                NEWS_DOCS.forEach((d, idx) => {
                    const div = document.createElement("div");
                    div.className = "story-card";
                    div.setAttribute("role", "button");
                    div.setAttribute("tabindex", "0");
                    const summary = d.summary || "";
                    // Keep structural placeholder for thumb area (empty) to preserve layout spacing
                    div.innerHTML =
                        '\n          <div class="story-thumb"></div>\n          <div class="story-body">\n            <h3>' +
                        d.title +
                        "</h3>\n            <p>" +
                        summary +
                        "</p>\n          </div>";
                    div.addEventListener("click", () => openNews(idx));
                    div.addEventListener("keypress", (e) => {
                        if (e.key === "Enter" || e.key === " ") {
                            e.preventDefault();
                            openNews(idx);
                        }
                    });
                    gridEl.appendChild(div);
                });
            } catch (e) {
                gridEl.innerHTML =
                    '<div style="color:#b00020">Gagal memuat data.</div>';
            }
        }

        const newsGrid = document.getElementById("newsGrid");
        const newsDetail = document.getElementById("newsDetail");
        const btnNewsBack = document.getElementById("btnNewsBack");
        if (btnNewsBack) btnNewsBack.addEventListener("click", backToGrid);

        async function openNews(index) {
            const item = NEWS_DOCS[index];
            if (!item) return;
            const titleEl = document.getElementById("newsTitle");
            const metaEl = document.getElementById("newsMeta");
            const contentEl = document.getElementById("newsContent");
            if (titleEl) titleEl.textContent = item.title || "";
            if (metaEl) metaEl.innerHTML = "&nbsp;"; // preserve vertical spacing while visually empty
            if (contentEl) {
                contentEl.innerHTML = "";
                try {
                    const res = await fetch(
                        "/api/etipad/news/" + encodeURIComponent(item.slug)
                    );
                    const data = await res.json();
                    if (res.ok && data && data.news) {
                        const raw = data.news.content || item.summary || "";
                        const safe = raw.replace(
                            /[&<>]/g,
                            (c) =>
                                ({ "&": "&amp;", "<": "&lt;", ">": "&gt;" }[c])
                        );
                        safe.split(/\n\n+/).forEach((p) => {
                            const para = document.createElement("p");
                            para.innerHTML = p.replace(/\n/g, "<br>");
                            contentEl.appendChild(para);
                        });
                    } else {
                        contentEl.textContent =
                            item.summary || "Tidak ada konten";
                    }
                } catch (e) {
                    contentEl.textContent = item.summary || "Tidak ada konten";
                }
            }
            if (newsGrid) newsGrid.style.display = "none";
            if (newsDetail) newsDetail.style.display = "block";
        }

        function backToGrid() {
            if (newsDetail) newsDetail.style.display = "none";
            if (newsGrid) newsGrid.style.display = "grid";
        }
    });
})();
