function HideCookieWarn() {
    var x = document.getElementById("cookiewarn");
        if (x.style.display === "block") {
                x.style.display = "none";  
                isCookie = "true";
                if (isCookie != "" && isCookie != null) {
                setCookie("popup", isCookie, 365);
    }                      
            } 
            
    }
function setCookie(cname, cvalue, exdays) {
var d = new Date();
d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
var expires = "expires="+d.toUTCString();
document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
    
function getCookie(cname) {
var name = cname + "=";
var ca = document.cookie.split(';');
for(var i = 0; i < ca.length; i++) {
var c = ca[i];
while (c.charAt(0) == ' ') {
c = c.substring(1);
}
if (c.indexOf(name) == 0) {
return c.substring(name.length, c.length);
}
}
return "";
}
function checkCookie() {
var isCookie = getCookie("popup");
var x = document.getElementById("cookiewarn");
      
                
if (isCookie != "") {
    x.style.display = "none";     
} else {
    x.style.display = "block";
}

}

function goPrev()
{
    window.history.back();
}

$(function(){
    var navbar = $('.navbar');
    
    $(window).scroll(function(){
        if($(window).scrollTop() <= 5){
            navbar.removeClass('nav-sticky');
        } else {
            navbar.addClass('nav-sticky');
        }
    });
});