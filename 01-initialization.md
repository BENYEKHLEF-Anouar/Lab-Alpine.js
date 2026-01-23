# Tuto 1 : Initialisation & Réactivité

## Objectif
Installer Alpine.js via CDN et créer votre premier composant réactif (un compteur).

---

## 1. Installation (CDN)

C'est la méthode la plus simple. Ajoutez simplement cette balise `<script>` dans le `<head>` de votre fichier HTML.

```html
<head>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
```

> **Note** : L'attribut `defer` est important pour qu'Alpine attende que le HTML soit parsé avant de s'initialiser.

## 2. Votre Premier Composant (`x-data`)

En Alpine, tout commence avec `x-data`. Cela définit la portée (scope) d'un composant.

```html
<div x-data="{ message: 'Hello World' }">
    <h1 x-text="message"></h1>
</div>
```

*   `x-data` : Définit les données réactives de ce bloc HTML.
*   `x-text` : Dit à Alpine de mettre le contenu du `h1` à jour avec la valeur de `message`.

## 3. Ajouter de l'Interactivité (Le Compteur)

Créons le classique "Compteur" pour voir la réactivité en action.

### Étape A : La Structure
```html
<div x-data="{ count: 0 }">
    <button>Incrémenter</button>
    
    <span x-text="count"></span>
</div>
```

### Étape B : L'Écoute d'Événement (`@click`)
Nous voulons augmenter `count` quand on clique sur le bouton.

```html
<div x-data="{ count: 0 }">
    <!-- Au clic, on incrémente count de 1 -->
    <button @click="count++">Incrémenter</button>
    
    <span x-text="count">0</span>
</div>
```

**Ce qui se passe :**
1.  Vous cliquez.
2.  Alpine intercepte le clic.
3.  Il exécute `count++`.
4.  Comme `count` a changé, Alpine met automatiquement à jour tous les éléments qui utilisent `count` (ici, le `span`).

## 4. Exercice Rapide

Essayez d'ajouter un bouton "Décrémenter" qui diminue le compteur.

<details>
<summary>Voir la solution</summary>

```html
<button @click="count--">Décrémenter</button>
```
</details>

---
[Suivant : Tutoriel 2 - Interactivité](./02-interactivity.md)