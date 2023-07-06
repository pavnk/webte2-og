function drawSearchElement(){
    var mainDiv = document.getElementById("filter-div");

    var filterField = document.createElement("select");
    filterField.setAttribute("id", "filter-field");
    mainDiv.appendChild(filterField);

    var optionOne = document.createElement("option");
    filterField.appendChild(optionOne);

    var optionTwo = document.createElement("option");
    optionTwo.innerHTML = "Time";
    optionTwo.setAttribute("value", "time");
    filterField.appendChild(optionTwo);

    var optionThree = document.createElement("option");
    optionThree.innerHTML = "Login";
    optionThree.setAttribute("value", "login");
    filterField.appendChild(optionThree);

    var optionFour = document.createElement("option");
    optionFour.innerHTML = "Action";
    optionFour.setAttribute("value", "action");
    filterField.appendChild(optionFour);

    var optionFive = document.createElement("option");
    optionFive.innerHTML = "Type";
    optionFive.setAttribute("value", "type");
    filterField.appendChild(optionFive);

    var filterType = document.createElement("select");
    filterType.setAttribute("id","filter-type");
    mainDiv.appendChild(filterType);

    var filterOptionOne = document.createElement("option");
    filterOptionOne.innerHTML = "=";
    filterOptionOne.setAttribute("value","=");
    filterType.appendChild(filterOptionOne);

    var filterOptionTwo = document.createElement("option");
    filterOptionTwo.innerHTML = "<";
    filterOptionTwo.setAttribute("value","<");
    filterType.appendChild(filterOptionTwo);

    var filterOptionThree = document.createElement("option");
    filterOptionThree.innerHTML = "<=";
    filterOptionThree.setAttribute("value","<=");
    filterType.appendChild(filterOptionThree);

    var filterOptionFour = document.createElement("option");
    filterOptionFour.innerHTML = ">";
    filterOptionFour.setAttribute("value",">");
    filterType.appendChild(filterOptionFour);

    var filterOptionFive = document.createElement("option");
    filterOptionFive.innerHTML = ">=";
    filterOptionFive.setAttribute("value",">=");
    filterType.appendChild(filterOptionFive);

    var filterOptionSix = document.createElement("option");
    filterOptionSix.innerHTML = "!=";
    filterOptionSix.setAttribute("value","!=");
    filterType.appendChild(filterOptionSix);

    var filterInput = document.createElement("input");
    filterInput.setAttribute("id","filter-value");
    filterInput.setAttribute("type","text");
    filterInput.setAttribute("placeholder","value to filter");
    mainDiv.appendChild(filterInput);

    var filterButton = document.createElement("button");
    filterButton.setAttribute("id","filter-clear");
    filterButton.innerHTML = "Clear Filter";
    mainDiv.appendChild(filterButton);

}

function drawAthleteTable(data){
    drawSearchElement();

    //Define variables for input elements
    var fieldEl = document.getElementById("filter-field");
    var typeEl = document.getElementById("filter-type");
    var valueEl = document.getElementById("filter-value");

//Custom filter example
    function customFilter(data){
        return data.car && data.rating < 3;
    }

//Trigger setFilter function with correct parameters
    function updateFilter(){
        var filterVal = fieldEl.options[fieldEl.selectedIndex].value;
        var typeVal = typeEl.options[typeEl.selectedIndex].value;

        var filter = filterVal == "function" ? customFilter : filterVal;

        if(filterVal == "function" ){
            typeEl.disabled = true;
            valueEl.disabled = true;
        }else{
            typeEl.disabled = false;
            valueEl.disabled = false;
        }

        if(filterVal){
            table.setFilter(filter,typeVal, valueEl.value);
        }
    }

//Update filters on value change
    document.getElementById("filter-field").addEventListener("change", updateFilter);
    document.getElementById("filter-type").addEventListener("change", updateFilter);
    document.getElementById("filter-value").addEventListener("keyup", updateFilter);

//Clear filters on "Clear Filters" button click
    document.getElementById("filter-clear").addEventListener("click", function(){
        fieldEl.value = "";
        typeEl.value = "=";
        valueEl.value = "";

        table.clearFilter();
    });

    var tableData=[];
    for(let i=0; i<data.length;++i){
        tableData.push({time: data[i].time, login: data[i].login,action: data[i].action, type: data[i].login_type});
    }


    var table = new Tabulator("#userInfoTable", {
        data:tableData,
        layout:"fitColumns",
        pagination:"local",
        paginationSize:15,
        paginationSizeSelector:[5, 10, 15, 20],
        movableColumns:true,
        paginationCounter:"rows",
        columns:[
            {title:"Time", field:"time"},
            {title:"Login", field:"login"},
            {title:"Action", field:"action"},
            {title:"Type", field:"type"},
        ],
    });

    var tableDiv = document.getElementById("userInfoTable");
}

window.addEventListener("load", function() {
    fetch('../get-data-history.php')
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            drawAthleteTable(data);
        });
},false);