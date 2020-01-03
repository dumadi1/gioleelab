var name;
var emailed;

function doSomething(index){
    
    var email=document.getElementById("email");
    var label=document.getElementById("emailholder");
    var phone=document.getElementById("phone");
    var label2=document.getElementById("phoneholder");
    
if(index==1){

    
    email.setAttribute("type","text");
    label.style.display='block';
    phone.setAttribute("type","hidden");
    label2.style.display='none';
    jQuery.ajax({
        type: "POST",
        url: 'returnmail.php',
        data: {customer: name},
    
        success: function(response) {
            email.value=response;
        }
    });
    //alert("<?php echo returnemail(name);?>");

}else if(index==2){
    phone.setAttribute("type","text");
    label2.style.display='block';
    email.setAttribute("type","hidden");
    label.style.display='none';
    jQuery.ajax({
        type: "POST",
        url: 'returnnumber.php',
        data: {customer: name},
    
        success: function(response) {
            phone.value=response;
        }
    });

}else{
    phone.setAttribute("type","text");
    label2.style.display='block';
    email.setAttribute("type","text");
    label.style.display='block';
    jQuery.ajax({
        type: "POST",
        url: 'returnmail.php',
        data: {customer: name},
    
        success: function(response) {
            email.value=response;
        }
    });
    jQuery.ajax({
        type: "POST",
        url: 'returnnumber.php',
        data: {customer: name},
    
        success: function(response) {
            phone.value=response;
        }
    });

}
}

function myFunction(cust){
    var email=document.getElementById("email");
    var label=document.getElementById("emailholder");
    var phone=document.getElementById("phone");
    var label2=document.getElementById("phoneholder");
name=cust;
phone.setAttribute("type","hidden");
label2.style.display='none';
email.setAttribute("type","hidden");
label.style.display='none';

}
function ret(){
    return name;
}