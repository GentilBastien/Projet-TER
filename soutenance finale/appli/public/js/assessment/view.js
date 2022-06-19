/**
 * Put all the span elements in parameter to the view.
 * Add a link to a WordTag object to every single span element.
 * @param {String} span_nodes The list of all span elements.
 */
setSpans = function (span_nodes) {
    let length = span_nodes.length;
    for (let i = 0; i < length; i++) {
        span_nodes[0].wordtag = WordTags[Object.keys(WordTags)[0]]; //sets default WordTag
        DOM_text.appendChild(span_nodes[0]); //appendChild removes the element that's why we keep using index 0.
    }
}

/**
 * Updates word annotation of this DOM span.
 * @param {String} DOM_span The DOM of a clickable span element.
 */
updateSpanView = function (DOM_span) {
    /**
     * reset all clickable spans.
     */
    for (let i = 0; i < DOM_list_clicspans.length; i++)
        DOM_list_clicspans[i].setAttribute("class", "clic " + DOM_list_clicspans[i].wordtag.tagName);
    /**
     * Set the current selected one as selected
     */
    DOM_span.setAttribute("class", "clic selected " + DOM_span.wordtag.tagName);

    DOM_span.style.backgroundColor = DOM_span.wordtag.color;
}

/**
 * Sets the next tag to the clickable span in parameter. The next
 * wordtag is looping over the list of wordtags without going back
 * to the NOT_REVIEWED tag.
 * @param {String} DOM_span The DOM of a clickable span element.
 */
setNextState = function (DOM_span) {
    if (DOM_span.wordtag.value < 0) {
        DOM_span.wordtag = firstWordTagWithPositiveValue();
        return DOM_span.wordtag;
    }

    let arr = Object.values(WordTags);
    if (DOM_span.wordtag == WordTagWithMaxValue()) {
        DOM_span.wordtag = arr[1];
        return DOM_span.wordtag;
    }

    let idx = arr.indexOf(DOM_span.wordtag);
    DOM_span.wordtag = arr[++idx];
    return DOM_span.wordtag;
}

firstWordTagWithPositiveValue = function () {
    let arr = Object.values(WordTags);
    let len = arr.length;
    for (let i = 0; i < len; i++)
        if (arr[i].value >= 0)
            return arr[i];
    throw "There were no WordTag with positive value!";
}

WordTagWithMaxValue = function () {
    let arr = Object.values(WordTags);
    return arr[arr.length - 1];
}

//--------------
//-----DOM------
//--------------

/**
 * Gets the <p> containing the abstract.
 */
const DOM_text = document.getElementById("abstract");

/**
 * Gets the radio buttons grouped by name "global" and
 * sets the first one as checked.
 */
const DOM_radio = document.getElementsByName("global");
DOM_radio[0].checked = "checked";

/**
 * Gets the hidden input sending data.
 */
const DOM_data = document.getElementsByClassName("data");
