let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
  e.preventDefault();
  deferredPrompt = e;

  const installBtn = document.createElement('button');
  installBtn.innerText = " Install 1BST";
  installBtn.style.position = 'fixed';
  installBtn.style.bottom = '20px';
  installBtn.style.right = '20px';
  installBtn.style.padding = '10px 15px';
  installBtn.style.background = '#0b5fff';
  installBtn.style.color = '#fff';
  installBtn.style.border = 'none';
  installBtn.style.borderRadius = '6px';
  installBtn.style.cursor = 'pointer';
  installBtn.style.zIndex = '9999';
  document.body.appendChild(installBtn);

  installBtn.addEventListener('click', async () => {
    installBtn.style.display = 'none';
    if (!deferredPrompt) return;
    deferredPrompt.prompt();
    const { outcome } = await deferredPrompt.userChoice;
    console.log(`User response: ${outcome}`);
    deferredPrompt = null;
  });
});
