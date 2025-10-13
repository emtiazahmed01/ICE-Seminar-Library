    (function(){
      const tabs = Array.from(document.querySelectorAll('.tab'));
      const contents = Array.from(document.querySelectorAll('.tab-content'));

      function activateTab(tabEl, setFocus = false){
        const target = tabEl.dataset.tab;
        // deactivate all
        tabs.forEach(t=>{t.classList.remove('active');t.setAttribute('aria-selected','false')});
        contents.forEach(c=>c.classList.remove('active'));

        // activate chosen
        tabEl.classList.add('active');
        tabEl.setAttribute('aria-selected','true');
        const content = document.getElementById(target);
        if(content) content.classList.add('active');

        if(setFocus) tabEl.focus();
        // update hash without scrolling
        history.replaceState(null,'', '#'+target);
      }

      tabs.forEach(tab=>{
        tab.addEventListener('click', ()=> activateTab(tab,true));
        tab.addEventListener('keydown', (e)=>{
          const idx = tabs.indexOf(tab);
          if(e.key === 'ArrowRight') activateTab(tabs[(idx+1)%tabs.length], true);
          if(e.key === 'ArrowLeft') activateTab(tabs[(idx-1+tabs.length)%tabs.length], true);
          if(e.key === 'Enter' || e.key === ' ') activateTab(tab, true);
        });
      });

      // open tab from URL hash on load
      const initialHash = window.location.hash.replace('#','');
      if(initialHash){
        const initTab = tabs.find(t=>t.dataset.tab === initialHash);
        if(initTab) activateTab(initTab, false);
      }
    })();

    // Theme toggle (light / dark)
    function toggleTheme(){
      document.body.classList.toggle('dark');
      const btn = document.querySelector('.toggle-btn');
      if(document.body.classList.contains('dark')){
        btn.textContent = '☀️ Light';
        localStorage.setItem('ice-theme','dark');
      } else {
        btn.textContent = '🌙 Dark';
        localStorage.setItem('ice-theme','light');
      }
    }
    // apply saved theme
    (function(){
      const saved = localStorage.getItem('ice-theme');
      if(saved === 'dark'){
        document.body.classList.add('dark');
        const btn = document.querySelector('.toggle-btn'); if(btn) btn.textContent='☀️ Light';
      }
    })();

    // Back to top button
    const backToTop = document.getElementById('backToTop');
    window.addEventListener('scroll', ()=>{
      if(window.scrollY > 300) backToTop.classList.add('visible'); else backToTop.classList.remove('visible');
    });
    backToTop.addEventListener('click', ()=> window.scrollTo({top:0,behavior:'smooth'}));

    // Contact form behaviour (example: mock submit)
    const contactForm = document.getElementById('contactForm');
    if(contactForm){
      contactForm.addEventListener('submit', (e)=>{
        e.preventDefault();
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const message = document.getElementById('message').value.trim();
        if(!name||!email||!message){ alert('Please fill all fields'); return }
        // Here you would send data via fetch/ajax to your server.
        alert('Message sent — this is a demo. Implement server-side handling to actually send messages.');
        contactForm.reset();
      });
    }