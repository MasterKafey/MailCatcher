import './styles/app.css';
import $ from 'jquery';

function initializeBurgerButton() {
    $('#burger-button').click(() => {
        $('#sidebar-menu').slideToggle();
        $('#burger-button svg').toggleClass('text-white');
    });
}

$(document).ready(() => {
    initializeBurgerButton();
});

let tabsInitializer = function () {
    let tabEmailLinks = document.getElementsByClassName('tab-email');

    for (let i = 0; i < tabEmailLinks.length; ++i) {
        let link = tabEmailLinks[i];
        let tabId = link.getAttribute('href').substring(1);
        let tab = document.getElementById(tabId);

        link.onclick = function () {
            for (let j = 0; j < tabEmailLinks.length; ++j) {
                let currentTabId = tabEmailLinks[j].getAttribute('href').substring(1);
                let currentTab = document.getElementById(currentTabId);
                if (currentTab) {
                    currentTab.classList.add('hidden');
                    tabEmailLinks[j].classList.remove('text-primary', 'border-b-2', 'border-primary');
                }
            }

            tab.classList.remove('hidden');
            link.classList.add('text-primary', 'border-b-2', 'border-primary');
        }
    }
};


let sendMail = async function (url) {
    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        const result = await response.json();
        displayMessage(result.message, 'success');
    } catch (error) {
        displayMessage('Erreur lors de l\'envoi du mail.', 'error');
    }
};

let deleteMail = async function (url) {
    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        const result = await response.json();
        window.location.reload();
        displayMessage(result.message, 'success');
    } catch (error) {
        displayMessage('Erreur lors de la suppression du mail.', 'error');
    }
};

function displayMessage(message, type) {
    const popupMessageContainer = document.getElementById('popup-message');
    const popupMessageContent = document.getElementById('popup-message-content');

    popupMessageContent.innerHTML = `<span class="${type === 'success' ? 'text-white' : 'text-white'}">${message}</span>`;
    popupMessageContainer.classList.remove('hidden'); // Retire la classe hidden pour afficher le pop-up
}

function closePopupMessage() {
    const popupMessageContainer = document.getElementById('popup-message');
    popupMessageContainer.classList.add('hidden'); // Ajoute la classe hidden pour masquer le pop-up
}

window.onload = function () {
    let links = document.getElementsByClassName('mail-link');
    let emailContent = document.getElementById('mail-content');
    let spinner = document.getElementById('loading-spinner');
    let inboxInformation = document.getElementById('inbox-information');

    for (let i = 0; i < links.length; ++i) {
        let link = links[i];
        link.onclick = async function () {
            inboxInformation.classList.add('hidden');
            emailContent.classList.remove('hidden');
            spinner.classList.remove('hidden');
            link.classList.add('bg-gray-100');
            const response = await fetch(link.getAttribute('data-link'), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            const view = await response.json();
            emailContent.innerHTML = view.view;
            tabsInitializer();
            spinner.classList.add('hidden');

            // Attachez les gestionnaires d'événements aux boutons dans le nouveau contenu
            let sendButton = document.getElementById('sendButton');
            let deleteButton = document.getElementById('deleteButton');

            if (sendButton && deleteButton) {
                sendButton.onclick = function () {
                    sendMail(sendButton.getAttribute('data-url'));
                };

                deleteButton.onclick = function () {
                    deleteMail(deleteButton.getAttribute('data-url'));
                };
            }
        }
    }

    document.getElementById('display-configuration-button').onclick = function () {
        inboxInformation.classList.remove('hidden');
        emailContent.classList.add('hidden');
    };
};