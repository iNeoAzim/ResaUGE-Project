// Variables globales
let currentPage = 1;
const itemsPerPage = 5;
let sortField = null;
let sortDirection = 'asc';
let filteredRows = [];

// Éléments DOM
const table = document.getElementById('usersTable');
const tbody = table.querySelector('tbody');
const rows = Array.from(tbody.querySelectorAll('tr'));
const searchInput = document.getElementById('searchInput');
const roleFilter = document.getElementById('roleFilter');
const statusFilter = document.getElementById('statusFilter');
const togglePasswordBtn = document.getElementById('togglePassword');
const pagination = document.getElementById('pagination');
const noResults = document.getElementById('noResults');
const statsText = document.getElementById('statsText');
const confirmModal = document.getElementById('confirmModal');

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser le tableau
    updateTable();
    
    // Ajouter les écouteurs d'événements
    searchInput.addEventListener('input', updateTable);
    roleFilter.addEventListener('change', updateTable);
    statusFilter.addEventListener('change', updateTable);
    togglePasswordBtn.addEventListener('click', togglePasswords);
    
    // Ajouter les écouteurs pour le tri
    const sortableHeaders = document.querySelectorAll('th.sortable');
    sortableHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const field = header.getAttribute('data-sort');
            handleSort(field);
        });
    });
});

// Fonction pour mettre à jour le tableau
function updateTable() {
    // Filtrer les lignes
    filteredRows = rows.filter(row => {
        const rowText = row.textContent.toLowerCase();
        const searchTerm = searchInput.value.toLowerCase();
        const role = row.getAttribute('data-role');
        const status = row.getAttribute('data-status');
        
        const matchesSearch = rowText.includes(searchTerm);
        const matchesRole = roleFilter.value === 'all' || role === roleFilter.value;
        const matchesStatus = statusFilter.value === 'all' || status === statusFilter.value;
        
        return matchesSearch && matchesRole && matchesStatus;
    });
    
    // Trier les lignes si nécessaire
    if (sortField) {
        sortRows();
    }
    
    // Mettre à jour la pagination
    updatePagination();
    
    // Afficher les lignes de la page actuelle
    displayRows();
    
    // Mettre à jour les statistiques
    updateStats();
}

// Fonction pour gérer le tri
function handleSort(field) {
    const headers = document.querySelectorAll('th.sortable');
    
    // Réinitialiser les classes de tri sur tous les en-têtes
    headers.forEach(header => {
        header.classList.remove('sorted-asc', 'sorted-desc');
    });
    
    // Mettre à jour le champ et la direction de tri
    if (sortField === field) {
        sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        sortField = field;
        sortDirection = 'asc';
    }
    
    // Ajouter la classe de tri à l'en-tête actif
    const activeHeader = document.querySelector(`th[data-sort="${field}"]`);
    activeHeader.classList.add(sortDirection === 'asc' ? 'sorted-asc' : 'sorted-desc');
    
    // Trier et afficher les lignes
    sortRows();
    displayRows();
}

// Fonction pour trier les lignes
function sortRows() {
    const getCellValue = (row, index) => {
        const cells = row.querySelectorAll('td');
        return cells[index].textContent.trim();
    };
    
    const getColumnIndex = (field) => {
        switch (field) {
            case 'nom': return 0;
            case 'prenom': return 1;
            case 'email': return 2;
            case 'role': return 4;
            case 'valide': return 5;
            default: return 0;
        }
    };
    
    const columnIndex = getColumnIndex(sortField);
    
    filteredRows.sort((a, b) => {
        const aValue = getCellValue(a, columnIndex);
        const bValue = getCellValue(b, columnIndex);
        
        if (sortField === 'valide') {
            // Tri spécial pour le statut
            const aStatus = a.getAttribute('data-status') === 'validated';
            const bStatus = b.getAttribute('data-status') === 'validated';
            return sortDirection === 'asc' 
                ? (aStatus === bStatus ? 0 : aStatus ? 1 : -1)
                : (aStatus === bStatus ? 0 : aStatus ? -1 : 1);
        }
        
        return sortDirection === 'asc' 
            ? aValue.localeCompare(bValue)
            : bValue.localeCompare(aValue);
    });
}

// Fonction pour mettre à jour la pagination
function updatePagination() {
    const totalPages = Math.ceil(filteredRows.length / itemsPerPage) || 1;
    
    // Ajuster la page courante si nécessaire
    if (currentPage > totalPages) {
        currentPage = totalPages;
    }
    
    // Générer les liens de pagination
    let paginationHTML = `
        <li class="${currentPage === 1 ? 'disabled' : ''}">
            <a href="#" onclick="changePage(${currentPage - 1}); return false;">&laquo;</a>
        </li>
    `;
    
    for (let i = 1; i <= totalPages; i++) {
        paginationHTML += `
            <li class="${i === currentPage ? 'active' : ''}">
                <a href="#" onclick="changePage(${i}); return false;">${i}</a>
            </li>
        `;
    }
    
    paginationHTML += `
        <li class="${currentPage === totalPages ? 'disabled' : ''}">
            <a href="#" onclick="changePage(${currentPage + 1}); return false;">&raquo;</a>
        </li>
    `;
    
    pagination.innerHTML = paginationHTML;
    
    // Afficher ou masquer la pagination selon le nombre de pages
    pagination.style.display = totalPages > 1 ? 'flex' : 'none';
}

// Fonction pour changer de page
function changePage(page) {
    const totalPages = Math.ceil(filteredRows.length / itemsPerPage) || 1;
    
    // Vérifier que la page est valide
    if (page < 1 || page > totalPages) {
        return;
    }
    
    currentPage = page;
    displayRows();
    updatePagination();
}

// Fonction pour afficher les lignes de la page courante
function displayRows() {
    // Masquer toutes les lignes
    rows.forEach(row => {
        row.style.display = 'none';
    });
    
    // Afficher les lignes filtrées de la page courante
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageRows = filteredRows.slice(start, end);
    
    pageRows.forEach(row => {
        row.style.display = '';
    });
    
    // Afficher un message si aucun résultat
    noResults.style.display = filteredRows.length === 0 ? 'block' : 'none';
}

// Fonction pour mettre à jour les statistiques
function updateStats() {
    const start = (currentPage - 1) * itemsPerPage + 1;
    const end = Math.min(start + itemsPerPage - 1, filteredRows.length);
    const total = rows.length;
    
    if (filteredRows.length === 0) {
        statsText.textContent = `Aucun compte ne correspond aux critères de recherche (sur ${total} au total)`;
    } else {
        statsText.textContent = `Affichage de ${start} à ${end} sur ${filteredRows.length} compte(s)`;
        
        if (filteredRows.length !== total) {
            statsText.textContent += ` (filtré depuis ${total} au total)`;
        }
    }
}

// Fonction pour afficher/masquer les mots de passe
function togglePasswords() {
    const hiddenElements = document.querySelectorAll('.password-hidden');
    const visibleElements = document.querySelectorAll('.password-visible');
    
    hiddenElements.forEach(el => {
        el.classList.toggle('hidden');
    });
    
    visibleElements.forEach(el => {
        el.classList.toggle('hidden');
    });
    
    togglePasswordBtn.textContent = togglePasswordBtn.textContent === 'Afficher MDP' ? 'Masquer MDP' : 'Afficher MDP';
}

// Fonctions pour la modal de confirmation
function openModal(action, userId, prenom, nom) {
    const modal = document.getElementById('confirmModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalBody = document.getElementById('modalBody');
    const modalUserId = document.getElementById('modalUserId');
    const modalAction = document.getElementById('modalAction');
    const confirmButton = document.getElementById('confirmButton');

    modalUserId.value = userId;
    modalAction.value = action;

    if (action === 'validate') {
        modalTitle.textContent = 'Valider le compte';
        modalBody.textContent = `Êtes-vous sûr de vouloir valider le compte de ${prenom} ${nom} ?`;
        confirmButton.textContent = 'Valider';
        confirmButton.className = 'btn-validate';
    } else if (action === 'reject') {
        modalTitle.textContent = 'Rejeter le compte';
        modalBody.textContent = `Êtes-vous sûr de vouloir rejeter le compte de ${prenom} ${nom} ?`;
        confirmButton.textContent = 'Rejeter';
        confirmButton.className = 'btn-reject';
    }

    modal.style.display = 'flex';
}

function closeModal() {
    const modal = document.getElementById('confirmModal');
    modal.style.display = 'none';
}

// Fermer la modal si on clique en dehors
window.onclick = function(event) {
    const modal = document.getElementById('confirmModal');
    if (event.target === modal) {
        closeModal();
    }
}