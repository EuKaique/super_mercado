function phoneMask(input) {
    var value = input.value.replace(/\D/g, "")
                           .replace(/^(\d{2})(\d)/g, "($1) $2")
                           .replace(/(\d)(\d{4})$/, "$1-$2");
    input.value = value;
}
function moneyMask(input) {
    var value = input.value.replace(/\D/g, "")
                           .replace(/\B(?=(\d{3})+(?!\d))/g, ",");

    input.value = value;
}

