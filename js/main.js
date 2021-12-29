document.addEventListener('scroll', function () {
    let y = window.scrollY;
    /*console.log(y);*/
    if (y > 100){
        document.querySelector('header').classList.add('scroll');
    } else {
        document.querySelector('header').classList.remove('scroll');
    }
})

const form = document.getElementById('form');

form.addEventListener('submit',(e) => {
    e.preventDefault();

    let nombre = form.elements['nombre'].value,
        hijo = form.elements['hijo'].value,
        edad = form.elements['edad'].value,
        email = form.elements['email'].value,
        mensaje = form.elements['mensaje'].value,
        URL = './actions/contacto.php';

    fetch(URL,{
        method: 'POST',
        body: JSON.stringify({
            nombre: nombre,
            hijo: hijo,
            edad: edad,
            email: email,
            mensaje: mensaje,
        }),
        headers: {'Content-Type': 'application/json; charset=UTF-8'},
    })
        .then(function (json) {
            return json.json()
        })
        .then(res => {
            /*console.log(res);*/
            respuesta(res);
        })
        .catch(onerror => {
            console.error(onerror);
        })
})

function toggle() {
    let nav = document.querySelector('nav');
    nav.classList.toggle('active');
}

function respuesta(response) {
    if (response.success){
        form.reset();
        let div = `<div class="alert alert-success"><p class="text-center">${response.success}</p></div>`;
        form.insertAdjacentHTML('beforebegin',div);
        borrarAlert();
        borrarEmpty();
    } else {
        let div = `<div class="alert alert-danger"><p class="text-center">${response.error}</p></div>`;
        form.insertAdjacentHTML('beforebegin',div);

        let inputs = document.querySelectorAll('input');
        for (let j = 0; j < inputs.length; j++){
            if (inputs[j].value === ''){
                inputs[j].setAttribute('class','empty');
            }
        }
        let textarea = document.getElementById('mensaje');
        if (textarea.value == ''){
            textarea.setAttribute('class','empty')
        }

        borrarAlert();
    }
}

function borrarAlert(){
    let alerts = document.querySelectorAll('.alert');
    setTimeout(function () {
        for (let i = 0; i < alerts.length; i++){
            alerts[i].remove();
        }
    },10000);
}

function borrarEmpty() {
    let empties = document.querySelectorAll('.empty');
    for (let m = 0; m < empties.length; m++){
        if (empties[m].classList.contains('empty')){
            empties[m].classList.remove("empty");
        }
    }
}