window.digpage = {
    apiUrl:(function(){
        var domain = window.location.hostname;
        var tmp = domain.split('.').reverse();
        return 'http://api.' + tmp[1] + '.' + tmp[0] + '/';
    })()
};