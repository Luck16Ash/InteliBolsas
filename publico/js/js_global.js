/* ======= JS GLOBAL - InteliBolsas =======
   Funcionalidades gerais para todas as páginas
============================================= */

document.addEventListener("DOMContentLoaded", () => {

  // ============================
  // EFEITO LUMINÁRIA
  // ============================
  const luminariaElements = document.querySelectorAll(
    ".form-control, .btn, .btn-primary, .btn-outline-primary, .Caixa, .luminaria"
  );

  luminariaElements.forEach(el => {
    el.addEventListener("mouseenter", () => {
      el.style.boxShadow = "0 0 15px rgba(52, 152, 219, 0.7)";
      el.style.transition = "0.3s";
    });
    el.addEventListener("mouseleave", () => {
      el.style.boxShadow = "none";
    });
  });

  // ============================
  // EFEITO DE FOCO NOS INPUTS
  // ============================
  const inputs = document.querySelectorAll(".form-control");
  inputs.forEach(input => {
    input.addEventListener("focus", () => {
      input.style.boxShadow = "0 0 10px rgba(52, 152, 219, 0.6)";
    });
    input.addEventListener("blur", () => {
      input.style.boxShadow = "none";
    });
  });

  // ============================
  // ANIMAÇÃO DE CLIQUE NOS BOTÕES
  // ============================
  const buttons = document.querySelectorAll(".btn-primary, .btn-outline-primary, .btn");
  buttons.forEach(btn => {
    btn.addEventListener("mousedown", () => btn.style.transform = "scale(0.95)");
    btn.addEventListener("mouseup", () => btn.style.transform = "scale(1)");
    btn.addEventListener("mouseleave", () => btn.style.transform = "scale(1)");
  });

  // ============================
  // VALIDAÇÃO SIMPLES DO FORMULÁRIO DE BUSCA
  // ============================
  const formBusca = document.getElementById("formBusca");
  if (formBusca) {
    formBusca.addEventListener("submit", (e) => {
      e.preventDefault();
      const termo = document.getElementById("inputBusca").value.trim();
      if (termo === "") {
        alert("Por favor, digite o curso ou bolsa que deseja buscar.");
      } else {
        // Substitua alert por ação real, ex: redirecionar ou buscar via AJAX
        alert("Busca realizada por: " + termo);
      }
    });
  }

  // ============================
  // SCROLL SUAVE PARA LINKS INTERNOS
  // ============================
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
  // EFEITO MASCOTE (HOME)
  // ============================
  const mascote = document.querySelector('.mascote');
  if (mascote) {
    mascote.addEventListener('mouseenter', () => mascote.style.transform = 'scale(1.1)');
    mascote.addEventListener('mouseleave', () => mascote.style.transform = 'scale(1)');
  }

});
