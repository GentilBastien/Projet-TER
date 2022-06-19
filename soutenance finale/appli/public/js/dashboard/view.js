
/**
 * Creates a topic visual element displayed as a row in the topics table.
 * @param {Topic} topic the topic element containing the informations to display.
 */
createTopicView = function (topic) {
    let tr = document.createElement('tr');
    let td1 = document.createElement('td');
    let td2 = document.createElement('td');
    let td3 = document.createElement('td');
    let td4 = document.createElement('td');
    td1.innerHTML = topic["conversational"];
    td2.innerHTML = topic["nbCompleted"];
    td3.innerHTML = topic["nbRemaining"];
    td4.innerHTML = topic["nbTotal"];
    tr.appendChild(td1);
    tr.appendChild(td2);
    tr.appendChild(td3);
    tr.appendChild(td4);
    if (topic["isCompleted"])
        tr.setAttribute('class', 'completed');
    DOM_topics_tbody.appendChild(tr)
}


/**
 * Creates an expert visual element displayed as a row in the experts table.
 * @param {Expert} expert the expert element containing the informations to display.
 */
createExpertView = function (expert) {
    let tr = document.createElement('tr');
    let td1 = document.createElement('td');
    let td2 = document.createElement('td');
    let td3 = document.createElement('td');
    let td4 = document.createElement('td');
    td1.innerHTML = expert["id"];
    td2.innerHTML = expert["nbCompleted"];
    td3.innerHTML = expert["nbRemaining"];
    td4.innerHTML = expert["nbTotal"];
    tr.appendChild(td1);
    tr.appendChild(td2);
    tr.appendChild(td3);
    tr.appendChild(td4);
    if (expert["isDone"])
        tr.setAttribute('class', 'completed');
    DOM_experts_tbody.appendChild(tr);
}

/**
 * Removes all the lines from the table(s)
 */
 let emptyTopicTable = function () {
    while (DOM_topics_tbody.firstChild) {
        DOM_topics_tbody.removeChild(DOM_topics_tbody.lastChild);
    }
}

/**
 * Removes all the lines from the table(s)
 */
let emptyExpertView = function () {
    while (DOM_experts_tbody.firstChild) {
        DOM_experts_tbody.removeChild(DOM_experts_tbody.lastChild);
    }
}



//--------------
//-----DOM------
//--------------

/**
 * Gets the <tbody> of the topics table.
 */
const DOM_topics_tbody = document.getElementById("topicsTable");

/**
* Gets the <tbody> of the experts table.
*/
const DOM_experts_tbody = document.getElementById("expertsTable");
