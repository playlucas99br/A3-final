document.addEventListener('DOMContentLoaded', () => {
    const infoButtons = document.querySelectorAll('.info-button');
    const modal = document.getElementById('modal');
    const closeButton = document.querySelector('.close-button');

    infoButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Obtém as informações do botão
            const name = button.getAttribute('data-name');
            const age = button.getAttribute('data-age');
            const course = button.getAttribute('data-course');
            const semester = button.getAttribute('data-semester');
            const quote = button.getAttribute('data-quote');
            const imageSrc = button.parentElement.querySelector('img').src; // Obtém a imagem

            // Atualiza o modal com as informações
            document.getElementById('modal-name').innerText = name;
            document.getElementById('modal-age').innerText = `Idade: ${age}`;
            document.getElementById('modal-course').innerText = `Curso: ${course}`;
            document.getElementById('modal-semester').innerText = `Semestre: ${semester}`;
            document.getElementById('modal-quote').innerText = quote;
            document.getElementById('modal-image').src = imageSrc;

            // Mostra o modal
            modal.style.display = 'block';
        });
    });

    // Fecha o modal ao clicar no "x"
    closeButton.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Fecha o modal ao clicar fora dele
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});
