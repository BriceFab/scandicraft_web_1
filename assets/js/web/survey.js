import axios from "./libs/axios";

function onSurveyAnswerClick(answer_list_id, survey_id, user_id) {
    axios.post('survey_answer', {
        "answer": answer_list_id,
        "survey": survey_id,
        "user": user_id
    }).then((success) => {
        alert('success');
    }).catch((error) => {
        alert('error');
    });
}

//export fonction to window dom
window.onSurveyAnswerClick = onSurveyAnswerClick;