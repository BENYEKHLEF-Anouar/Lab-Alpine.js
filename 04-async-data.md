# Tuto 3 : Données Asynchrones (Fetch API)

## Objectif
Apprendre à charger des données depuis une API externe (ex: une liste d'utilisateurs) et les afficher dynamiquement.

---

## 1. L'initialisation avec `x-init`

La directive `x-init` permet d'exécuter du code JavaScript dès qu'un composant est initialisé par Alpine. C'est l'endroit idéal pour lancer des requêtes HTTP.

```html
<div x-data="{ posts: [] }" x-init="console.log('Je suis chargé !')">
</div>
```

## 2. Récupérer des données (`fetch`)

On combine `x-init` avec la méthode standard `fetch()` de JavaScript.

```html
<div 
    x-data="{ users: [] }" 
    x-init="users = await (await fetch('https://jsonplaceholder.typicode.com/users')).json()"
>
    <!-- Les données seront dans 'users' -->
</div>
```

> **Note :** Alpine.js supporte `await` directement dans ses expressions.

## 3. Afficher une Liste (`x-for`)

Pour afficher les données reçues, on utilise `x-for`.
⚠️ **Attention** : `x-for` doit TOUJOURS être placé sur une balise `<template>`.

```html
<ul>
    <template x-for="user in users" :key="user.id">
        <li x-text="user.name"></li>
    </template>
</ul>
```

## 4. Gérer l'État de Chargement (Loading UI)

Il est important de montrer à l'utilisateur que ça charge.

```html
<div x-data="{ isLoading: true, users: [] }" 
     x-init="
        const response = await fetch('https://jsonplaceholder.typicode.com/users');
        users = await response.json();
        isLoading = false;
     "
>
    <!-- Spinner -->
    <div x-show="isLoading">Chargement...</div>

    <!-- Liste -->
    <ul x-show="!isLoading">
        <template x-for="user in users">
            <li x-text="user.name"></li>
        </template>
    </ul>
</div>
```

---
[Retour au Sommaire](./README.md)