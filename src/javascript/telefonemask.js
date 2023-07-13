window.addEventListener("DOMContentLoaded", (event) => {
    let telefone = document.querySelector('.telefone-mask');

    if(document.body.contains(telefone)){
        telefone.addEventListener('keyup', function() {
            telefone.value = mascaraTelefone(telefone.value);
        });
    }
});

function mascaraTelefone(value){
    if (!value) return "";

    value = value.replace(/\D/g,'');
    value = value.replace(/(\d{2})(\d)/,"($1) $2");
    value = value.replace(/(\d)(\d{4})$/,"$1-$2");

    return value;
}