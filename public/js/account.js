function ShowAddress() {
    
    var w = document.getElementById("infos");
    var x = document.getElementById("pass");
    var y = document.getElementById("address");
    var z = document.getElementById("orders");

    x.style.display = "none";  
    w.style.display = "none"; 
    z.style.display = "none"; 
        if (x.style.display === "none") {
            y.style.display = "block";  

        }                      
} 
            
 

 function ShowInfos() {
     
    var w = document.getElementById("infos");
    var x = document.getElementById("pass");
    var y = document.getElementById("address");
    var z = document.getElementById("orders");
    x.style.display = "none"; 
    y.style.display = "none"; 
    z.style.display = "none"; 
        if (x.style.display === "none") {
            w.style.display = "block";  
        }   
} 
            
 

function ShowPass() {
    var w = document.getElementById("infos");
    var x = document.getElementById("pass");
    var y = document.getElementById("address");
    var z = document.getElementById("orders");
    y.style.display = "none"; 
    z.style.display = "none"; 
    w.style.display = "none";
        if (x.style.display === "none") {
            x.style.display = "block";  
         }  else {
            w.style.display = "block";
            x.style.display = "none";
         }
         
}    


      
 

 function ShowOrders() {
    var w = document.getElementById("infos");
    var x = document.getElementById("pass");
    var y = document.getElementById("address");
    var z = document.getElementById("orders");
    x.style.display = "none";  
    w.style.display = "none"; 
    y.style.display = "none"; 
        if (x.style.display === "none") {
            z.style.display = "block";  
        }                      
 } 


 function FNEdit(){
    var x = document.getElementById("FN");
    var y = document.getElementById("FNI");
    if (x.style.display === "none") {
        x.style.display = "inline";  
        y.style.display = "none";  
    } else {
        y.style.display = "inline";
        x.style.display = "none";  
    } 
 }
 function LNEdit(){
    var x = document.getElementById("LN");
    var y = document.getElementById("LNI");
    if (x.style.display === "none") {
        x.style.display = "inline";  
        y.style.display = "none";  
    } else {
        y.style.display = "inline";
        x.style.display = "none";  
    } 
 }
 function EEdit(){
    var x = document.getElementById("E");
    var y = document.getElementById("EI");
    if (x.style.display === "none") {
        x.style.display = "inline";  
        y.style.display = "none";  
    } else {
        y.style.display = "inline";
        x.style.display = "none";  
    } 
 }
         
 function PNEdit(){
    var x = document.getElementById("PN");
    var y = document.getElementById("PNI");
    if (x.style.display === "none") {
        x.style.display = "inline";  
        y.style.display = "none";  
    } else {
        y.style.display = "inline";
        x.style.display = "none";  
    } 
 }
         
 function SEdit(){
    var x = document.getElementById("S");
    var y = document.getElementById("SI");
    if (x.style.display === "none") {
        x.style.display = "inline";  
        y.style.display = "none";  
    } else {
        y.style.display = "inline";
        x.style.display = "none";  
    } 
 }
         
 function CPEdit(){
    var x = document.getElementById("CP");
    var y = document.getElementById("CPI");
    if (x.style.display === "none") {
        x.style.display = "inline";  
        y.style.display = "none";  
    } else {
        y.style.display = "inline";
        x.style.display = "none";  
    } 
 }
         
 function CEdit(){
    var x = document.getElementById("C");
    var y = document.getElementById("CI");
    if (x.style.display === "none") {
        x.style.display = "inline";  
        y.style.display = "none";  
    } else {
        y.style.display = "inline";
        x.style.display = "none";  
    } 
 }
         
         
            
 