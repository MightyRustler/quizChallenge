<script setup>
import {ref, computed} from 'vue'
import axios from 'axios'

const props = defineProps({ 
    questions: Object,
    quizName: String    
    })

const quizCompleted = ref(false)
const nameCollected = ref(false)
const playerName = ref('')
const currentQuestion = ref(0)
const selectedAnswer = ref(null)
const score = ref(0)
const scoreboardResults = ref([])

const getCurrentQuestion = computed(() => {
    return props.questions[currentQuestion.value]
})

const SetAnswer = e => {    
    score.value += getCurrentQuestion.value.scores[e.target.value][1]
    
    if(currentQuestion.value < props.questions.length - 1){
        currentQuestion.value++
    }else{
        quizCompleted.value = true
    }

}

const UpdateName = e => {
    playerName.value = e.target.value
}

const SubmitName = () => {
    axios({
        method :'post',
        url : '/storeScore',
        data : {
            name : playerName.value,
            score : score.value
        }
    })

    nameCollected.value = true

    axios({
        method :'get',
        url : '/scoreboard'
    }).then(function (response){
        scoreboardResults.value = response.data
    })
}


</script>
<template>
    <main class="app">
        <h1>{{quizName}}</h1>     
        <!-- Quiz question and answer selection -->            
        <section class="quiz" v-if="!quizCompleted">
            <div class="quiz-info">
                <span class="question">{{ getCurrentQuestion.name }}</span>
                <span class="score"> Score {{ score }}</span>
            </div>
            <div class="options">
                <label 
                    v-for="(answer, index) in getCurrentQuestion.scores"
                    :key="index" 
                    class="option">
                    <input 
                        type="radio"
                        :name="getCurrentQuestion.index"
                        :value="index"
                        v-model="getCurrentQuestion.selected"
                        :disabled="getCurrentQuestion.selected"
                        @change="SetAnswer"
                        >
                    <span>{{ answer[0] }}</span>
                </label>
            </div>
        </section>
        <!-- Name collection for the scoreboard -->
        <section v-else-if="!nameCollected">
            <div class="nameCollect">
            <h2>You Have Finished the quiz</h2>
                <h3>Please enter your name</h3>
                <input type="text" @change="UpdateName" placeholder="Enter Name...">
                <button
                @click="SubmitName"
                >Submit</button>
            </div>
        </section>
        <!-- Scoreboard Display -->
        <section v-else>
            <div class="scoreboard">
            <div class="scoreLegend">
                <h2>You Have Finished the quiz</h2>
                <p>Your score is {{ score }}</p>
            </div>
            <table>
            <tr v-for="(result, index) in scoreboardResults">
                <td>{{ result.name }}</td>
                <td>{{ result.score }}</td>
            </tr>
            </table>
            </div>
        </section>
    </main>
</template>

<style>
* {
    margin :0;
    padding :0;
    box-sizing : border-box;
    font-family : 'Montserrat', sans-serif;
}

body{
    background : linear-gradient(320deg, #eb92be, #ffef78, #63c9b4);
    color : #FFF;
}

.app{
    display : flex;
    flex-direction : column;
    align-items : center;
    padding : 2rem;
    min-height : 100vh;
}

h1{
    font-size: 2rem;
    margin-bottom : 2rem;
}

.nameCollect{
    background-color : #382a4b;
    padding: 1rem;
    width : 100%;
    max-width : 640px;
    border-radius : 0.5rem;

}

.scoreboard{
    background-color : #382a4b;
    padding: 1rem;
    width : 100%;
    max-width : 640px;
    border-radius : 0.5rem;
    display : flex;
    align-items: center;
}

.scoreLegend{
    padding-right : 1rem;
}

.scoreboard table{
    width : 40%
}
.scoreboard table tr{
    display : flex;
    justify-content : space-between;
    margin-bottom : 0.25rem;
}


.nameCollect input{
    margin-top : 1rem;
    margin-bottom : 1rem;
    color : #FFF;
    background-color : #382a4b;
    width : 100%;
    height : 2rem;
    border : none;
    border-bottom: 3px solid black;
}

.quiz{
    background-color : #382a4b;
    padding: 1rem;
    width : 100%;
    max-width : 640px;
    border-radius : 0.5rem;
}

.quiz-info{
    display: flex;
    justify-content: space-between;
    margin-bottom : 1rem;
}

.quiz-info .question{
    color : #FFF;
    font-size : 1.25rem;
}

.quiz-info .score{
    color : #FFF;
    font-size :1.25rem;
}

.options{
    margin-bottom : 1rem;
}

.option{
    display :block;
    padding: 1rem;
    background-color: #271C36;
    margin-bottom : 0.5rem;
    border-radius : 0.5rem;
    cursor : pointer;
}

.option :hover{
    background-color : #2d213f;
}

.option.correct{
    background-color : #2cce7d;
}

.option.wrong{
    background-color : #ff5a5f;
}

.option:last-of-type{
    margin-bottom: 0;
}

.option.disabled{
    opacity : 0.5;
}

.option input{
    display : none;
}

.option span{
    text-transform : capitalize;
}

button { 
    appearance :none;
    outline : none;
    border : none;
    cursor : pointer;

    padding : 0.5rem 1rem;
    background-color : #2cce7d;
    color : #2d213f;
    font-weight : 700;
    text-transform : uppercase;
    font-size : 1.25rem;
    border-radius : 0.5rem;
}

button:disabled{
    opacity : 0.5;
}

h2{
    font-size: 2rem;
    margin-bottom: 2rem;
    text-align: center;
}

p{
    color: #8f8f8f;
    font-size : 1.25rem;
    text-align : center;
}
</style>