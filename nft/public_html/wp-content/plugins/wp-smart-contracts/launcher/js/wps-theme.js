document.addEventListener("DOMContentLoaded", function(event) {


    var theBody = document.body;
    var themeSwitch = document.getElementById('wps-theme');

    themeSwitch.addEventListener('click', function name(e) {

        if(theBody.classList.contains('light-theme')){
            theBody.classList.remove('light-theme');
            theBody.classList.add('dark-theme');
        }else {
            theBody.classList.remove('dark-theme');
            theBody.classList.add('light-theme');
        }
        
    })

    

});