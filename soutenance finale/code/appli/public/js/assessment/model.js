class WordTag {
    constructor(value, tagName, color) {
        this.value = value;
        this.tagName = tagName;
        this.color = color;
    }
}

class GlobalTag {
    constructor(value, tagName, color) {
        this.value = value;
        this.tagName = tagName;
        this.color = color;
    }
}

class Assessment {
    constructor(abstract) {
        this.abstract = abstract;
        this.globalTag = undefined;
        this.wordsTag = {};
        this.selectorIndex = 0;
    }

    setGlobalAnnotation(globalTag) {
        this.globalTag = globalTag.value;
    }

    setWordAnnotation(index, wordTag) {
        this.wordsTag[index] = wordTag.value;
    }
}

