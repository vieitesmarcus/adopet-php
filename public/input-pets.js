const addpetInput = document.querySelector('#addPet');

addpetInput.addEventListener('click', (event) => {
    const form = document.querySelector('#popup');
    console.log(form.getAttribute('id'))
    if (form.getAttribute('class') == "container popup") {
        form.setAttribute('class', "container");

    } else {
        form.setAttribute('class', "container popup");
    }
})