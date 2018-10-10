let quiz;
let quizSerie = [];
let waitForAnswer = false;
let questionList = [];
let answerList = [];
let currentQuestion = "01";
let allThemesNames = ["alimentation", "test"];
let allThemes = ["quizA1", "quizA2"];
const quizA1 = 
{
	question01: 
	{
		intro: "« Il est très important de prendre un bon petit déjeuner le matin ! » Aujourd’hui, tu peux choisir ce que tu prends ! Que choisis-tu ?",
		// number in % from background size
		imageSrc: "assets/img/alim_bg01.svg",
	    sizeOrigin: 4,
	    xOrigin: 60,
	    yOrigin: 32,
	    tag: document.getElementById("questionButton01"),
	    question: "Que vas-tu manger ?",
	    proposition01: 
	    {
	    	proposition: "Choco bols",
	    	imageSrc: "assets/img/fruit_pomme.png",
	    	stats_environnement: 0,
	    	stats_sante: 0,
	    	stats_social: 1
	    },
	    proposition02: 
	    {
	    	proposition: "Pain + confiture maison",
	    	imageSrc: "assets/img/fruit_banane.png",
	    	stats_environnement: 2,
	    	stats_sante: 1,
	    	stats_social: 1
	    },	   
	    proposition03: 
	    {
	    	proposition: "Viennoiseries du supermarché",
	    	imageSrc: "assets/img/fruit_kiwi.png",
	    	stats_environnement: 1,
	    	stats_sante: 0,
	    	stats_social: 1
	    }
	},
	question02: 
	{
		// number in % from background size
		imageSrc: "assets/img/alim_bg01.svg",
	    sizeOrigin: 4,
	    xOrigin: 70,
	    yOrigin: 50,
	   	tag: document.getElementById("questionButton02"),
	    question: "Que bois-tu le matin ?",
	    proposition01: 
	    {
	    	proposition: "Du lait bio",
	    	imageSrc: "assets/img/fruit_kiwi.png",
	    	stats_environnement: 2,
	    	stats_sante: 1,
	    	stats_social: 1
	    },
	    proposition02: 
	    {
	    	proposition: "Du jus d'orange pressé",
	    	imageSrc: "assets/img/fruit_banane.png",
	    	stats_environnement: 1,
	    	stats_sante: 2,
	    	stats_social: 1
	    },	   
	    proposition03: 
	    {
	    	proposition: "Du soda",
	    	imageSrc: "assets/img/fruit_pomme.png",
	    	stats_environnement: 0,
	    	stats_sante: 0,
	    	stats_social: 0
	    }
	},
	question03: 
	{
		// number in % from background size
		imageSrc: "assets/img/alim_bg01.svg",
	    sizeOrigin: 4,
	    xOrigin: 50,
	    yOrigin: 45,
	   	tag: document.getElementById("questionButton03"),
	    question: "Qu’emmènes-tu comme collation pour l’école ?",
	    proposition01: 
	    {
	    	proposition: "Une barre chocolatée",
	    	imageSrc: "assets/img/fruit_banane.png",
	    	stats_environnement: 0,
	    	stats_sante: 0,
	    	stats_social: 0
	    },
	    proposition02: 
	    {
	    	proposition: "Une pomme",
	    	imageSrc: "assets/img/fruit_kiwi.png",
	    	stats_environnement: 2,
	    	stats_sante: 2,
	    	stats_social: 1
	    },	   
	    proposition03: 
	    {
	    	proposition: "Gâteau fait maison",
	    	imageSrc: "assets/img/fruit_pomme.png",
	    	stats_environnement: 1,
	    	stats_sante: 1,
	    	stats_social: 2
	    }
	}
};
const quizA2 = 
{
	question01: 
	{
		intro: "« C'est l'heure du souper ! »",
		// number in % from background size
		imageSrc: "assets/img/alim_bg01.svg",
	    sizeOrigin: 4,
	    xOrigin: 30,
	    yOrigin: 25,
	    tag: document.getElementById("questionButton01"),
	    question: "Aujourd’hui, c’est jour de poisson ! Vous allez manger",
	    proposition01: 
	    {
	    	proposition: "Du poisson pané",
	    	imageSrc: "assets/img/fruit_pomme.png",
	    	stats_environnement: 0,
	    	stats_sante: 0,
	    	stats_social: 0
	    },
	    proposition02: 
	    {
	    	proposition: "Du saumon élevé en Ecosse",
	    	imageSrc: "assets/img/fruit_banane.png",
	    	stats_environnement: 0,
	    	stats_sante: 1,
	    	stats_social: 0
	    },	   
	    proposition03: 
	    {
	    	proposition: "Du poisson issu de la pêche durable",
	    	imageSrc: "assets/img/fruit_kiwi.png",
	    	stats_environnement: 2,
	    	stats_sante: 2,
	    	stats_social: 2
	    }
	},
	question02: 
	{
		// number in % from background size
		imageSrc: "assets/img/alim_bg01.svg",
	    sizeOrigin: 4,
	    xOrigin: 65,
	    yOrigin: 60,
	   	tag: document.getElementById("questionButton02"),
	    question: "Miam des fruits! Que choisis-tu pour le desert?",
	    proposition01: 
	    {
	    	proposition: "Des fraises dans un ravier carton",
	    	imageSrc: "assets/img/fruit_kiwi.png",
	    	stats_environnement: 1,
	    	stats_sante: 2,
	    	stats_social: 1
	    },
	    proposition02: 
	    {
	    	proposition: "Des poires en vrac",
	    	imageSrc: "assets/img/fruit_banane.png",
	    	stats_environnement: 2,
	    	stats_sante: 2,
	    	stats_social: 1
	    },	   
	    proposition03: 
	    {
	    	proposition: "Des tranches d'ananas en conserve",
	    	imageSrc: "assets/img/fruit_pomme.png",
	    	stats_environnement: 0,
	    	stats_sante: 0,
	    	stats_social: 1
	    }
	},
	question03: 
	{
		// number in % from background size
		imageSrc: "assets/img/alim_bg01.svg",
	    sizeOrigin: 4,
	    xOrigin: 40,
	    yOrigin: 70,
	   	tag: document.getElementById("questionButton03"),
	    question: "À la tv, on parle des agriculteurs brésiliens qui ne sont pas contents parce qu’ils ont faim. Que fais-tu ?",
	    proposition01: 
	    {
	    	proposition: "Tu changes de chaine parce que tu n’y comprends rien (c’est un problème de grands !)",
	    	imageSrc: "assets/img/fruit_banane.png",
	    	stats_environnement: 0,
	    	stats_sante: 0,
	    	stats_social: 0
	    },
	    proposition02: 
	    {
	    	proposition: "Tu poses des questions",
	    	imageSrc: "assets/img/fruit_kiwi.png",
	    	stats_environnement: 2,
	    	stats_sante: 2,
	    	stats_social: 2
	    },	   
	    proposition03: 
	    {
	    	proposition: "Tu demandes à tes parents d’envoyer de l’argent aux agriculteurs pour qu’ils ne soient plus pauvres",
	    	imageSrc: "assets/img/fruit_pomme.png",
	    	stats_environnement: 1,
	    	stats_sante: 1,
	    	stats_social: 1
	    }
	}
};
const quizA3 = 
{
	question01: 
	{
		intro: "« Tu pars faire les courses avec les parents. Tu t’emmitoufles dans ton manteau parce qu’il fait très froid en ce mois de décembre. Que mettez-vous dans le caddie ? »",
		// number in % from background size
		imageSrc: "assets/img/alim_bg01.svg",
	    sizeOrigin: 4,
	    xOrigin: 30,
	    yOrigin: 30,
	    tag: document.getElementById("questionButton01"),
	    question: "Au rayon boucherie, que préfères-tu ?",
	    proposition01: 
	    {
	    	proposition: "Du boeuf",
	    	imageSrc: "assets/img/fruit_pomme.png",
	    	stats_environnement: 0,
	    	stats_sante: 1,
	    	stats_social: 0
	    },
	    proposition02: 
	    {
	    	proposition: "Du poulet",
	    	imageSrc: "assets/img/fruit_banane.png",
	    	stats_environnement: 1,
	    	stats_sante: 2,
	    	stats_social: 0
	    },	   
	    proposition03: 
	    {
	    	proposition: "Un burger végé",
	    	imageSrc: "assets/img/fruit_kiwi.png",
	    	stats_environnement: 1,
	    	stats_sante: 2,
	    	stats_social: 1
	    }
	},
	question02: 
	{
		// number in % from background size
		imageSrc: "assets/img/alim_bg01.svg",
	    sizeOrigin: 4,
	    xOrigin: 50,
	    yOrigin: 75,
	   	tag: document.getElementById("questionButton02"),
	    question: "Au magain, tu vois les bouteilles d'eau dont tu as vu la pub le matin à la tv...",
	    proposition01: 
	    {
	    	proposition: "Tu insistes auprès de tes parents pour les acheter",
	    	imageSrc: "assets/img/fruit_kiwi.png",
	    	stats_environnement: 0,
	    	stats_sante: 2,
	    	stats_social: 1
	    },
	    proposition02: 
	    {
	    	proposition: "Tu ne prends pas tu as ta gourde à la maison",
	    	imageSrc: "assets/img/fruit_banane.png",
	    	stats_environnement: 2,
	    	stats_sante: 2,
	    	stats_social: 1
	    },	   
	    proposition03: 
	    {
	    	proposition: "Tu achètes un bidon de 5l comme d'habitude",
	    	imageSrc: "assets/img/fruit_pomme.png",
	    	stats_environnement: 1,
	    	stats_sante: 2,
	    	stats_social: 1
	    }
	},
	question03: 
	{
		// number in % from background size
		imageSrc: "assets/img/alim_bg01.svg",
	    sizeOrigin: 4,
	    xOrigin: 60,
	    yOrigin: 20,
	   	tag: document.getElementById("questionButton03"),
	    question: "Tu as un petit creux, tes parents te laissent choisir un produit. Lequel choisis-tu ?",
	    proposition01: 
	    {
	    	proposition: "Des chips",
	    	imageSrc: "assets/img/fruit_banane.png",
	    	stats_environnement: 0,
	    	stats_sante: 0,
	    	stats_social: 0
	    },
	    proposition02: 
	    {
	    	proposition: "Des bonbons",
	    	imageSrc: "assets/img/fruit_kiwi.png",
	    	stats_environnement: 0,
	    	stats_sante: 0,
	    	stats_social: 0
	    },	   
	    proposition03: 
	    {
	    	proposition: "Du chocolat équitable",
	    	imageSrc: "assets/img/fruit_pomme.png",
	    	stats_environnement: 0,
	    	stats_sante: 1,
	    	stats_social: 2
	    },
	    openQuestion: "Question Ouverte par Défaut!"
	}
};