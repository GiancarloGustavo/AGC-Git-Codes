

function toggleHeaderName(){
    const burgerOne = document.querySelector('.one');
    const burgerTwo = document.querySelector('.two');
    const burgerThree = document.querySelector('.three');

    burgerOne.classList.toggle('active');
    burgerTwo.classList.toggle('active');
    burgerThree.classList.toggle('active');

    const logoName = document.querySelector('.header-logo-container-image-logo-name');
    const linksName = document.querySelector('.links-stand-up-name');
    const profileName = document.querySelector('.profile-name');

    logoName.classList.toggle('hide');
    linksName.classList.toggle('hide');
    profileName.classList.toggle('hide');

    //for the responsive

    if(window.innerWidth < 545){
        const main = document.querySelector('main');

        main.classList.toggle('mainHide');
    }
}


function toggleHeaderNameNoConnexion(){

    const burgerOne = document.querySelector('.one');
    const burgerTwo = document.querySelector('.two');
    const burgerThree = document.querySelector('.three');

    burgerOne.classList.toggle('active');
    burgerTwo.classList.toggle('active');
    burgerThree.classList.toggle('active');

    const logoName = document.querySelector('.header-logo-container-image-logo-name');
    const noConnexionProfileName = document.querySelector('.profile-no-connexion-span-name-container');
    const infoNoConnexion = document.querySelector('.span-name-icon-container');

    logoName.classList.toggle('hide');
    noConnexionProfileName.classList.toggle('hide');
    infoNoConnexion.classList.toggle('hide');

    if(window.innerWidth < 545){
        const main = document.querySelector('main');
        main.classList.toggle('mainHide'); 
    }
}

function searchFunction(){
    const searchPanel = document.querySelector('.overlay-search');
    const textPresentation = document.querySelector('.presentation-text');
    const CodeH = document.querySelector('.code-h1');

    searchPanel.classList.toggle('active');

    if(searchPanel.classList.contains('active')){
        textPresentation.style.opacity = '0';
        CodeH.style.opacity = '0';
    }else{
        textPresentation.style.opacity = '1';
        CodeH.style.opacity = '1';
    }
}

