/**
 * Updates values of the topic table on the experts dashboard
 */
let updateExpertDashboard = function(topics) {
    emptyTopicTable();
    expertTable.setArrayTopic(topics);
    expertTable.arrayTopics.forEach(topic => {
        createTopicView(topic);
    });
}

/**
 * Updates values of the experts table on the admin's dashboard
 */
 let updateAdminDashboard = function(experts, topics) {
    emptyTopicTable();
    emptyExpertView();

    adminTable.setArrayTopic(topics);
    adminTable.setArrayExpert(experts);

    adminTable.arrayTopics.forEach(topic => {
        createTopicView(topic);
    });
    adminTable.arrayExperts.forEach(expert => {
        createExpertView(expert);
    });
}
