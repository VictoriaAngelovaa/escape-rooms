import { deleteRequest } from './api.js';

async function onExit() {
    try {
        await deleteRequest('./../controllers/exit.php');
        window.location.href = `./../views/login.php`;
    }
    catch(_e) {
        console.log('An error occurred');
    } 
}

async function showUserProfile() {
    window.location.href = `./../views/user.php?user=${user}`;
}

document.querySelector('.exit-button').addEventListener('click', onExit);
document.querySelector('.account-button').addEventListener('click', showUserProfile);
