let contador;

function inatividade() {
  // Vai limpar o contador de inatividade
  clearTimeout(contador);
  // Vai iniciar o contador de inatividade
  contador = setTimeout(() => {
    // Vai redirecionar para a página incial depois de 45 seg
    window.location.href = "php/deletar_comanda.php";
  }, 25000);
}

// Se clicar em qualquer lugar ou mexer o mouse no documento, vai reiniciar o contador de inatividade
document.addEventListener("click", inatividade);
document.addEventListener("mousemove", inatividade);

// Contador começar ao carregar a página
inatividade();
