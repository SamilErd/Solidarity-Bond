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
            
 