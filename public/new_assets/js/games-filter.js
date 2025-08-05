let currentCategory;

// Active Background for Game Category 
function setActivePosition(x,y){
    const block = document.getElementById('selected-category');
    block.classList.remove('hide');
    block.style.left=x+"px";
    block.style.width = `${y}px`;
}
      
// Clear Games 
function clearGames(){
    document.getElementById('game-list').innerHTML = "";
}

function displayGame(category){
    let parent = document.getElementById('game-list');

    if(category != 'all'){
        parent.querySelectorAll('.game-card').forEach((x)=>{
            x.classList.add('hide');
        });
        parent.querySelectorAll(`.${category}`).forEach((x)=>{
            x.classList.remove('hide');
        });
    }
    else{
        parent.querySelectorAll('.game-card').forEach((x)=>{
            x.classList.remove('hide');
        });
    }
}


const game_categories = document.querySelectorAll('.game-category');
game_categories.forEach(function(category) {
    category.addEventListener('click', function() {
        document.querySelectorAll('.game-category').forEach((x)=>{
            x.classList.remove('selected');
        });
        category.classList.add('selected');
        currentCategory = category.getAttribute('category');
        displayGame(currentCategory);
        setActivePosition(category.offsetLeft,category.offsetWidth);
    });
});
