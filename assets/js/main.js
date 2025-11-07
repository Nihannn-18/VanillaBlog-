//Markdown preview toggle and live rendering

(function(){
  const mdArea = document.getElementById('markdown');
  const preview = document.getElementById('preview');
  const toggle = document.getElementById('toggle-preview');
  if (mdArea && preview && toggle) {
    
    //Send Markdown text to backend for rendering
    const render = () => {
      fetch(window.APP_URL + '/includes/markdown_preview.php', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({ text: mdArea.value })
      }).then(r => r.text()).then(html => {
        preview.innerHTML = html;
      }).catch(() => {
        preview.innerHTML = '<p style="color:#ff5c5c">Preview failed.</p>';
      });
    };

    
  // Toggle preview visibility

    toggle.addEventListener('click', (e)=>{
      e.preventDefault();
      const show = preview.style.display !== 'block';
      preview.style.display = show ? 'block' : 'none';
      if (show) render();
    });

    // Update preview as user types
    mdArea.addEventListener('input', ()=>{
      if (preview.style.display === 'block') render();
    });
  }
})();
