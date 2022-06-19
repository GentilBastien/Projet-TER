class Topic {
    constructor(id, conversationnal, nbCompleted, nbRemaining, nbTotal) {
        this.id = id;
        this.conversationnal = conversationnal;
        this.nbCompleted = nbCompleted;
        this.nbRemaining = nbRemaining;
        this.nbTotal = nbTotal;
    }

    isCompleted() {
        return this.nbRemaining == 0;
    }

    updateTopicAdvancement(nbCompleted, nbRemaining, nbTotal) {
        this.nbCompleted = nbCompleted;
        this.nbRemaining = nbRemaining;
        this.nbTotal = nbTotal;
    }
}

class Expert {
    constructor(id, nbCompleted, nbRemaining, nbTotal) {
        this.id = id;
        this.nbCompleted = nbCompleted;
        this.nbRemaining = nbRemaining;
        this.nbTotal = nbTotal;
    }

    isDone() {
        return this.nbRemaining == 0;
    }

    updateExpertAdvancement(nbCompleted, nbRemaining, nbTotal) {
        this.nbCompleted = nbCompleted;
        this.nbRemaining = nbRemaining;
        this.nbTotal = nbTotal;
    }
}

class ExpertTable {
    setArrayTopic(topics) {
        this.arrayTopics = topics;
    }
}

class AdminTable {
    setArrayExpert(experts) {
        this.arrayExperts = experts;
    }
    setArrayTopic(topics) {
        this.arrayTopics = topics;
    }
}

const expertTable = new ExpertTable();
const adminTable = new AdminTable();
