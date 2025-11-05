# 📚 Guide d'utilisation du Seeder IESCA

## 🎯 Seeder créé : `IESCASimpleStructureSeeder`

Ce seeder crée une structure simplifiée avec **une seule classe par spécialité/niveau**.

## 📊 Données créées

### I. Niveaux Académiques (3)
- L1 (Licence 1)
- L2 (Licence 2)
- L3 (Licence 3)

### II. Filières (4)
- Sciences et Administration des Affaires (SAA)
- Génie Informatique (GI)
- Sciences Juridiques (SJ)
- Sciences Commerciales (SC)

### III. Spécialités (10)
**SAA (2 spécialités):**
- Management et entrepreneuriat (M)
- Gestion des ressources humaines (RH)

**GI (2 spécialités):**
- Réseaux et télécommunications
- Informatique de gestion

**SJ (3 spécialités):**
- Droit privé (P)
- Droit public (Pu)
- Droit des affaires (Daff)

**SC (3 spécialités):**
- Comptabilité (Cpt)
- Management de la chaîne logistique (Log)
- Banque, Assurance et finances (B/A/F)

### IV. Classes (14 classes)

**L1 (9 classes):**
1. L1 SAA-M
2. L1 SAA-RH
3. L1 GI-Réseaux
4. L1 GI-InfoG
5. L1 SJ-P
6. L1 SJ-Pu
7. L1 SC-Cpt
8. L1 SC-Log
9. L1 SC-B/A/F

**L2 (3 classes):**
10. L2 SAA-M
11. L2 GI-Réseaux
12. L2 SC-Cpt

**L3 (2 classes):**
13. L3 SAA-RH
14. L3 SJ-Daff

### V. Cours (5 cours pour L1 SAA-M)
- Introduction au Management (SAA101)
- Comptabilité Générale I (SAA102)
- Marketing Fondamental (SAA103)
- Gestion des Ressources Humaines (SAA104)
- Économie Générale (SAA105)

### VI. Calendrier des Cours (3 entrées pour L1 SAA-M)
- **Lundi 08h00-10h00** : Introduction au Management (Prof. Diallo)
- **Mardi 10h00-13h00** : Comptabilité Générale I (Prof. Traoré)
- **Jeudi 14h00-16h00** : Marketing Fondamental (Prof. Keita)

### VII. Enseignants de test (3)
- Prof. Diallo (prof.diallo@iesca.com)
- Prof. Traoré (prof.traore@iesca.com)
- Prof. Keita (prof.keita@iesca.com)
- **Mot de passe par défaut** : `password`

## 🚀 Utilisation

### Sur votre machine locale

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/IECS2

# Exécuter le seeder
php artisan db:seed --class=IESCASimpleStructureSeeder
```

### Sur le serveur en production

```bash
cd /home/u570136219/domains/iesc.osmoseconsulting.fr/public_html

# Exécuter le seeder (--force pour éviter les confirmations)
php artisan db:seed --class=IESCASimpleStructureSeeder --force
```

## 📝 Notes importantes

1. **Le seeder utilise `firstOrCreate`** : il ne créera pas de doublons si vous l'exécutez plusieurs fois
2. **Les cours sont associés à la classe L1 SAA-M** : vous pouvez ajouter d'autres cours pour les autres classes
3. **Les enseignants ont le mot de passe `password`** : changez-le en production !
4. **Le calendrier est créé pour le semestre 1** : vous pouvez ajouter le semestre 2

## 🔧 Ajouter plus de données

Pour ajouter plus de cours ou de calendrier, vous pouvez :

1. **Modifier le seeder** : Ajoutez plus de données dans les tableaux
2. **Créer un seeder supplémentaire** : Pour les cours des autres classes
3. **Utiliser l'interface admin** : Une fois les données de base créées

## ✅ Vérification

Après avoir exécuté le seeder, vérifiez :

```bash
# Vérifier les classes créées
php artisan tinker
>>> \App\Models\Classe::count()
>>> \App\Models\Classe::with('filiere', 'niveau')->get()

# Vérifier le calendrier
>>> \App\Models\CalendrierCours::with('classe', 'cours')->get()
```

## 🎯 Prochaines étapes

1. Exécutez le seeder sur votre serveur
2. Testez l'affichage du calendrier pour la classe L1 SAA-M
3. Ajoutez plus de cours et de calendrier via l'interface admin
4. Affectez des étudiants aux classes

