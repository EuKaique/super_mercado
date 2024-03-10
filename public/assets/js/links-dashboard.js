const routes = ['profile', 'food', 'homeappliances', 'electronics', 'tools', 'games', 'computing', 'otherproducts'];

routes.forEach(route => {
    const element = document.getElementById(route);
    const isActiveRoute = window.location.pathname.split('/')[1].startsWith(route);
    
    if (isActiveRoute) {
        element.classList.add('active-route');
    } else {
        element.classList.remove('active-route');
    }
});
