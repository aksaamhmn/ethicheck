(function(){
  document.addEventListener('DOMContentLoaded', function(){
    const analysisForm = document.getElementById('checker-form');
    const textarea = document.getElementById('news-text-input');
    const resultsBox = document.getElementById('results-box');
    const submitButton = document.getElementById('submit-btn');
    if(!analysisForm || !textarea || !resultsBox || !submitButton){ return; }

    const originalButtonText = submitButton.innerHTML;
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : (window.ETHICHECK_CONFIG && window.ETHICHECK_CONFIG.csrf);
    const analyzeUrl = (window.ETHICHECK_CONFIG && window.ETHICHECK_CONFIG.analyzeUrl) || '/ethicheck/analyze';

    analysisForm.addEventListener('submit', async function(e){
      e.preventDefault();
      const textToAnalyze = textarea.value.trim();
      if(textToAnalyze.length < 50){
        resultsBox.innerHTML = '<p style="color: var(--red-border);">Teks berita terlalu pendek untuk dianalisis. Harap masukkan setidaknya 50 karakter.</p>';
        return;
      }
      submitButton.disabled = true;
      submitButton.innerHTML = '<span class="loading-spinner"></span> Menganalisis...';
      resultsBox.innerHTML = '<p>AI sedang menganalisis teks Anda. Mohon tunggu...</p>';
      try{
        const response = await fetch(analyzeUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken || '',
            'Accept': 'application/json'
          },
          body: JSON.stringify({ text: textToAnalyze })
        });
        const data = await response.json();
        if(!response.ok || data.error){ throw new Error(data.error || 'Gagal menganalisis'); }
        renderResults(data);
      }catch(err){
        console.error('Error:', err);
        resultsBox.innerHTML = `<p style="color: var(--red-border);"><strong>Terjadi Kesalahan:</strong> ${err.message}</p>`;
      }finally{
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
      }
    });

    function renderResults(data){
      if(!data){
        resultsBox.innerHTML = '<p style="color: var(--red-border);"><strong>Error:</strong> AI mengembalikan respons kosong atau tidak valid.</p>';
        return;
      }
      resultsBox.innerHTML = '';
      let scoreVal = null;
      if(typeof data.score === 'number'){ scoreVal = data.score; }
      else if(data.score){ const maybe = parseInt(data.score); if(!isNaN(maybe)) scoreVal = maybe; }
      if(scoreVal !== null){
        const scoreWrap = document.createElement('div');
        scoreWrap.style = 'display:flex;align-items:center;gap:12px;margin-bottom:12px;';
        const scoreText = document.createElement('strong');
        scoreText.textContent = `Skor: ${scoreVal}/100`;
        const barOuter = document.createElement('div');
        barOuter.style = 'flex:1;height:12px;background:#e6eefc;border-radius:8px;overflow:hidden;';
        const barInner = document.createElement('div');
        const width = Math.max(0, Math.min(100, scoreVal));
        barInner.style = `height:100%;width:${width}%;background:linear-gradient(90deg,#2e66cc,#2f5aa0)`;
        barOuter.appendChild(barInner);
        scoreWrap.appendChild(scoreText);
        scoreWrap.appendChild(barOuter);
        resultsBox.appendChild(scoreWrap);
      }
      const highlightedContent = document.createElement('div');
      highlightedContent.className = 'highlighted-text';
      if(data.highlighted_text){ highlightedContent.innerHTML = data.highlighted_text.replace(/\n/g,'<br>'); }
      else { highlightedContent.innerHTML = '<p style="color:var(--red-border);">(Peringatan: AI tidak mengembalikan `highlighted_text`.)</p>'; }
      resultsBox.appendChild(highlightedContent);
      if(Array.isArray(data.explanations) && data.explanations.length){
        const explanationsList = document.createElement('ul');
        explanationsList.className = 'explanations-list';
        data.explanations.forEach(item=>{
          const li = document.createElement('li');
          li.innerHTML = `
            <div class="violation-id">${item.id}</div>
            <div class="violation-details">
              <div class="violation-rule">${item.rule}</div>
              <p class="violation-reasoning">${item.reasoning}</p>
            </div>`;
          explanationsList.appendChild(li);
        });
        resultsBox.appendChild(explanationsList);
      }
      if(data.recommended_text){
        const recTitle = document.createElement('h4');
        recTitle.textContent = 'Rekomendasi Berita yang Baik:';
        recTitle.style = 'margin-top:16px;margin-bottom:8px;color:var(--ink);';
        const recDiv = document.createElement('div');
        recDiv.className = 'results-box';
        recDiv.innerHTML = data.recommended_text.replace(/\n/g,'<br>');
        resultsBox.appendChild(recTitle);
        resultsBox.appendChild(recDiv);
      }
      if(data.status_message){
        const statusMsg = document.createElement('p');
        statusMsg.style = 'color: green; font-weight: bold; margin-top: 15px;';
        statusMsg.textContent = `✅ ${data.status_message}`;
        resultsBox.appendChild(statusMsg);
      }
    }
  });
})();
