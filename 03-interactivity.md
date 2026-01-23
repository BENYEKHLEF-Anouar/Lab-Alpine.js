# Tuto 2 : Interactivité (Dropdown & Modal)

## Objectif
Gérer la visibilité des éléments (`x-show`) et les interactions utilisateur avancées (comme fermer un menu en cliquant ailleurs).

---

## 1. La directive `x-show`

La base de l'interactivité UI est souvent d'afficher ou masquer des choses.
`x-show` bascule la propriété CSS `display: none` sur un élément selon que l'expression est vraie ou fausse.

```html
<div x-data="{ open: false }">
    <button @click="open = !open">Basculer</button>
    
    <div x-show="open">
        Je suis visible !
    </div>
</div>
```

## 2. Ajouter des Transitions (`x-transition`)

Pour éviter que l'élément n'apparaisse brutalement, Alpine offre des transitions automatiques.
Il suffit d'ajouter l'attribut `x-transition`.

```html
<div x-show="open" x-transition>
    Apparition en douceur...
</div>
```

Vous pouvez personnaliser la durée et le type de transition (ex: `x-transition.duration.500ms`).

## 3. Exemple Concret : Le Dropdown Menu

Un menu déroulant classique nécessite souvent :
1.  Un bouton pour ouvrir/fermer.
2.  Le menu lui-même.
3.  **Important** : Le menu doit se fermer si on clique À CÔTÉ (Click Outside).

Alpine gère ça très facilement avec le modificateur `.outside`.

```html
<div x-data="{ open: false }" class="relative">
    <!-- Le déclencheur -->
    <button @click="open = !open">Options</button>

    <!-- Le Menu -->
    <div 
        x-show="open" 
        @click.outside="open = false"
        x-transition
        class="absolute bg-white shadow-lg p-4"
    >
        <a href="#">Éditer</a>
        <a href="#">Supprimer</a>
    </div>
</div>
```

*   `@click.outside="open = false"` : Si un clic est détecté en dehors de cet élément, `open` devient `false`. C'est magique. ✨

## 4. Exemple Concret : La Modale

Une modale fonctionne sur le même principe, souvent avec la touche `Echap` pour fermer.

```html
<div x-data="{ isOpen: false }">
    <button @click="isOpen = true">Ouvrir Modale</button>

    <!-- Overlay & Modale -->
    <div 
        x-show="isOpen" 
        class="fixed inset-0 bg-black/50 flex items-center justify-center"
    >
        <!-- Contenu de la modale -->
        <div 
            @click.outside="isOpen = false"
            @keydown.window.escape="isOpen = false"
            class="bg-white p-8 rounded shadow-xl"
        >
            <h2>Attention</h2>
            <p>Êtes-vous sûr ?</p>
            <button @click="isOpen = false">Fermer</button>
        </div>
    </div>
</div>
```

*   `@keydown.window.escape` : Écoute la touche Echap (Escape) sur toute la fenêtre (`window`) pour fermer la modale.

---
[Suivant : Tutoriel 3 - Données Asynchrones](./04-async-data.md)