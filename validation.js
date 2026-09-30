// Règles de validation : champ -> [regex, message, obligatoire ?]
const regles = {
    telephone: [/^(?:\+212|00212|0)[\s.-]?[5-7](?:[\s.-]?\d{2}){4}$/, 'Numéro de téléphone marocain invalide', false],
    email:     [/^[^\s@]+@[^\s@]+\.[A-Za-z]{2,}$/,                     'Adresse email invalide',                 true]
};


function valider(nom) {
    const [regex, message, obligatoire] = regles[nom];
    const champ = document.getElementById(nom);
    const valeur = champ.value.trim();
    const ok = valeur === '' ? !obligatoire : regex.test(valeur);
    document.getElementById('msg-' + nom).textContent = ok ? '' : message;
    return ok;
}

const form = document.querySelector('form');

// À l'envoi : bloquer si un champ est invalide
form.addEventListener('submit', function (e) {
    const invalides = Object.keys(regles).filter(nom => !valider(nom));
    if (invalides.length) {
        e.preventDefault();
        document.getElementById(invalides[0]).focus();
    }
});


Object.keys(regles).forEach(nom =>
    document.getElementById(nom).addEventListener('blur', () => valider(nom))
);


(new URLSearchParams(location.search).get('erreur') || '')
    .split(',')
    .forEach(nom => { if (regles[nom]) valider(nom); });
