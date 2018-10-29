# Documentation

## Ajouter un Nouveau Thème

1. les thèmes se gèrent dans fichier "assets/js/question.js"
2. ajouter le nom du nouveau thème dans l'array "allThemesNames". Ex: allThemesNames = ["alimentation", "dechets", "civisme"]
3. ajouter le quiz correpondant au thème dans l'array "allThemes". Ex: allThemes = ["quizA1", "quizB1", "quizC1"], "quizA1" pour "alimentation", "quizB1" pour "dechets", etc.
4. un exemple est présent dans le fichier "question.js" pour aider.
5. pour personnaliser les thèmes, il suffit de se baser sur les 3 objets "quizAx" présents. Toutes les propriétés nécessaires pour réaliser un nouveau thème sont présentes dans ces objects.
6. concernant les deux petits jeux récréatifs, ils se lancent à partir de la fonction "saveAnswer" se trouvant dans "assets/js/game.js".