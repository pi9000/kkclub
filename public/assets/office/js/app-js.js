document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
   });
   document.addEventListener('keydown', function(e) {
   if (event.keyCode == 123) {
    return false;
   }
   if (e.ctrlKey && e.shiftKey) {
    return false;
   }
   if (event.ctrlKey && event.keyCode == 85) {
    return false;
   }
  });

   document.onkeydown = function(e) {
   if(event.keyCode == 123) {
       return false;
   }
   if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){
       return false;
   }
   if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){
       return false;
   }
   if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)){
       return false;
   }    
   if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){
       return false;
   }
   if(e.ctrlKey && e.keyCode == 'S'.charCodeAt(0)){
       return false;
   }
   if(e.ctrlKey && e.keyCode == 'F'.charCodeAt(0)){
       return false;
   }
   if(e.ctrlKey && e.keyCode == 'D'.charCodeAt(0)){
       return false;
   }
   if(e.ctrlKey && e.keyCode == 'E'.charCodeAt(0)){
       return false;
   }
   if(e.ctrlKey && e.keyCode == 'O'.charCodeAt(0)){
       return false;
   }
   if(e.ctrlKey && e.keyCode == 'W'.charCodeAt(0)){
       return false;
   }
   }