/* ======= JS CADASTRO - InteliBolsas =======
   Este script é exclusivo da página de cadastro.
   Ele mostra ou esconde os campos do formulário
   conforme o tipo de cadastro (Aluno ou Instituição).
   ============================================ */

document.addEventListener("DOMContentLoaded", () => {

  // Pega o campo select "tipo"
  const tipoSelect = document.getElementById("tipo");

  // Pega os blocos que devem aparecer ou sumir
  const campoAluno = document.getElementById("campoAluno");
  const campoInstituicao = document.getElementById("campoInstituicao");

  // Quando o tipo mudar, atualiza os campos visíveis
  if (tipoSelect) {
    tipoSelect.addEventListener("change", () => {
      const tipo = tipoSelect.value;

      // Se for aluno, mostra o campo de área de interesse
      if (tipo === "aluno") {
        campoAluno.style.display = "block";
        campoInstituicao.style.display = "none";
      }

      // Se for instituição, mostra o campo de endereço
      if (tipo === "instituicao") {
        campoAluno.style.display = "none";
        campoInstituicao.style.display = "block";
      }
    });
  }

  // ===========================
  // Validação simples do formulário
  // ===========================
  const formCadastro = document.getElementById("formCadastro");
  if (formCadastro) {
    formCadastro.addEventListener("submit", (e) => {
      const nome = document.getElementById("nome").value.trim();
      const email = document.getElementById("email").value.trim();
      const senha = document.getElementById("senha").value.trim();

      // Verifica campos básicos
      if (nome === "" || email === "" || senha === "") {
        e.preventDefault(); // não envia o formulário
        alert("Por favor, preencha todos os campos obrigatórios.");
      } else if (senha.length < 6) {
        e.preventDefault();
        alert("A senha precisa ter pelo menos 6 caracteres.");
      }
    });
  }

});
