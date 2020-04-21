/*
    general functions to use in the system
*/

$(document).ready(function(){
    // for 000webhost
    $(".footer + div").css("display","none");
});

var lastScrollTop = 0;

function myScrollListener(element, DownFunction, UpFunction){
    
    element.addEventListener("scroll", function(){ // or window.addEventListener("scroll"....
        var st = window.pageYOffset || document.documentElement.scrollTop;
        if (st > lastScrollTop){
            DownFunction();
        } else {
            UpFunction();
        }
        lastScrollTop = st <= 0 ? 0 : st; // For Mobile or negative scrolling
    }, false);
}
