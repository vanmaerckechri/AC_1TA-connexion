let quiz;
let quizSerie = [];
let waitForAnswer = false;
let questionList = [];
let answerList = [];
let currentQuestion = "01";
const quizA1 = 
{
	question01: 
	{
		questionNumber: 1,
		// number in % from background size
		imageSrc: "assets/img/alim_bg01.jpg",
	    sizeOrigin: 4,
	    xOrigin: 30,
	    yOrigin: 30,
	    tag: document.getElementById("questionButton01"),
	    question: "Quel fruit manges-tu pour ton petit déjeuner ?",
	    proposition01: 
	    {
	    	proposition: "pomme",
	    	imageSrc: "assets/img/fruit_pomme.png",
	    	stats_environnement: 3,
	    	stats_sante: 3,
	    	stats_social: 3
	    },
	    proposition02: 
	    {
	    	proposition: "banane",
	    	imageSrc: "assets/img/fruit_banane.png",
	    	stats_environnement: 2,
	    	stats_sante: 2,
	    	stats_social: 2
	    },	   
	    proposition03: 
	    {
	    	proposition: "kiwi",
	    	imageSrc: "assets/img/fruit_kiwi.png",
	    	stats_environnement: 1,
	    	stats_sante: 2,
	    	stats_social: 2
	    }
	},
	question02: 
	{
		questionNumber: 2,
		// number in % from background size
		imageSrc: "assets/img/alim_bg01.jpg",
	    sizeOrigin: 4,
	    xOrigin: 50,
	    yOrigin: 75,
	   	tag: document.getElementById("questionButton02"),
	    question: "Quel fruit manges-tu pour ton diner ?",
	    proposition01: 
	    {
	    	proposition: "kiwi",
	    	imageSrc: "assets/img/fruit_kiwi.png",
	    	stats_environnement: 1,
	    	stats_sante: 2,
	    	stats_social: 2
	    },
	    proposition02: 
	    {
	    	proposition: "banane",
	    	imageSrc: "assets/img/fruit_banane.png",
	    	stats_environnement: 2,
	    	stats_sante: 2,
	    	stats_social: 2
	    },	   
	    proposition03: 
	    {
	    	proposition: "pomme",
	    	imageSrc: "assets/img/fruit_pomme.png",
	    	stats_environnement: 3,
	    	stats_sante: 3,
	    	stats_social: 3
	    }
	},
	question03: 
	{
		questionNumber: 3,
		// number in % from background size
		imageSrc: "assets/img/alim_bg01.jpg",
	    sizeOrigin: 4,
	    xOrigin: 60,
	    yOrigin: 20,
	   	tag: document.getElementById("questionButton03"),
	    question: "Quel fruit manges-tu pour ton souper ?",
	    proposition01: 
	    {
	    	proposition: "banane",
	    	imageSrc: "assets/img/fruit_banane.png",
	    	stats_environnement: 2,
	    	stats_sante: 2,
	    	stats_social: 2
	    },
	    proposition02: 
	    {
	    	proposition: "kiwi",
	    	imageSrc: "assets/img/fruit_kiwi.png",
	    	stats_environnement: 1,
	    	stats_sante: 2,
	    	stats_social: 2
	    },	   
	    proposition03: 
	    {
	    	proposition: "pomme",
	    	imageSrc: "assets/img/fruit_pomme.png",
	    	stats_environnement: 3,
	    	stats_sante: 3,
	    	stats_social: 3
	    }
	}
};
const quizA2 = 
{
	question01: 
	{
		questionNumber: 4,
		// number in % from background size
		imageSrc: "assets/img/alim_bg01.jpg",
	    sizeOrigin: 4,
	    xOrigin: 20,
	    yOrigin: 15,
	    tag: document.getElementById("questionButton01"),
	    question: "Serie A - Question 04",
	    proposition01: 
	    {
	    	proposition: "pomme",
	    	imageSrc: "assets/img/fruit_pomme.png",
	    	stats_environnement: 3,
	    	stats_sante: 3,
	    	stats_social: 3
	    },
	    proposition02: 
	    {
	    	proposition: "banane",
	    	imageSrc: "assets/img/fruit_banane.png",
	    	stats_environnement: 2,
	    	stats_sante: 2,
	    	stats_social: 2
	    },	   
	    proposition03: 
	    {
	    	proposition: "kiwi",
	    	imageSrc: "assets/img/fruit_kiwi.png",
	    	stats_environnement: 1,
	    	stats_sante: 2,
	    	stats_social: 2
	    }
	},
	question02: 
	{
		questionNumber: 5,
		// number in % from background size
		imageSrc: "assets/img/alim_bg01.jpg",
	    sizeOrigin: 4,
	    xOrigin: 35,
	    yOrigin: 20,
	   	tag: document.getElementById("questionButton02"),
	    question: "Serie A - Question 05",
	    proposition01: 
	    {
	    	proposition: "kiwi",
	    	imageSrc: "assets/img/fruit_kiwi.png",
	    	stats_environnement: 1,
	    	stats_sante: 2,
	    	stats_social: 2
	    },
	    proposition02: 
	    {
	    	proposition: "banane",
	    	imageSrc: "assets/img/fruit_banane.png",
	    	stats_environnement: 2,
	    	stats_sante: 2,
	    	stats_social: 2
	    },	   
	    proposition03: 
	    {
	    	proposition: "pomme",
	    	imageSrc: "assets/img/fruit_pomme.png",
	    	stats_environnement: 3,
	    	stats_sante: 3,
	    	stats_social: 3
	    }
	},
	question03: 
	{
		questionNumber: 6,
		// number in % from background size
		imageSrc: "assets/img/alim_bg01.jpg",
	    sizeOrigin: 4,
	    xOrigin: 90,
	    yOrigin: 10,
	   	tag: document.getElementById("questionButton03"),
	    question: "Serie A - Question 06",
	    proposition01: 
	    {
	    	proposition: "banane",
	    	imageSrc: "assets/img/fruit_banane.png",
	    	stats_environnement: 2,
	    	stats_sante: 2,
	    	stats_social: 2
	    },
	    proposition02: 
	    {
	    	proposition: "kiwi",
	    	imageSrc: "assets/img/fruit_kiwi.png",
	    	stats_environnement: 1,
	    	stats_sante: 2,
	    	stats_social: 2
	    },	   
	    proposition03: 
	    {
	    	proposition: "pomme",
	    	imageSrc: "assets/img/fruit_pomme.png",
	    	stats_environnement: 3,
	    	stats_sante: 3,
	    	stats_social: 3
	    }
	}
};
const quizA3 = 
{
	question01: 
	{
		questionNumber: 7,
		// number in % from background size
		imageSrc: "assets/img/alim_bg01.jpg",
	    sizeOrigin: 4,
	    xOrigin: 30,
	    yOrigin: 30,
	    tag: document.getElementById("questionButton01"),
	    question: "Serie A - Question 07",
	    proposition01: 
	    {
	    	proposition: "pomme",
	    	imageSrc: "assets/img/fruit_pomme.png",
	    	stats_environnement: 3,
	    	stats_sante: 3,
	    	stats_social: 3
	    },
	    proposition02: 
	    {
	    	proposition: "banane",
	    	imageSrc: "assets/img/fruit_banane.png",
	    	stats_environnement: 2,
	    	stats_sante: 2,
	    	stats_social: 2
	    },	   
	    proposition03: 
	    {
	    	proposition: "kiwi",
	    	imageSrc: "assets/img/fruit_kiwi.png",
	    	stats_environnement: 1,
	    	stats_sante: 2,
	    	stats_social: 2
	    }
	},
	question02: 
	{
		questionNumber: 8,
		// number in % from background size
		imageSrc: "assets/img/alim_bg01.jpg",
	    sizeOrigin: 4,
	    xOrigin: 50,
	    yOrigin: 75,
	   	tag: document.getElementById("questionButton02"),
	    question: "Serie A - Question 08",
	    proposition01: 
	    {
	    	proposition: "kiwi",
	    	imageSrc: "assets/img/fruit_kiwi.png",
	    	stats_environnement: 1,
	    	stats_sante: 2,
	    	stats_social: 2
	    },
	    proposition02: 
	    {
	    	proposition: "banane",
	    	imageSrc: "assets/img/fruit_banane.png",
	    	stats_environnement: 2,
	    	stats_sante: 2,
	    	stats_social: 2
	    },	   
	    proposition03: 
	    {
	    	proposition: "pomme",
	    	imageSrc: "assets/img/fruit_pomme.png",
	    	stats_environnement: 3,
	    	stats_sante: 3,
	    	stats_social: 3
	    }
	},
	question03: 
	{
		questionNumber: 9,
		// number in % from background size
		imageSrc: "assets/img/alim_bg01.jpg",
	    sizeOrigin: 4,
	    xOrigin: 60,
	    yOrigin: 20,
	   	tag: document.getElementById("questionButton03"),
	    question: "Serie A - Question 09",
	    proposition01: 
	    {
	    	proposition: "banane",
	    	imageSrc: "assets/img/fruit_banane.png",
	    	stats_environnement: 2,
	    	stats_sante: 2,
	    	stats_social: 2
	    },
	    proposition02: 
	    {
	    	proposition: "kiwi",
	    	imageSrc: "assets/img/fruit_kiwi.png",
	    	stats_environnement: 1,
	    	stats_sante: 2,
	    	stats_social: 2
	    },	   
	    proposition03: 
	    {
	    	proposition: "pomme",
	    	imageSrc: "assets/img/fruit_pomme.png",
	    	stats_environnement: 3,
	    	stats_sante: 3,
	    	stats_social: 3
	    }
	}
};