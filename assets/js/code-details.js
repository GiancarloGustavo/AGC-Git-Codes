
function copyCodeHtml(button) {
    // Trouve le conteneur de code le plus proche
    const codeContainer = button.closest('.html-codes').querySelector('.code-container code');
    
    // Crée un élément textarea temporaire
    const textarea = document.createElement('textarea');
    textarea.value = codeContainer.textContent;
    textarea.style.position = 'fixed';  // Pour éviter le défilement
    document.body.appendChild(textarea);
    textarea.select();
    
    try {
        // Exécute la copie
        const successful = document.execCommand('copy');
        const msg = successful ? 'successful' : 'unsuccessful';
        console.log('Copying text command was ' + msg);
        
        // Change l'icône temporairement
        button.innerHTML = `<i class="fa-solid fa-check" style="color: green;"></i> Copied`;
        
        // Remet l'état initial après 2 secondes
        setTimeout(() => {
            button.innerHTML = `<i class="fa-regular fa-copy"></i> Copy`;
        }, 2000);
    } catch (err) {
        console.error('Error in copying text: ', err);
    } finally {
        // Nettoie
        document.body.removeChild(textarea);
    }
}


function copyCodeCss(button) {
    // Trouve le conteneur de code le plus proche
    const codeContainer = button.closest('.css-codes').querySelector('.code-container code');
    
    // Crée un élément textarea temporaire
    const textarea = document.createElement('textarea');
    textarea.value = codeContainer.textContent;
    textarea.style.position = 'fixed';  // Pour éviter le défilement
    document.body.appendChild(textarea);
    textarea.select();
    
    try {
        // Exécute la copie
        const successful = document.execCommand('copy');
        const msg = successful ? 'successful' : 'unsuccessful';
        console.log('Copying text command was ' + msg);
        
        // Change l'icône temporairement
        button.innerHTML = `<i class="fa-solid fa-check" style="color: green;"></i> Copied`;
        
        // Remet l'état initial après 2 secondes
        setTimeout(() => {
            button.innerHTML = `<i class="fa-regular fa-copy"></i> Copy`;
        }, 2000);
    } catch (err) {
        console.error('Error in copying text: ', err);
    } finally {
        // Nettoie
        document.body.removeChild(textarea);
    }
}


function copyCodeJs(button) {
    // Trouve le conteneur de code le plus proche
    const codeContainer = button.closest('.js-codes').querySelector('.code-container code');
    
    // Crée un élément textarea temporaire
    const textarea = document.createElement('textarea');
    textarea.value = codeContainer.textContent;
    textarea.style.position = 'fixed';  // Pour éviter le défilement
    document.body.appendChild(textarea);
    textarea.select();
    
    try {
        // Exécute la copie
        const successful = document.execCommand('copy');
        const msg = successful ? 'successful' : 'unsuccessful';
        console.log('Copying text command was ' + msg);
        
        // Change l'icône temporairement
        button.innerHTML = `<i class="fa-solid fa-check" style="color: green;"></i> Copied`;
        
        // Remet l'état initial après 2 secondes
        setTimeout(() => {
            button.innerHTML = `<i class="fa-regular fa-copy"></i> Copy`;
        }, 2000);
    } catch (err) {
        console.error('Error in copying text: ', err);
    } finally {
        // Nettoie
        document.body.removeChild(textarea);
    }
}


// ------------------------------------------------------------------------------

// for the toggle of the codes container by the button

const htmlContainerCodes = document.querySelector('.codes-container-html');
const cssContainerCodes = document.querySelector('.codes-container-css');
const jsContainerCodes = document.querySelector('.codes-container-js');

const htmlBtnToggle = document.querySelector('.html-btn');
const cssBtnToggle = document.querySelector('.css-btn');
const jsBtnToggle = document.querySelector('.js-btn');

htmlBtnToggle.onclick = () => {
    htmlBtnToggle.classList.add('active');
    jsBtnToggle.classList.remove('active');
    cssBtnToggle.classList.remove('active');

    htmlContainerCodes.classList.add('active');
    cssContainerCodes.classList.remove('active');
    jsContainerCodes.classList.remove('active');
}

cssBtnToggle.onclick = () => {
    htmlBtnToggle.classList.remove('active');
    jsBtnToggle.classList.remove('active');
    cssBtnToggle.classList.add('active');

    htmlContainerCodes.classList.remove('active');
    cssContainerCodes.classList.add('active');
    jsContainerCodes.classList.remove('active');
}

jsBtnToggle.onclick = () => {
    htmlBtnToggle.classList.remove('active');
    jsBtnToggle.classList.add('active');
    cssBtnToggle.classList.remove('active');

    htmlContainerCodes.classList.remove('active');
    cssContainerCodes.classList.remove('active');
    jsContainerCodes.classList.add('active');
}


// --------------------------------------

function showFormFill(){
    const formFill = document.querySelector('.add-comment-container');

    formFill.classList.toggle('hide');
}


