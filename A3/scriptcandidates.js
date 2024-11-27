// Variáveis globais
let selectedCandidateId = null;

// Função para abrir o modal de votação
function openVoteModal(candidato, id) {
    selectedCandidateId = id;
    document.getElementById("vote-modal").style.display = "flex";
}

// Função para fechar o modal
function closeVoteModal() {
    document.getElementById("vote-modal").style.display = "none";
}

// Adicionar evento de click nos botões de "votar"
document.querySelectorAll('.vote-btn').forEach(button => {
    button.addEventListener('click', function () {
        selectedCandidateId = this.getAttribute('data-candidate-id');
        document.querySelectorAll('.candidate-card').forEach(card => {
            card.classList.remove('selected');
        });
        this.closest('.candidate-card').classList.add('selected');
        console.log('Candidato escolhido:', selectedCandidateId);
    });
});

// Impede digitar letras no campo RA
document.getElementById("ra").addEventListener("keypress", function (e) {
    if (!/^\d$/.test(e.key)) {
        e.preventDefault();
    }
});

// Remove letras ou caracteres inválidos colados no campo RA
document.getElementById("ra").addEventListener("input", function () {
    this.value = this.value.replace(/[^\d]/g, '');
});

// Impede digitar números no campo Nome
document.getElementById("nome").addEventListener("keypress", function (e) {
    if (!/^[a-zA-ZÀ-ÿ\s]$/.test(e.key)) {
        e.preventDefault();
    }
});

// Remove números ou caracteres inválidos colados no campo Nome
document.getElementById("nome").addEventListener("input", function () {
    this.value = this.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
});

// Manipulador de envio de formulário
document.getElementById("vote-form").addEventListener("submit", function (e) {
    e.preventDefault();

    if (!selectedCandidateId) {
        alert("Por favor, escolha um candidato!");
        return;
    }

    const ra = document.getElementById("ra").value;
    const nome = document.getElementById("nome").value;

    fetch('http://127.0.0.1/A3/backend/register_vote.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            ra: ra,
            nome: nome,
            candidate_id: selectedCandidateId,
        }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`Erro HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            if (data.status === 'success') {
                alert(data.message);
                updateCandidateVotes();
                closeVoteModal();
            } else {
                alert(data.message);
            }
        })
        .catch((error) => {
            console.error('Erro:', error);
            alert('Ocorreu um erro ao registrar o voto.');
        });
});

function updateCandidateVotes() {
    fetch('http://127.0.0.1/A3/backend/get_stats.php')
        .then((response) => {
            if (!response.ok) {
                throw new Error(`Erro HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            if (data.status !== 'success') {
                throw new Error(data.message || 'Erro desconhecido');
            }

            let statsHtml = "<h3>Estatísticas de Votos</h3>";
            data.data.forEach((candidato) => {
                statsHtml += `<p>${candidato.candidato}: ${candidato.total_votos} voto(s) (${candidato.porcentagem}%)</p>`;
            });

            const statsContainer = document.querySelector('.vote-stats');
            if (statsContainer) {
                statsContainer.innerHTML = statsHtml;
            } else {
                console.error('Elemento .vote-stats não encontrado.');
            }
        })
        .catch((error) => {
            console.error('Erro ao carregar estatísticas:', error);
            alert('Erro ao carregar estatísticas. Verifique o console para mais detalhes.');
        });
}
