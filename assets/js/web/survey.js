import axios from 'axios';

function onSurveyAnswerClick(base_path, answer_list_id, survey_id, user_id) {
    alert("answer " + base_path + " " + answer_list_id + " " + survey_id + " " + user_id);
}

//export fonction to window dom
window.onSurveyAnswerClick = onSurveyAnswerClick;