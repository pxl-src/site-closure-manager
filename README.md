# Site Closure Manager - Plugin WordPress

## 📋 Description

**Site Closure Manager** est un plugin WordPress/WooCommerce professionnel qui vous permet de fermer temporairement votre site à une date et heure précises, tout en affichant une page de maintenance élégante et personnalisable à vos visiteurs.

## ✨ Fonctionnalités

### Gestion de fermeture
- ✅ Planification de fermeture avec date et heure précises
- ✅ Date de réouverture automatique (optionnelle)
- ✅ Activation/désactivation en un clic
- ✅ Compte à rebours jusqu'à la réouverture

### Page de maintenance personnalisable
- 🎨 Couleurs personnalisables (fond, texte, accent)
- 🖼️ Upload de logo personnalisé
- 📝 Titre et message personnalisables
- 📧 Affichage d'un email de contact
- 🔗 Liens vers les réseaux sociaux (Facebook, Twitter, Instagram, LinkedIn)
- 📱 Design responsive et moderne

### Contrôle d'accès
- 👥 Définir les rôles WordPress pouvant contourner la fermeture
- 🔐 Les administrateurs peuvent toujours accéder au site
- 🔍 Prévisualisation de la page de maintenance

### Optimisation technique
- ⚡ Code optimisé et performant
- 🔒 Sécurité renforcée
- 🌐 Header HTTP 503 (Service Unavailable)
- 📊 Compatible WooCommerce

## 📦 Installation

### Installation manuelle

1. **Télécharger le plugin**
   - Téléchargez tous les fichiers du plugin

2. **Structure des fichiers**
   ```
   site-closure-manager/
   ├── site-closure-manager.php (fichier principal)
   ├── templates/
   │   ├── admin-page.php
   │   └── closure-page.php
   ├── assets/
   │   ├── css/
   │   │   └── admin-style.css
   │   └── js/
   │       └── admin-script.js
   └── README.md
   ```

3. **Installation**
   - Compressez le dossier `site-closure-manager` en ZIP
   - Allez dans WordPress : Extensions → Ajouter
   - Cliquez sur "Téléverser une extension"
   - Sélectionnez le fichier ZIP
   - Cliquez sur "Installer maintenant"
   - Activez le plugin

### Installation via FTP

1. Uploadez le dossier `site-closure-manager` dans `/wp-content/plugins/`
2. Allez dans Extensions et activez "Site Closure Manager"

## 🚀 Utilisation

### Configuration de base

1. **Accéder aux paramètres**
   - Dans le menu WordPress, cliquez sur "Fermeture Site"

2. **Configurer la fermeture**
   - Définissez la **date de fermeture** (obligatoire)
   - Définissez l'**heure de fermeture** (par défaut 00:00)
   - Optionnel : définissez la **date de réouverture**
   - Optionnel : définissez l'**heure de réouverture**

3. **Personnaliser le contenu**
   - Saisissez le **titre** de la page (ex: "Site temporairement fermé")
   - Rédigez un **message** pour vos visiteurs
   - Cochez "Afficher le compte à rebours" si souhaité

4. **Personnaliser l'apparence**
   - Choisissez la **couleur de fond**
   - Choisissez la **couleur du texte**
   - Choisissez la **couleur d'accent**
   - Uploadez votre **logo** (optionnel)

5. **Ajouter les contacts**
   - Saisissez un **email de contact**
   - Activez les **réseaux sociaux** si souhaité
   - Ajoutez vos liens sociaux

6. **Définir les accès**
   - Sélectionnez les rôles qui peuvent contourner la fermeture
   - Par défaut, seuls les administrateurs ont accès

7. **Activer la fermeture**
   - Basculez l'interrupteur "Activer la fermeture du site"
   - Cliquez sur "Enregistrer les modifications"

### Prévisualisation

Avant d'activer la fermeture, vous pouvez prévisualiser la page :
- Cliquez sur le bouton "Prévisualiser"
- La page s'ouvrira dans un nouvel onglet

### Cas d'usage

#### Fermeture pour maintenance
```
Date de fermeture : 2026-03-15
Heure de fermeture : 22:00
Date de réouverture : 2026-03-16
Heure de réouverture : 06:00
Message : "Notre site est en maintenance. Nous serons de retour demain matin."
Compte à rebours : ✅ Activé
```

#### Fermeture pour vacances
```
Date de fermeture : 2026-07-01
Heure de fermeture : 18:00
Date de réouverture : 2026-08-15
Heure de réouverture : 09:00
Message : "Nous sommes en vacances ! Rendez-vous le 15 août."
Compte à rebours : ✅ Activé
```

#### Fermeture temporaire indéfinie
```
Date de fermeture : 2026-04-01
Heure de fermeture : 00:00
Date de réouverture : (vide)
Message : "Notre site est temporairement fermé. Nous vous informerons de la réouverture."
Compte à rebours : ❌ Désactivé
```

## 🎨 Personnalisation avancée

### Modifier le template de la page

Le template se trouve dans `templates/closure-page.php`. Vous pouvez le personnaliser pour ajouter :
- Des animations CSS personnalisées
- Des sections supplémentaires
- Votre propre HTML/CSS

### Ajouter des styles personnalisés

Créez un fichier CSS personnalisé et ajoutez-le via un hook WordPress :

```php
add_action('scm_closure_page_head', function() {
    echo '<link rel="stylesheet" href="' . get_stylesheet_directory_uri() . '/custom-closure.css">';
});
```

### Filtres disponibles

Le plugin offre plusieurs filtres pour les développeurs :

```php
// Modifier le message de fermeture
add_filter('scm_closure_message', function($message, $settings) {
    return $message . '<br>Contactez-nous au 01 23 45 67 89';
}, 10, 2);

// Modifier le titre de la page
add_filter('scm_closure_title', function($title) {
    return 'Nous revenons bientôt !';
});

// Ajouter du contenu supplémentaire
add_action('scm_closure_page_footer', function() {
    echo '<p>Suivez-nous sur nos réseaux sociaux !</p>';
});
```

## 🔧 Configuration technique

### Compatibilité
- WordPress 5.8+
- PHP 7.4+
- Compatible WooCommerce
- Compatible tous thèmes WordPress

### Headers HTTP
Le plugin envoie automatiquement les headers appropriés :
- **Status:** 503 Service Unavailable
- **Retry-After:** 3600 secondes (1 heure)

### Sécurité
- Validation et sanitization de tous les inputs
- Nonces CSRF pour les formulaires
- Échappement des sorties
- Vérification des capacités utilisateur
- Protection contre l'accès direct aux fichiers

### Performance
- Code optimisé
- Pas de requêtes supplémentaires en frontend
- CSS/JS minifiables
- Compatible avec les plugins de cache

## ❓ FAQ

### Le site est-il vraiment inaccessible ?
Oui, pour tous les visiteurs non authentifiés. Les utilisateurs avec les rôles définis dans les paramètres peuvent toujours accéder au site.

### Puis-je tester sans bloquer le site ?
Oui, utilisez le bouton "Prévisualiser" pour voir la page sans activer la fermeture.

### Que se passe-t-il si j'oublie de désactiver ?
Si vous avez défini une date de réouverture, le site se rouvrira automatiquement. Sinon, vous devrez vous connecter et désactiver manuellement la fermeture.

### Le plugin affecte-t-il le SEO ?
Le plugin envoie un code 503 qui indique aux moteurs de recherche que la fermeture est temporaire, donc pas d'impact SEO négatif.

### Puis-je personnaliser complètement la page ?
Oui, le template est modifiable et plusieurs hooks sont disponibles pour les développeurs.

### Est-ce compatible avec mon thème ?
Oui, le plugin fonctionne indépendamment du thème actif.

### Les commandes WooCommerce sont-elles bloquées ?
Oui, pendant la fermeture, aucune commande ne peut être passée. Les clients existants ne peuvent pas accéder à leurs comptes.

## 🐛 Dépannage

### La page de fermeture ne s'affiche pas
1. Vérifiez que la fermeture est activée
2. Vérifiez la date et l'heure de fermeture
3. Vérifiez que vous n'êtes pas connecté avec un rôle ayant accès
4. Videz le cache de votre site
5. Désactivez les plugins de cache temporairement

### Les couleurs ne changent pas
1. Assurez-vous d'avoir sauvegardé les modifications
2. Videz le cache du navigateur (Ctrl+F5)
3. Vérifiez les codes couleurs hexadécimaux

### Le compte à rebours ne fonctionne pas
1. Vérifiez qu'une date de réouverture est définie
2. Vérifiez que l'option "Afficher le compte à rebours" est cochée
3. Vérifiez la console JavaScript pour les erreurs

### Je ne peux plus accéder au site
Si vous êtes bloqué :
1. Connectez-vous via wp-login.php
2. Accédez à Extensions
3. Désactivez temporairement le plugin
4. Ou connectez-vous via FTP et renommez le dossier du plugin

## 📝 Changelog

### Version 1.0.0 (2026)
- 🎉 Version initiale
- ✅ Gestion de fermeture planifiée
- ✅ Page de maintenance personnalisable
- ✅ Compte à rebours
- ✅ Gestion des rôles d'accès
- ✅ Interface d'administration complète
- ✅ Support des réseaux sociaux
- ✅ Design responsive

## 📄 Licence

Ce plugin est distribué sous licence GPL v2 ou ultérieure.

## 👨‍💻 Support

Pour toute question ou problème :
- Consultez la FAQ ci-dessus
- Vérifiez la documentation
- Contactez le support

## 🙏 Crédits

Développé avec ❤️ pour la communauté WordPress/WooCommerce

---

**Note importante:** Testez toujours le plugin sur un environnement de staging avant de l'utiliser en production.
