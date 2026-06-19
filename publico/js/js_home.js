/* ======= JS DA HOME - InteliBolsas =======
   Script exclusivo da página inicial.
   Controla animações, interações e comportamento da Home.
   ============================================ */

document.addEventListener('DOMContentLoaded', () => {

  // Scroll suave para âncoras internas
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  // Redirecionamento do botão de Login
  const btnLogin = document.getElementById('btnLogin');
  if (btnLogin) {
    btnLogin.addEventListener('click', () => {
      window.location.href = 'login.php';
    });
  }

  // Busca simples
  const formBusca = document.getElementById('formBusca');
  const inputBusca = document.getElementById('inputBusca');
  if (formBusca && inputBusca) {
    formBusca.addEventListener('submit', e => {
      e.preventDefault();
      const termo = inputBusca.value.trim();
      if (termo) {
        alert(`Você buscou por: "${termo}". Redirecionando para resultados...`);
      } else {
        alert('Digite um termo de busca válido.');
      }
    });
  }

  // Animação suave do mascote
  const mascote = document.querySelector('.mascote');
  if (mascote) {
    mascote.style.transition = 'transform 0.3s ease-in-out';
    mascote.addEventListener('mouseenter', () => {
      mascote.style.transform = 'scale(1.1)';
    });
    mascote.addEventListener('mouseleave', () => {
      mascote.style.transform = 'scale(1)';
    });
  }

});