function multEmail() {
    if(document.getElementById("recvEmail").multiple == false){
        document.getElementById("recvEmail").multiple = true;
        document.getElementById("mulMsg").innerHTML = "The email field now accept multiple values.";
    }
    else{
        document.getElementById("recvEmail").multiple = false;
        document.getElementById("mulMsg").innerHTML = "";
    }
}