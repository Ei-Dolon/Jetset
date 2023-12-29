document.addEventListener("DOMContentLoaded", function(event) {

    var acc = document.getElementsByClassName("wps-accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function(e) {

            if(!e.target.classList.contains('select-dropdown--1')) {


                this.classList.toggle("active");
                var wpsMobileDirection = this.querySelector('.wps-mobile-direction');


                if(wpsMobileDirection) {
                    if (this.classList.contains('active')) {
                        wpsMobileDirection.textContent = '[ - ]';
                    }else {
                        wpsMobileDirection.textContent = '[ + ]';
                    }
                }

                var panel = this.nextElementSibling;
                
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                } 
            }

        });
    }

    //Open thge first accordeon on mobile
    setTimeout(function() {
        var firstAccordion = document.getElementById('first-accordion');

        if (firstAccordion) {
            firstAccordion.click();
        }
    }, 200);
});