const getRouteProfile = window.location.pathname.split('/')[1].startsWith('profile');
const getRouteFood = window.location.pathname.split('/')[1].startsWith('food');

if(getRouteProfile) {
    document.getElementById('profile').classList.add('active-route')
} else {
    document.getElementById('profile').classList.remove('active-route')
}

if(getRouteFood) {
    document.getElementById('food').classList.add('active-route')
} else {
    document.getElementById('food').classList.remove('active-route')
}
