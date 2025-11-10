(function(){
  document.addEventListener('DOMContentLoaded', function(){
    const btnPasal = document.getElementById('btnPasal');
    const btnBerita = document.getElementById('btnBerita');
    const pasalSection = document.getElementById('etipadApp');
    const newsSection = document.getElementById('newsWrapper');
    let newsLoaded = false;
    if(btnPasal) btnPasal.addEventListener('click', ()=>{
      btnPasal.classList.add('active'); if(btnBerita) btnBerita.classList.remove('active');
      if(pasalSection) pasalSection.style.display = 'flex'; if(newsSection) newsSection.style.display='none';
    });
    if(btnBerita) btnBerita.addEventListener('click', ()=>{
      btnBerita.classList.add('active'); if(btnPasal) btnPasal.classList.remove('active');
      if(pasalSection) pasalSection.style.display = 'none'; if(newsSection) newsSection.style.display='block';
      if(!newsLoaded){ loadNews(); newsLoaded = true; }
    });

    async function loadDocs(){
      try{
        const res = await fetch('/api/etipad/docs');
        const data = await res.json();
        const listEl = document.getElementById('docList');
        if(!data.documents || !Array.isArray(data.documents)){
          if(listEl) listEl.innerHTML = '<div style="color:#b00020">Gagal memuat dokumen.</div>';
          return;
        }
        if(!listEl) return;
        listEl.innerHTML = '';
        data.documents.forEach(doc=>{
          const btn = document.createElement('button');
          btn.type='button'; btn.textContent=doc.title; btn.className='doc-btn';
          btn.addEventListener('click', ()=> openDoc(doc.slug, btn));
          listEl.appendChild(btn);
        });
      }catch(e){
        const el = document.getElementById('docList');
        if(el) el.innerHTML = '<div style="color:#b00020">Error koneksi.</div>';
      }
    }

    async function openDoc(slug, btn){
      const viewer = document.getElementById('docViewer');
      if(viewer) viewer.innerHTML = '<div style="display:flex;align-items:center;gap:8px"><div class="loading-spinner" style="width:22px;height:22px"></div><span>Memuat...</span></div>';
      try{
        const res = await fetch('/api/etipad/docs/'+encodeURIComponent(slug));
        const data = await res.json();
        if(!res.ok) throw new Error(data.error || 'Gagal');
        const doc = data.document || {};
        if(viewer){
          if(doc.file_url && (doc.mime_type==='application/pdf' || /\.pdf($|\?)/i.test(doc.file_url))){
            viewer.innerHTML = '<h2 style="margin:0 0 12px;font-size:20px;color:#2d5fbf">'+doc.title+'</h2>'+
              '<div style="border:1px solid #d0e2ff;border-radius:8px;overflow:hidden">'+
              '<iframe src="'+doc.file_url+'#view=FitH" style="width:100%;height:70vh;border:0" title="'+doc.title+'"></iframe>'+
              '</div>';
          }else{
            const raw = doc.content || '';
            const safe = raw.replace(/[&<>]/g, c=>({ '&':'&amp;','<':'&lt;','>':'&gt;' }[c]));
            const html = safe.replace(/\r\n/g,'<br>').replace(/\n/g,'<br>').replace(/\r?\n/g,'<br>');
            viewer.innerHTML = '<h2 style="margin:0 0 12px;font-size:20px;color:#2d5fbf">'+doc.title+'</h2><div style="font-size:14px">'+html+'</div>';
          }
        }
      }catch(e){ if(viewer) viewer.innerHTML = '<span style="color:#b00020">Tidak dapat memuat isi dokumen.</span>'; }
      document.querySelectorAll('#docList button').forEach(b=>b.classList.remove('active'));
      if(btn) btn.classList.add('active');
    }

    loadDocs();

    const NEWS_ITEMS = [
      { title:'Judul Utama Berita Contoh Hari Ini', tag:'Nasional', date:'12 Nov 2025', author:'Redaksi Etipad', summary:'Ringkasan singkat berita utama yang menarik dan menonjolkan inti informasi untuk pembaca.', content:[
        'Pemerintah hari ini merilis sejumlah langkah strategis untuk meningkatkan kualitas informasi publik.',
        'Selain itu, lembaga independen turut mengawasi pelaksanaan kebijakan agar tetap transparan dan akuntabel.',
        'Para ahli menilai langkah ini dapat meningkatkan kepercayaan masyarakat terhadap media arus utama.'
      ]},
      { title:'Update Kebijakan Media Terbaru', tag:'Regulasi', date:'11 Nov 2025', author:'Redaksi', summary:'Pemerintah umumkan pembaruan verifikasi sumber dan distribusi konten.', content:[
        'Aturan baru mewajibkan platform untuk memperjelas asal-usul materi yang dipublikasikan.',
        'Pihak industri menyambut positif karena dapat menekan peredaran misinformasi.'
      ]},
      { title:'Tren Konsumsi Berita Milenial', tag:'Analisis', date:'10 Nov 2025', author:'Analis', summary:'Peralihan ke platform mobile dengan durasi baca lebih pendek.', content:[
        'Riset terbaru menunjukkan preferensi format ringkas dengan visual kuat.',
        'Media bereksperimen dengan push notification yang lebih personal.'
      ]},
      { title:'Literasi Informasi di Sekolah', tag:'Edukasi', date:'09 Nov 2025', author:'Pendidikan', summary:'Program baru melatih siswa mengenali misinformasi secara interaktif.', content:[
        'Modul interaktif memperkenalkan teknik verifikasi dasar untuk siswa.',
        'Guru dibekali pedoman praktis untuk diskusi kelas berbasis kasus.'
      ]},
      { title:'Teknologi Moderasi Konten AI', tag:'Teknologi', date:'09 Nov 2025', author:'Tekno', summary:'Startup luncurkan sistem moderasi yang diklaim transparan bagi publik.', content:[
        'Fitur audit trail memungkinkan pengguna melacak alasan moderasi.',
        'Organisasi masyarakat sipil diminta memberi masukan terhadap metrik transparansi.'
      ]},
      { title:'Kolaborasi Media Lokal', tag:'Kolaborasi', date:'08 Nov 2025', author:'Daerah', summary:'Sinergi redaksi daerah mempercepat klarifikasi isu viral dan hoaks.', content:[
        'Jejaring wartawan lokal memperkuat akses ke sumber primer di tiap wilayah.',
        'Hasil kolaborasi dirilis secara terbuka untuk lintas redaksi.'
      ]},
      { title:'Pelatihan Verifikasi Fakta', tag:'Pelatihan', date:'07 Nov 2025', author:'Komunitas', summary:'Workshop intensif untuk jurnalis muda tentang verifikasi multimedia.', content:[
        'Materi meliputi OSINT, metadata, dan penelusuran gambar terbalik.',
        'Peserta berlatih pada studi kasus yang menyerupai situasi nyata.'
      ]},
      { title:'Ekonomi Iklan Digital', tag:'Ekonomi', date:'06 Nov 2025', author:'Bisnis', summary:'Analisis pergeseran belanja iklan ke format interaktif dan native.', content:[
        'Brand memprioritaskan ROI yang dapat diukur secara granular.',
        'Publisher menyiapkan paket konten kolaboratif yang lebih transparan.'
      ]},
      { title:'Transparansi Algoritma Portal', tag:'Privasi', date:'05 Nov 2025', author:'Keamanan', summary:'Diskusi publik tentang keterbukaan algoritma rekomendasi berita.', content:[
        'Paksa ujian publik (public audit) diusulkan untuk platform besar.',
        'Akademisi menekankan pentingnya akses data bagi penelitian independen.'
      ]}
    ];

    function loadNews(){
      const gridEl = document.getElementById('newsGrid');
      if(!gridEl) return;
      gridEl.innerHTML = '';
      NEWS_ITEMS.slice(0,9).forEach((item, idx)=>{
        const div = document.createElement('div');
        div.className='story-card';
        div.setAttribute('role','button');
        div.setAttribute('tabindex','0');
        div.innerHTML = '\n          <div class="story-thumb">\n            <span class="tag">'+(item.tag||'')+'</span>\n            <span class="badge">Baca</span>\n          </div>\n          <div class="story-body">\n            <div class="meta">'+(item.date||'')+'</div>\n            <h3>'+item.title+'</h3>\n            <p>'+item.summary+'</p>\n          </div>';
        div.addEventListener('click', ()=> openNews(idx));
        div.addEventListener('keypress', (e)=>{ if(e.key==='Enter'||e.key===' ') { e.preventDefault(); openNews(idx); }});
        gridEl.appendChild(div);
      });
    }

    const newsGrid = document.getElementById('newsGrid');
    const newsDetail = document.getElementById('newsDetail');
    const btnNewsBack = document.getElementById('btnNewsBack');
    if(btnNewsBack) btnNewsBack.addEventListener('click', backToGrid);

    function openNews(index){
      const item = NEWS_ITEMS[index];
      if(!item) return;
      const titleEl = document.getElementById('newsTitle');
      const metaEl = document.getElementById('newsMeta');
      const contentEl = document.getElementById('newsContent');
      if(titleEl) titleEl.textContent = item.title;
      if(metaEl) metaEl.textContent = (item.date||'')+' • '+(item.author||'Redaksi')+' • '+(item.tag||'');
      if(contentEl){
        contentEl.innerHTML = '';
        (item.content||[item.summary]).forEach(p=>{ const para=document.createElement('p'); para.textContent=p; contentEl.appendChild(para); });
      }
      if(newsGrid) newsGrid.style.display='none';
      if(newsDetail) newsDetail.style.display='block';
    }

    function backToGrid(){ if(newsDetail) newsDetail.style.display='none'; if(newsGrid) newsGrid.style.display='grid'; }
  });
})();
