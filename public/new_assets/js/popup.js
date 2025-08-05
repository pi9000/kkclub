
// x = element id
function closePopup(x){
    if(x){
        let target;
        target = document.getElementById(x);
        target.style.display = "none";
    }
    else{
        document.querySelectorAll('.popup').forEach((x)=>{
           x.style.display = 'none'; 
        });
    }
  
    setOverlay(false);
}

// x = element id
function openPopup(x){
    let target = document.getElementById(x);
    setOverlay(true);
    target.style.display = "flex";
}