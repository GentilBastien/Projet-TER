let onKeyPressed = function (event) {
    if (event.keyCode == 32) { //looking for Space Bar
        event.preventDefault();
        let DOM_span = DOM_list_clicspans[assessment.selectorIndex];
        if (DOM_span == null) //if we didn't find any clickable span at this index, we return.
            return;
        assessment.setWordAnnotation(assessment.selectorIndex, setNextState(DOM_span, "Word"));
        updateSpanView(DOM_span);
        changeDataValues();
    } else if (event.keyCode == 37) { //looking for left arrow
        if (assessment.selectorIndex == 0)
            return;
        let DOM_span = DOM_list_clicspans[--assessment.selectorIndex];
        updateSpanView(DOM_span);
    } else if (event.keyCode == 39) { //looking for right arrow
        if (assessment.selectorIndex == DOM_list_clicspans.length - 1)
            return;
        let DOM_span = DOM_list_clicspans[++assessment.selectorIndex];
        updateSpanView(DOM_span);
    }
}

/**
 * Callback when clicking a word.
 * @param {*} event The click event.
 */
let onClickWord = function (event) {
    let DOM_span = event.target;
    /**
     * Gets the index of the word we clicked on.
     */
    assessment.selectorIndex = Array.from(DOM_list_clicspans).indexOf(DOM_span);
    /**
     * Update the model. setNextState returns the new state of the word.
     * The returned result is put in the WordTag list of the assessment.
     */
    assessment.setWordAnnotation(assessment.selectorIndex, setNextState(DOM_span, "Word"));
    changeDataValues();
    updateSpanView(DOM_span);
}

/**
 * Callback when clicking a button for the global annotation.
 * @param {*} event The click event.
 */
let onClickGlobal = function (event) {
    /**
     * this refers to the radio button target of the event.
     */
    if (this.checked) {
        assessment.setGlobalAnnotation(GlobalTags[this.id]);
        changeDataValues();
    }
}

/**
 * Transforms the abstract string into a real list of span elements.
 * @param {String} abstract All the span elements of the abstract but in one litteral String.
 */
setAbstract = function (abstract) {
    /**
     * Uses DOMParser to create DOM elements.
     */
    let span_nodes = new DOMParser().parseFromString(abstract, "text/html").getElementsByTagName("span");
    setSpans(span_nodes);
}

/**
 * Encode the results as a JSON.
 * @param {Boolean} next boolean data to put with the other data
 */
stringifyData = function () {
    return "{\"global\":"
        + assessment.globalTag
        + ", \"words\":"
        + JSON.stringify(assessment.wordsTag)
        + "}";
}

/**
 * Changes the values of all hidden input with class=data.
 */
changeDataValues = function () {
    for (let i = 0; i < DOM_data.length; i++)
        DOM_data[i].value = stringifyData();
}

setAbstract(assessment.abstract);
/**
 * get all the clickable spans after setting the abstract and NOT
 * all spans since some are used for blank spaces
 */
const DOM_list_clicspans = document.getElementsByClassName("clic");
/**
 * Apply the possibility for the clickable spans to be clicked with the
 * mouse.
 */
for (let i = 0; i < DOM_list_clicspans.length; i++)
    DOM_list_clicspans[i].addEventListener("click", onClickWord);
updateSpanView(DOM_list_clicspans[assessment.selectorIndex]);
/**
 * Apply possibility to annotate the words with keybinds.
 */
document.onkeydown = onKeyPressed;
/**
 * Add a changeListener to all the radio buttons for global relevance.
 */
for (const radio of DOM_radio)
    radio.addEventListener('change', onClickGlobal);
assessment.setGlobalAnnotation(GlobalTags[DOM_radio[0].id]);
changeDataValues();