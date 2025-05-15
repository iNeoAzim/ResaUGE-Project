// Animation sur les cartes admin
const cards = document.querySelectorAll('.admin-card');
cards.forEach(card => {
    card.addEventListener('mouseenter', () => {
        card.style.boxShadow = '0 12px 32px rgba(60,60,100,0.18)';
        card.style.transform = 'scale(1.05)';
    });
    card.addEventListener('mouseleave', () => {
        card.style.boxShadow = '0 2px 12px rgba(60,60,100,0.08)';
        card.style.transform = 'scale(1)';
    });
});

// Message de bienvenue dynamique (exemple d'effet)
const welcome = document.querySelector('.welcome');
if (welcome) {
    welcome.style.opacity = 0;
    setTimeout(() => {
        welcome.style.transition = 'opacity 1s';
        welcome.style.opacity = 1;
    }, 300);
}

// Confirmation lors du clic sur Déconnexion
const logoutLink = Array.from(document.querySelectorAll('nav a')).find(a => a.textContent.includes('Déconnexion'));
if (logoutLink) {
    logoutLink.addEventListener('click', function(e) {
        if (!confirm('Voulez-vous vraiment vous déconnecter ?')) {
            e.preventDefault();
        }
    });
}

// Accessibilité : navigation clavier sur la sidebar
const sidebarLinks = document.querySelectorAll('.sidebar nav a');
sidebarLinks.forEach(link => {
    link.addEventListener('focus', () => link.style.background = '#283593');
    link.addEventListener('blur', () => link.style.background = '');
});
