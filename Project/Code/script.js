const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});

const registerRoleSelect = document.getElementById('register-role');
const loginRoleSelect = document.getElementById('login-role');
const registerRoleDescription = document.getElementById('role-description-register');
const loginRoleDescription = document.getElementById('role-description-login');

const roleDescription = {
    student: "texte à afficher pour l'élève",
    teacher: "texte à afficher pour le professeur",
    agent: "texte à afficher pour l'agent",
    admin: "texte à afficher pour l'administrateur",
};

function updateRoleDescription(selectElement, descriptionElement) {
    const selectedRole = selectElement.value;
    descriptionElement.textContent = roleDescription[selectedRole];
}

registerRoleSelect.addEventListener('change', () => {
    updateRoleDescription(registerRoleSelect, registerRoleDescription);
});

loginRoleSelect.addEventListener('change', () => {
    updateRoleDescription(loginRoleSelect, loginRoleDescription);
});