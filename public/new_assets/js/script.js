// Side Navigation
const 
    overlay = document.getElementById("overlay"),
    navi_toggle = document.getElementById("burger-menu"),
    navi = document.getElementById("side-navi");
    
    //overlay.addEventListener('click',closeNavi);
    try{
        navi_toggle.addEventListener('click',openNavi);
    }
    catch(e){
        /* nth */
    }


// Set Overlay
function setOverlay(x){
    if(x == true){
        document.body.style.overflow = 'hidden';
        overlay.style.display = 'block';
    }
    else{
        document.body.style.overflow = 'scroll';
        overlay.style.display = 'none';
    }
}

// Close Navigation
function closeNavi(){
    setOverlay(false);
    navi.style.left = '-100%';
    navi.style.transform = "none";
}
    
// Show Navigation
function openNavi(){
    setOverlay(true);
    navi.style.left = '50%';
    navi.style.transform = "translateX(-59%)";
}

function showLoading(){
    document.getElementById('loading-screen').classList.remove('hide');
}

function hideLoading(){
    document.getElementById('loading-screen').classList.add('hide');
}

/** Remove Array **/
function dropArray(array, item){
    for(var i in array){
        if(array[i]==item){
            array.splice(i,1);
            break;
        }
    }
}

/** UPLOAD FILE **/
$("#files").change(function() {
  filename = this.files[0].name;
});


/** SHOW TOGGLE GROUP **/
function showToggle(){
    
    // Get button attr 
    let target = this.getAttribute('target'),
    container = this.closest('div');
    
    // Remove all selected style
    container.querySelectorAll('button').forEach((x)=>{
        x.classList.remove('selected');
    });
    
    try{
        // Hide all toggle-content
        let parent = this.closest('.toggle-group');
        parent.querySelectorAll('.toggle-content').forEach(function(element){
            if(element.getAttribute('id') != target){
                element.classList.remove('selected');
            }
        });
        
        // Proceed Next Step
        defineAction(target);
        
        // Display the selected toggle group
        console.log(target);
        parent.querySelector("#"+target).classList.add('selected');
    }
    catch(e){
        console.log(e);
    }
    
    // Add selected style to clicked button
    this.classList.add('selected');
}

/** FETCH DATA **/
function defineAction(x){
    let data = false;
    if(x == 'data-bonus'){
        $.ajax({
            type: "post",
            url: "fetch/data.php",
            async: false,
            data: {
                action: 'fth-bonus'
            },
            success: function(response) {
                res = JSON.parse(response);
                if(res.err === false){
                    if(!res.data || res.data.length < 1){
                        createEmptyTable(x,res.msg);
                    }
                    else{
                        createTable(x,res.data);    
                    }
                }
            }
        });
    } 
    
    else if(x == 'data-referral'){
        $.ajax({
            type: "post",
            url: "fetch/data.php",
            async: false,
            data: {
                action: 'fth-referral'
            },
            success: function(response) {
                res = JSON.parse(response);
                if(res.err === false){
                    if(!res.data || res.data.length < 1){
                        createEmptyTable(x,res.msg);
                    }
                    else{
                        createTable(x,res.data);    
                    }
                }
            }
        });
    } 
    
    else if(x == 'data-promo'){
        $.ajax({
            type: "post",
            url: "fetch/data.php",
            async: false,
            data: {
                action: 'fth-promo'
            },
            success: function(response) {
                res = JSON.parse(response);
                if(res.err === false){
                    if(!res.data || res.data.length < 1){
                        createEmptyTable(x,res.msg);
                    }
                    else{
                        createTable(x,res.data);    
                    }
                }
            }
        });
    }
    
    else if(x == 'data-applybonus'){
        submitApplyBonus();
    }
    
    else if(x == 'data-autopromo'){
        $.ajax({
            type:'post',
            url:'fetch/data.php',
            async:false,
            data: {
                action:'fth-autopromo'
            },
            success: function(response){
                res = JSON.parse(response);
                if(res.err === false){
                    if(!res.data || res.data.length < 1){
                        createEmptyTable(x,res.msg);
                    }
                    else{
                        createTable(x,res.data);    
                    }
                }
            }
        })    
    }
    
    else if(x == 'data-updatebonus'){
        try{
        $.ajax({
           type:'post',
           url:'fetch/data.php',
           async:false,
           data:{
               action:'fth-updatebonus'
           },
           success: function(response){
               res = JSON.parse(response);
               if(res.err === false){
                   createUpdateBonus(res.data);
               }
               else{
                   throw res.err;
               }
           }
        });
        }
        catch(e){
            alert(e);
        }
    }
    
    else if(x == 'deposit-bank'){
        // createSelOptions('sel-bank',dummyData);
    }
    
    else if(x == 'content-deposit-history'){
        $.ajax({
            type: "post",
            url: "fetch/data.php",
            async: false,
            data: {
                action: 'fth-deposithistory'
            },
            success: function(response) {
                res = JSON.parse(response);
                if(res.err === false){
                    if(!res.data || res.data.length < 1){
                        createEmptyTable(x,res.msg);
                    }
                    else{
                        createTable(x,res.data);    
                    }
                }
            }
        });
    }
    
    else if(x == 'content-withdrawal-history'){
        $.ajax({
            type: "post",
            url: "fetch/data.php",
            async: false,
            data: {
                action: 'fth-withdrawhistory'
            },
            success: function(response) {
                res = JSON.parse(response);
                if(res.err === false){
                    if(!res.data || res.data.length < 1){
                        createEmptyTable(x,res.msg);
                    }
                    else{
                        createTable(x,res.data);    
                    }
                }
            }
        });
    }

    else if(x == 'content-transfer-history'){
        $.ajax({
            type: "post",
            url: "fetch/data.php",
            async: false,
            data: {
                action: 'fth-transferhistory'
            },
            success: function(response) {
                res = JSON.parse(response);
                if(res.err === false){
                    if(!res.data || res.data.length < 1){
                        createEmptyTable(x,res.msg);
                    }
                    else{
                        createTable(x,res.data);    
                    }
                }
            }
        });
    }    

}

/** Submit Apply Bonus Search Paratmeter **/
function submitApplyBonus(){
    const container = document.getElementById('data-applybonus');
    let start = document.getElementById('applybonus-start').getAttribute('value'),
    end = document.getElementById('applybonus-end').getAttribute('value');
    $.ajax({
            type:'post',
            url:'fetch/data.php',
            async:false,
            data: {
                action:'fth-applybonus',start:start,end:end
            },
            success: function(response){
                res = JSON.parse(response);
                if(res.err === false){
                    if(!res.data || res.data.length < 1){
                        createEmptyTable('data-applybonus',res.msg);
                    }
                    else{
                        createTable('data-applybonus',res.data);    
                    }
                }
            }
        }) 
      
}

/** Create Update Bonus **/
// x = data
function createUpdateBonus(x){
    try{
        const container = document.getElementById('bonus-list');
        
        // Clear container innerHTML
        container.innerHTML = '';
        x.forEach((val)=>{
            let div = document.createElement('div'),
            style = val.value > 0 ? '' : 'disabled';
            div.classList.add('bonus-card');
            div.innerHTML = "<div class='bonus-box "+style+"' id='"+val.id+"' value='"+val.value+"'onclick='setUpdateBonus(this)'><div class='label'>"+val.name+"</div><div class='value'>"+(Math.round(val.value * 100) / 100).toFixed(2)+"</div></div>";
            container.appendChild(div);
        });
    }
    catch(e){
        console.log(e);
    }
}

function setUpdateBonus(x){
    if(x.classList.contains("disabled")){
        console.log("Disabled Target!!!");
    }
    else{
        x.classList.contains('selected') ? x.classList.remove('selected') : x.classList.add('selected');
        countUpdateBonus();
    }
}

function submitUpdateBonus(){
    try{
        let list = new Array() ;
        const container = document.getElementById('bonus-list');
        container.querySelectorAll('.bonus-box.selected').forEach((x)=>{
            list.push(x.getAttribute('id'));
        });
        
        // submit the list
        /* Insert code here*/
        console.log(list);
    }
    catch(e){
        console.log(e);
    }
}

function allUpdateBonus(x){
    try{
        const container = document.getElementById('bonus-list');
        if(!x.classList.contains('active')){
            container.querySelectorAll('.bonus-box').forEach((x)=>{
                if(!x.classList.contains('disabled')){
                    x.classList.add('selected');
                }
            });
            x.classList.add('active');
        }
        else{
            container.querySelectorAll('.bonus-box').forEach((x)=>{
                if(!x.classList.contains('disabled')){
                    x.classList.remove('selected');
                }
            });
            x.classList.remove('active')
        }
        countUpdateBonus();
    }
    catch(e){
        console.log(e);
    }
}

/** Calculate Total Update Bonus **/
function countUpdateBonus(){
    try{
        let total = 0;
        const container = document.getElementById('bonus-list'),
        element = document.getElementById('total-bonusamount');
        container.querySelectorAll('.bonus-box.selected').forEach((x)=>{
            total = !x.classList.contains('disabled') ? total+parseFloat(x.getAttribute('value')) : total;
        });
        element.innerHTML = parseFloat(total).toFixed(2);
    }
    catch(e){
        console.log(e);
    }
}

/** Create Table (No Data Return)**/
// x = id, y = key (column name)
function createEmptyTable(x,y){
    let table = document.getElementById(x).querySelector('.show-table table'),
    footer = document.getElementById(x).querySelector('.table-scroll-space'),
    col = y.length;
    
    // Clear all content inside the table bofore insert new data
    table.innerHTML = '';
    
    let first_tr = document.createElement('tr');
    y.forEach((val)=>{
        let th = document.createElement('th');
        th.innerHTML = val;
        first_tr.appendChild(th);
        
    });
    table.appendChild(first_tr);
    let content = document.createElement('tr'),
    td = document.createElement('td');
    td.setAttribute('colspan',col);
    td.style.textAlign = "center";
    td.innerHTML = "No data found";
    content.appendChild(td);
    table.appendChild(content);
    
    footer.style.display = 'block';
    footer.style.backgroundColor = "#2e2e2e";
}

/** Create Table **/
// x = id, y = data
function createTable(x,y){
    
    let table = document.getElementById(x).querySelector('.show-table table'),
    footer = document.getElementById(x).querySelector('.table-scroll-space'),
    first_tr = false;
    
    // clear all content inside the table before insert new data
    table.innerHTML = '';

  
    y.forEach((val)=>{
        let tr = document.createElement('tr');
            
        // Create first row (th)
        if(first_tr === false){
            first_tr = document.createElement('tr');
            Object.keys(val).forEach(function(key){
                let th = document.createElement('th');
                th.innerHTML = key;
                first_tr.appendChild(th);
            });
            table.appendChild(first_tr);
        }
            
        Object.keys(val).forEach(function(key){
            let td = document.createElement('td');
            td.innerHTML = val[key];
            tr.appendChild(td);
        });
        table.appendChild(tr);
    });
    
    footer.style.display = 'block';
    footer.style.backgroundColor = y.length % 2 == 0 ?  "#363636" : "#2e2e2e";
}



/** Add eventlisnter to btn-toggle **/
try{
    document.querySelectorAll('.btn-toggle').forEach(function(x){
        x.addEventListener('click',showToggle);
    });
}
catch(e){
    console.log(e);
}

/** Fetch Bet Record Options **/
function fthBetRecordOptions(){
    let data = false;
    $.ajax({
        type: "post",
        url: "fetch/data.php",
        async: false,
        data: {
            action: 'fth-betrecordoptions'
        },
        success: function(response) {
            try{
                res = JSON.parse(response);
                if(res.err === false){
                    if(!res.data || res.data.length < 1){
                        throw "!res.data";
                    }
                    createSelOptions('sel-betrecord',res.data);
                }
                else{
                    throw res.err;
                }
            }
            catch(e){
                console.log(e);
            }
        }
    });
}

/** Fetch Bet Record **/
// x = category (select value)
function fthBetRecord(x){
    $.ajax({
        type: "post",
        url: "fetch/data.php",
        async: false,
        data: {
            action: 'fth-targetbetrecord', target:x
        },
        success: function(response) {
            res = JSON.parse(response);
            if(res.err === false){
                if(!res.data || res.data.length < 1){
                    createEmptyTable('content-betrecord',res.msg);
                }
                else{
                    createTable('content-betrecord',res.data);    
                }
            }
            else{
                throw res.err;
            }
        }
    });
}

/** Setup Statement Form **/
function setupStatementForm(){
    try{
        // fetch & create options
        $.ajax({
            type: "post",
            url: "fetch/data.php",
            async: false,
            data: {
                action: 'fth-statementoptions'
            },
            success: function(response) {
                res = JSON.parse(response);
                if(res.err === false){
                    if(!res.data || res.data.length < 1){
                        throw "!res.data";
                    }
                    statementOptions = res.data;
                    createSelOptions('sel-statementcategory',res.data);
                    document.getElementById('btn-srcstatement').addEventListener('click',srcStatement);
                    
                }
                else{
                    throw res.err;
                }
            }
        });
    }
    catch(e){
        console.log(e);
    }
}

/** Change Statement Platform When Category Options Changed **/
// x = select value
function chgStatementPlatform(x){
    createSelOptions('sel-statementplatform',statementOptions[x].platform);
}


/** Submit request for statement record & create table**/
function srcStatement(){
    $.ajax({
        type: "post",
        url: "fetch/data.php",
        async: false,
        data: {
            action: 'fth-statementrecord'
        },
        success: function(response) {
            res = JSON.parse(response);
            if(res.err === false){
                if(!res.data || res.data.length < 1){
                    createEmptyTable('content-statement',res.msg);
                }
                else{
                    createTable('content-statement',res.data);    
                }
            }
        }
    });
}


//** Fetch All Turnover Data, Show Filter & Create Table **/
function srcTurnover(){
    $.ajax({
        type: "post",
        url: "fetch/data.php",
        async: false,
        data: {
            action: 'fth-turnover'
        },
        success: function(response) {
            res = JSON.parse(response);
            if(res.err === false){
                if(res.data){
                    turnover = res.data;
                    let parent = document.getElementById('content-turnover'), container = parent.querySelectorAll('.filter-options')[0];
                    container.style.display = 'flex';
                    container.querySelectorAll('.option.check-all')[0].click();
                }
            }
        }
    });
}

/** Pre-set all inp-date **/
function preSetDate(){
    try{
        let date = new Date(),
        month = (date.getMonth()+1) < 10 ? (date.getMonth()+1).toString().padStart(2, '0') : (date.getMonth()+1),
        day = date.getDate() < 10 ? date.getDate().toString().padStart(2, '0') : date.getDate();
        current = date.getFullYear()+"-"+month+"-"+day;
        document.querySelectorAll('.inp-date').forEach((x)=>{
            x.setAttribute('value',current);
            x.addEventListener('change',()=>{checkDate(x)});
        });
    }
    catch(e){
        console.log(e);
    }
}


/** Check Date **/
// x = inp-date
function checkDate(x){
    let date = new Date(Date.parse(x.value)), att = x.getAttribute('att');
    if(!att){ return false }
    let parent = x.parentNode.closest('.multi-date'), target;
    if(att == "start"){
        target =  parent.querySelectorAll('.inp-date')[1];
        if(Date.parse(target.value) < date){
            target.value = x.value;
        }
    }
    else if(att == "end"){
        target =  parent.querySelectorAll('.inp-date')[0];
        if(Date.parse(target.value) > date){
            target.value = x.value;
        }
    }
    else{
        return false;
    }
}

/** Fire preSetDate **/
preSetDate();

// Set previous page
const urlParams = new URLSearchParams(window.location.search);
try{
    if(document.getElementById('prev-page')){
        let target = document.getElementById('prev-page');
        if(urlParams.get('prev')){
            target.setAttribute('onclick',"location.href='"+urlParams.get('prev')+".php'");
        }
        else{
            target.setAttribute('onclick',"location.href='index.php'");
        } 
    }
}
catch(e){
    console.log(e);
}


// Show / Hide the target element
function showHiddenElement(){
    try{
        const targetid = this.getAttribute('target'),
        target = document.getElementById(targetid);
        if(target.classList.contains('hide')){
            target.classList.remove('hide');
        }
        else{
            target.classList.add('hide');
        }
    }
    catch(e){
        console.log(e)
    }
}

document.addEventListener('DOMContentLoaded',()=>{
    // Show Hidden Items  
    document.querySelectorAll('.elmt-toggle').forEach((x)=>{
        x.addEventListener('click',showHiddenElement);
    });

    try{
        document.getElementById('download-app').addEventListener('click',()=>{
            window.open('download_app', '_blank');
        });

        document.getElementById('close-download').addEventListener('click',()=>{
            document.getElementById('download-wrapper').classList.add('hide');
        });
    }
    catch(e){
        console.log('...');
    }
});

function toggleNotice(x){
    let parent = x.closest('.notice-box');
    if(parent.classList.contains('minimize')){
        parent.classList.remove('minimize');
    }
    else{
        parent.classList.add('minimize');
    }
}

function setSwal(status, msg, time){
    status = status == 1 ? 'success' : 'warning';
    new swal({
        timer: time,
        text: msg,
        buttons: {
            cancel: false,
            confirm: false
        },
        title: "",
        icon: status,
    });
}
try{
    document.getElementById('confirm-burn').addEventListener('change',(event)=>{
        const btn = document.getElementById('btn-burn');
        if (event.target.checked) {
            btn.disabled = false;
        } else {
            // Checkbox is unchecked
            btn.disabled = true;
        }
    });
}
catch(e){
    //do nth
}
