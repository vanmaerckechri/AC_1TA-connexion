let quiz;
let quizSerie = [];
let waitForAnswer = false;
let questionList = [];
let answerList = [];
let currentQuestion = "01";
let allThemesNames = ["alimentation"];
let allThemes = ["quizA1"];

// ex.:
// let allThemesNames = ["alimentation", "dechets", "culture"];
// let allThemes = ["quizA1", "quizB1", "quizC1"];
const quizA1 = 
{
	question01: 
	{
		intro: "« Il est très important de prendre un bon petit déjeuner le matin ! » Aujourd’hui, tu peux choisir ce que tu prends ! Que choisis-tu ?",
		// number in % from background size
		image: mainGameQuizABgImages[0],
	    sizeOrigin: 4,
	    xOrigin: 60,
	    yOrigin: 32,
	    tag: document.getElementById("questionButton01"),
	    question: "Que vas-tu manger ?",
	    proposition01: 
	    {
	    	proposition: "Des céréales au chocolat",
	    	image: mainGameQuizA1PropositionsImages[0],
	    	stats_environnement: 0,
	    	stats_sante: 0,
	    	stats_social: 1
	    },
	    proposition02: 
	    {
	    	proposition: "Du pain et de la confiture maison",
	    	image: mainGameQuizA1PropositionsImages[1],
	    	stats_environnement: 2,
	    	stats_sante: 1,
	    	stats_social: 1
	    },	   
	    proposition03: 
	    {
	    	proposition: "Des croissants du supermarché",
	    	image: mainGameQuizA1PropositionsImages[2],
	    	stats_environnement: 1,
	    	stats_sante: 0,
	    	stats_social: 1
	    }
	},
	question02: 
	{
		// number in % from background size
		image: mainGameQuizABgImages[0],
	    sizeOrigin: 4,
	    xOrigin: 70,
	    yOrigin: 50,
	   	tag: document.getElementById("questionButton02"),
	    question: "Que bois-tu le matin ?",
	   	proposition01: 
	    {
	    	proposition: "Du jus d'orange pressé",
	    	image: mainGameQuizA1PropositionsImages[3],
	    	stats_environnement: 1,
	    	stats_sante: 2,
	    	stats_social: 1
	    },	 
	    proposition02: 
	    {
	    	proposition: "Du lait bio",
	    	image: mainGameQuizA1PropositionsImages[4],
	    	stats_environnement: 2,
	    	stats_sante: 1,
	    	stats_social: 1
	    },  
	    proposition03: 
	    {
	    	proposition: "Du soda",
	    	image: mainGameQuizA1PropositionsImages[5],
	    	stats_environnement: 0,
	    	stats_sante: 0,
	    	stats_social: 0
	    }
	},
	question03: 
	{
		// number in % from background size
		image: mainGameQuizABgImages[0],
	    sizeOrigin: 4,
	    xOrigin: 50,
	    yOrigin: 45,
	   	tag: document.getElementById("questionButton03"),
	    question: "Qu’emmènes-tu comme collation pour l’école ?",
	    proposition01: 
	    {
	    	proposition: "Une barre chocolatée",
	    	image: mainGameQuizA1PropositionsImages[6],
	    	stats_environnement: 0,
	    	stats_sante: 0,
	    	stats_social: 0
	    },
	    proposition02: 
	    {
	    	proposition: "Une pomme",
	    	image: mainGameQuizA1PropositionsImages[7],
	    	stats_environnement: 2,
	    	stats_sante: 2,
	    	stats_social: 1
	    },	   
	    proposition03: 
	    {
	    	proposition: "Gâteau fait maison",
	    	image: mainGameQuizA1PropositionsImages[8],
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
		image: mainGameQuizABgImages[1],
	    sizeOrigin: 4,
	    xOrigin: 27,
	    yOrigin: 65,
	    tag: document.getElementById("questionButton01"),
	    question: "Aujourd’hui, c’est jour de poisson ! Vous allez manger",
	    proposition01: 
	    {
	    	proposition: "Du poisson pané",
	    	image: mainGameQuizA2PropositionsImages[0],
	    	stats_environnement: 0,
	    	stats_sante: 0,
	    	stats_social: 0
	    },
	    proposition02: 
	    {
	    	proposition: "Du saumon élevé en Ecosse",
	    	image: mainGameQuizA2PropositionsImages[1],
	    	stats_environnement: 0,
	    	stats_sante: 1,
	    	stats_social: 0
	    },	   
	    proposition03: 
	    {
	    	proposition: "Du poisson issu de la pêche durable",
	    	image: mainGameQuizA2PropositionsImages[2],
	    	stats_environnement: 2,
	    	stats_sante: 2,
	    	stats_social: 2
	    }
	},
	question02: 
	{
		// number in % from background size
		image: mainGameQuizABgImages[1],
	    sizeOrigin: 4,
	    xOrigin: 60,
	    yOrigin: 30,
	   	tag: document.getElementById("questionButton02"),
	    question: "Miam des fruits! Que choisis-tu pour le dessert?",
	    proposition01: 
	    {
	    	proposition: "Des fraises dans un ravier carton",
	    	image: mainGameQuizA2PropositionsImages[3],
	    	stats_environnement: 1,
	    	stats_sante: 2,
	    	stats_social: 1
	    }, 
	    proposition02: 
	    {
	    	proposition: "Des tranches d'ananas en conserve",
	    	image: mainGameQuizA2PropositionsImages[4],
	    	stats_environnement: 0,
	    	stats_sante: 0,
	    	stats_social: 1
	    },
	   	proposition03: 
	    {
	    	proposition: "Des poires en vrac",
	    	image: mainGameQuizA2PropositionsImages[5],
	    	stats_environnement: 2,
	    	stats_sante: 2,
	    	stats_social: 1
	    }  
	},
	question03: 
	{
		// number in % from background size
		image: mainGameQuizABgImages[1],
	    sizeOrigin: 4,
	    xOrigin: 25,
	    yOrigin: 39,
	   	tag: document.getElementById("questionButton03"),
	    question: "À la tv, on parle des agriculteurs brésiliens qui ne sont pas contents parce qu’ils ont faim. Que fais-tu ?",
	   	proposition01: 
	    {
	    	proposition: "Tu poses des questions",
	    	image: mainGameQuizA2PropositionsImages[6],
	    	stats_environnement: 2,
	    	stats_sante: 2,
	    	stats_social: 2
	    },	   
	    proposition02: 
	    {
	    	proposition: "Tu changes de chaîne parce que tu n’y comprends rien (c’est un problème de grands !)",
	    	image: mainGameQuizA2PropositionsImages[7],
	    	stats_environnement: 0,
	    	stats_sante: 0,
	    	stats_social: 0
	    },
	    proposition03: 
	    {
	    	proposition: "Tu demandes à tes parents d’envoyer de l’argent aux agriculteurs pour qu’ils ne soient plus pauvres",
	    	image: mainGameQuizA2PropositionsImages[8],
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
		image: mainGameQuizABgImages[2],
	    sizeOrigin: 4,
	    xOrigin: 74,
	    yOrigin: 45,
	    tag: document.getElementById("questionButton01"),
	    question: "Au rayon boucherie, que préfères-tu ?",
	    proposition01: 
	    {
	    	proposition: "Du boeuf",
	    	image: mainGameQuizA3PropositionsImages[0],
	    	stats_environnement: 0,
	    	stats_sante: 1,
	    	stats_social: 0
	    },
	    proposition02: 
	    {
	    	proposition: "Du poulet",
	    	image: mainGameQuizA3PropositionsImages[1],
	    	stats_environnement: 1,
	    	stats_sante: 2,
	    	stats_social: 0
	    },	   
	    proposition03: 
	    {
	    	proposition: "Un burger végé",
	    	image: mainGameQuizA3PropositionsImages[2],
	    	stats_environnement: 1,
	    	stats_sante: 2,
	    	stats_social: 1
	    }
	},
	question02: 
	{
		// number in % from background size
		image: mainGameQuizABgImages[2],
	    sizeOrigin: 4,
	    xOrigin: 53,
	    yOrigin: 41,
	   	tag: document.getElementById("questionButton02"),
	    question: "Au magasin, tu vois les bouteilles d'eau dont tu as vu la pub le matin à la tv...",
	    proposition01: 
	    {
	    	proposition: "Tu insistes auprès de tes parents pour les acheter",
	    	image: mainGameQuizA3PropositionsImages[3],
	    	stats_environnement: 0,
	    	stats_sante: 2,
	    	stats_social: 1
	    },
	    proposition02: 
	    {
	    	proposition: "Tu t'en fiches, tu as ta gourde à la maison",
	    	image: mainGameQuizA3PropositionsImages[4],
	    	stats_environnement: 2,
	    	stats_sante: 2,
	    	stats_social: 1
	    },	   
	    proposition03: 
	    {
	    	proposition: "Tu achètes un bidon de 5l comme d'habitude",
	    	image: mainGameQuizA3PropositionsImages[5],
	    	stats_environnement: 1,
	    	stats_sante: 2,
	    	stats_social: 1
	    }
	},
	question03: 
	{
		// number in % from background size
		image: mainGameQuizABgImages[2],
	    sizeOrigin: 4,
	    xOrigin: 35,
	    yOrigin: 45,
	   	tag: document.getElementById("questionButton03"),
	    question: "Tu as un petit creux, tes parents te laissent choisir un produit. Lequel choisis-tu ?",
	    proposition01: 
	    {
	    	proposition: "Des chips",
	    	image: mainGameQuizA3PropositionsImages[6],
	    	stats_environnement: 0,
	    	stats_sante: 0,
	    	stats_social: 0
	    },
	    proposition02: 
	    {
	    	proposition: "Des bonbons",
	    	image: mainGameQuizA3PropositionsImages[7],
	    	stats_environnement: 1,
	    	stats_sante: 1,
	    	stats_social: 1
	    },	   
	    proposition03: 
	    {
	    	proposition: "Du chocolat équitable",
	    	image: mainGameQuizA3PropositionsImages[8],
	    	stats_environnement: 2,
	    	stats_sante: 1,
	    	stats_social: 2
	    },
	    openQuestion: "Qu'est-ce qui, dans ton alimentation, pourrait avoir un impact positif sur la planète ?"
	}
};