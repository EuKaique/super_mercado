function openDropdown() {
    document.querySelector('.dropdown-container').classList.toggle('active');
}

document.addEventListener('click', (event) => {
    var dropdown = document.querySelector('.dropdown-container');
    var button = document.querySelector('.user-container button');

    if (!dropdown.contains(event.target) && !button.contains(event.target)) {
        dropdown.classList.remove('active');
    }
});

function router(){
    var getRoute = window.location.pathname;
    
    if (getRoute !== '/'){
        document.querySelector('.search-container').style.display = 'none';
    }

}
router()