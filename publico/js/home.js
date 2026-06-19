/* ======= JS DA HOME - InteliBolsas =======
   Este script é específico para a página home,
   controlando animações, scroll, mascote e geração de cards
   ============================================================ */

// ============================
// EFEITO DE SCROLL SUAVE PARA LINKS INTERNOS
// ============================
// Quando um link começa com "#", faz scroll suave até o alvo
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// ============================
// BOTÃO LOGIN
// ============================
// Redireciona para a página de login ao clicar no botão
const btnLogin = document.getElementById('btnLogin');
if(btnLogin){
    btnLogin.addEventListener('click', function() {
        window.location.href = 'login.html';
    });
}

// ============================
// BUSCA SIMPLES (ALERTA)
// ============================
// Valida o input de busca e exibe alerta
const formBusca = document.getElementById('formBusca');
if(formBusca){
    formBusca.addEventListener('submit', function(e) {
        e.preventDefault();
        const termo = document.getElementById('inputBusca').value.trim();
        if(termo) {
            alert(`Você buscou por: "${termo}"`);
        } else {
            alert('Digite algo para buscar.');
        }
    });
}

// ============================
// ANIMAÇÃO SUAVE DO MASCOTE
// ============================
// Aumenta o mascote ao passar o mouse e retorna ao normal ao sair
const mascote = document.querySelector('.mascote');
if(mascote){
    mascote.addEventListener('mouseenter', () => {
        mascote.style.transform = 'scale(1.1)';
    });
    mascote.addEventListener('mouseleave', () => {
        mascote.style.transform = 'scale(1)';
    });
}
