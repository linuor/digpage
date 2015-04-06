window.digpage = {
    apiUrl:(function(){
        var domain = window.location.hostname;
        var tmp = domain.split('.').reverse();
        return 'http://api.' + tmp[1] + '.' + tmp[0] + '/';
    })(),
    touchVer: function(id){
        if (!id || id == 'null') return;
        id = 'section' + id;
        var dataset = document.getElementById(id).dataset;
        dataset['ver'] = parseInt(dataset['ver']) + 1;
    }
};
