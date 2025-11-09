document.addEventListener('DOMContentLoaded', () => {
  // Data topik & soal
  const topics = [
    { id:'publikasi', title:'Publikasi', desc:'Klaim hasil & plagiarisme', questions:[
      { text:'Penulis mengklaim hasil yang tidak didukung data. Pilih pernyataan yang salah.', options:['"Data menunjukkan peningkatan signifikan."','"Analisis menunjukkan tren menurun."','"Penulis membuat klaim tanpa bukti."'], correct:2, explanation:'Klaim tanpa bukti melanggar etika publikasi. Laporan harus sesuai data.' },
      { text:'Teks disalin tanpa kutipan. Mana yang bermasalah?', options:['Paragraf deskriptif','Kutipan langsung tanpa referensi','Ringkasan hasil sendiri'], correct:1, explanation:'Plagiarisme terjadi jika teks orang lain digunakan tanpa atribusi yang jelas.' }
    ]},
    { id:'consent', title:'Persetujuan', desc:'Hak & persetujuan partisipan', questions:[
      { text:'Wawancara direkam tanpa izin. Mana tindakan yang salah?', options:['Mendapatkan persetujuan sebelum rekaman','Merekam tanpa sepengetahuan partisipan','Menjaga kerahasiaan data'], correct:1, explanation:'Merekam tanpa izin melanggar hak partisipan.' },
      { text:'Identitas partisipan dibagikan tanpa izin. Mana yang salah?', options:['Menyamarkan identitas','Mendapatkan persetujuan publikasi identitas','Membagikan identitas tanpa izin'], correct:2, explanation:'Membagikan identitas tanpa persetujuan adalah pelanggaran privasi.' }
    ]},
    { id:'data', title:'Manajemen Data', desc:'Integritas & pengelolaan data', questions:[
      { text:'Data mentah dihapus tanpa backup. Pilih yang salah.', options:['Menyimpan backup','Menghapus tanpa izin','Mencatat perubahan'], correct:1, explanation:'Menghapus data mentah tanpa izin mengurangi akuntabilitas.' },
      { text:'Data dimodifikasi untuk menyesuaikan hasil. Mana salah?', options:['Mendokumentasikan perubahan','Mengubah data tanpa catatan','Menjaga integritas data'], correct:1, explanation:'Mengubah data tanpa dokumentasi adalah manipulasi data.' }
    ]}
  ];

  // Element refs
  const topicsEl = document.getElementById('topics');
  const topicChosenEl = document.getElementById('topicChosen');
  const quizEl = document.getElementById('quiz');
  const questionText = document.getElementById('questionText');
  const optionsEl = document.getElementById('options');
  const qnum = document.getElementById('qnum');
  const qtotal = document.getElementById('qtotal');
  const scoreEl = document.getElementById('score');
  const nextBtn = document.getElementById('nextBtn');
  const backBtn = document.getElementById('backBtn');
  const explanationEl = document.getElementById('explanation');

  let currentTopic = null, idx = 0, score = 0, answered = false;

  function renderTopics(){
    if(!topicsEl) return;
    topicsEl.innerHTML='';
    topicsEl.style.display = 'flex';
    // Gunakan kartu bertuliskan S, K, T sesuai palet warna situs
    const suits=[
      {cls:'s',ch:'S',rot:-12,dx:-80,z:3},
      {cls:'k',ch:'K',rot:0,dx:0,z:2},
      {cls:'t',ch:'T',rot:12,dx:80,z:1}
    ];
    topics.forEach((t,i)=>{
      const s=suits[i% suits.length];
      const card=document.createElement('div');
      card.className=`playing-card ${s.cls}`;
      const base=`translateX(${s.dx}px) rotate(${s.rot}deg)`;
      card.style.transform=base; card.dataset.base=base; card.dataset.dx=String(s.dx); card.dataset.rot=String(s.rot); card.style.zIndex=s.z; card.tabIndex=0;
      card.innerHTML = `
        <div class="pc-inner">
          <div class="pc-face front">
            <div class="pc-corner tl">${s.ch}</div>
            <div class="pc-center">${s.ch}</div>
            <div class="pc-corner br">${s.ch}</div>
            <div class="pc-label">${t.title}</div>
          </div>
          <div class="pc-face back">
            <div style="text-align:center">
              <div style="font-weight:700;color:#2d5fbf;margin-bottom:6px">Topik</div>
              <div style="font-size:16px;color:#1b4a7a">${t.title}</div>
            </div>
          </div>
        </div>`;
      card.addEventListener('click',()=>{
        // Flip open card instead of spin
        card.classList.add('flipped');
        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const delay = reduce ? 0 : 750;
        setTimeout(()=>{
          if(topicChosenEl){
            topicChosenEl.textContent = `Topik dipilih: ${t.title}`;
            topicChosenEl.style.display = 'block';
          }
          startTopic(i);
          if(topicsEl) topicsEl.style.display='none';
        }, delay);
      });
      topicsEl.appendChild(card);
    });

    // Tambah efek sebar saat hover/focus (desktop/keyboard)
    setupSpreadInteractions(topicsEl);
  }

  function setupSpreadInteractions(container){
    if(!container) return;
    const isTouch = window.matchMedia('(pointer: coarse)').matches;
    const cards = Array.from(container.querySelectorAll('.playing-card'));
    // Reset helper
    const reset = ()=>{
      cards.forEach(c=>{ c.style.transform = c.dataset.base || ''; c.classList.remove('spread-target'); c.style.zIndex = c.style.zIndex || ''; });
    };
    if(isTouch){ reset(); return; }
    const getSpread = ()=>{
      const v = getComputedStyle(document.documentElement).getPropertyValue('--spread').trim();
      const n = parseFloat(v.replace('px',''));
      return isNaN(n)?120:n;
    };
    const spread = (target)=>{
      const dist = getSpread();
      const targetDx = parseFloat(target.dataset.dx||'0');
      cards.forEach((c,idx)=>{
        const dx = parseFloat(c.dataset.dx||'0');
        const rot = parseFloat(c.dataset.rot||'0');
        let tx = dx;
        if(c!==target){ tx = dx + (dx<targetDx? -dist : dx>targetDx? dist : 0); }
        const isTarget = c===target;
        const ty = isTarget? -8: 0;
        const sc = isTarget? ' var(--hover-scale)' : '';
        c.style.transform = `translateX(${tx}px) rotate(${rot}deg) translateY(${ty}px)` + (isTarget? ` scale(${getComputedStyle(document.documentElement).getPropertyValue('--hover-scale').trim()||'1.04'})`:'');
        c.classList.toggle('spread-target', isTarget);
        if(isTarget) c.style.zIndex = '9';
      });
    };
    // attach events
    cards.forEach(c=>{
      c.addEventListener('mouseenter', ()=>spread(c));
      c.addEventListener('mouseleave', reset);
      c.addEventListener('focusin', ()=>spread(c));
      c.addEventListener('blur', reset);
    });
  }

  function startTopic(i){
    currentTopic = topics[i]; idx = 0; score = 0; updateProgress();
    if(quizEl) quizEl.style.display='block';
    if(backBtn) backBtn.style.display='none';
    if(explanationEl) explanationEl.style.display='none';
    if(nextBtn) nextBtn.disabled = true;
    renderQuestion();
  }

  function renderQuestion(){
    const q = currentTopic.questions[idx];
    if(questionText) questionText.textContent = q.text;
    if(optionsEl) optionsEl.innerHTML='';
    q.options.forEach((opt,i)=>{
      const o=document.createElement('div');
      o.className='opt'; o.textContent=opt;
      o.addEventListener('click',()=>choose(i,o));
      optionsEl.appendChild(o);
    });
    answered=false; if(explanationEl) explanationEl.style.display='none';
    if(nextBtn) nextBtn.disabled=true; updateProgress();
  }

  function choose(i,el){
    if(answered) return; answered=true; const q=currentTopic.questions[idx];
    Array.from(optionsEl.children).forEach((c,j)=>{ c.classList.remove('correct','wrong'); if(j===q.correct) c.classList.add('correct'); });
    if(i===q.correct){ score++; scoreEl.textContent=score; el.classList.add('correct'); } else { el.classList.add('wrong'); }
    if(explanationEl){ explanationEl.style.display='block'; explanationEl.textContent=q.explanation; }
    if(nextBtn){ nextBtn.disabled=false; nextBtn.textContent = idx < currentTopic.questions.length-1 ? 'Lanjut' : 'Selesai'; }
    if(backBtn){ backBtn.style.display='none'; }
  }

  function updateProgress(){ qnum.textContent=idx+1; qtotal.textContent=currentTopic? currentTopic.questions.length:0; scoreEl.textContent=score; }

  if(nextBtn){
    nextBtn.addEventListener('click',()=>{
      if(!currentTopic||!answered) return;
      if(idx<currentTopic.questions.length-1){ idx++; renderQuestion(); }
      else { finishTopic(); }
    });
  }

  if(backBtn){
    backBtn.addEventListener('click',()=>{
      if(quizEl) quizEl.style.display='none';
      if(topicChosenEl){ topicChosenEl.style.display='none'; topicChosenEl.textContent=''; }
      if(topicsEl){ topicsEl.style.display='flex'; }
      document.querySelectorAll('.playing-card').forEach(n=>n.classList.remove('flipped'));
      currentTopic=null; idx=0; score=0; updateProgress();
      renderTopics();
    });
  }

  function finishTopic(){
    if(explanationEl){ explanationEl.style.display='block'; explanationEl.innerHTML = `Selesai! Skor: <strong>${score}</strong> dari ${currentTopic.questions.length}.`; }
    if(nextBtn) nextBtn.disabled=true;
    if(backBtn) backBtn.style.display='inline-block';
  }

  renderTopics();
});
